(function() {

    //Variable where public methods and variables will be stored
    var _self = { metrics: {}, widgets: {} };

    //Path to the SDH-API server with the trailing slash
    var _serverUrl;

    // Array with the information about metrics
    var _metricsInfo;

    // Storage of the metrics data
    var _metricsStorage = {}; //TODO: multi-level cache

    var _metricContexts = {};

    // Contains a list that links user callbacks (given as parameter at the observe methods) with the internal
    // callbacks. It is need to remove handlers when not used and free memory.
    var _event_handlers = [];

    // This is a variable to make the events invisible outside the framework
    var _eventBox = {};

    var _isReady = false;

    var FRAMEWORK_NAME = "frameworkname";



    /*
     FRAMEWORK PRIVATE METHODS
     */

    var error = function error(message) {
        console.error("[" + FRAMEWORK_NAME +  "] " + message);
    };


    var requestJSON = function requestJSON(path, queryParams, callback, maxRetries) {

        if(typeof maxRetries === 'undefined'){
            maxRetries = 2; //So up to 3 times will be requested
        }

        $.getJSON( _serverUrl + path, queryParams, callback).fail( function(d, textStatus, e) {
            error("getJSON failed, status: " + textStatus + ", error: "+e);

            //Retry the request
            if (maxRetries > 0) {
                requestJSON(path, queryParams, callback, --maxRetries);
            }

        });

    };

    /**
     * Fills _metricsInfo hashmap with the metrics info and the following structure:
     * {
     *       "{metric-id}": {
     *           path:"yourpath/../sdfsdf",
     *           params: ['param1', 'param2']
     *       },
     *       ...
     *   }
     * @param onReady
     */
    var loadMetricsInfo = function loadMetricsInfo(onReady) {

        requestJSON("metrics", null, function(data){

            /* Receives a hashmap with the following structure:
            {
                <metric-id>: {
                    path:"yourpath/../sdfsdf",
                    description: "metric description",
                    params: {
                         <paramId>: { // id = "projectid" without ":"
                             description:"param description",
                             name:"param beauty name"
                         },
                         ...
                    },
                    queryparams:{
                        'from': "date in ms",
                        'to': "date in ms",
                        'series': "Bool [true/false]",
                        'step': "for average series, this parameter establish the distance in days between points. 1day default."
                    }
                },
                ...
            }
            */

            _metricsInfo = {};

            for(var metricId in data) {
                _metricsInfo[metricId] = {
                    path: _metricsInfo[metricId].path,
                    params: []
                };

                for(var paramId in _metricsInfo[metricId].params) {
                    _metricsInfo[metricId].params.push(paramId);
                }

            }

            if('function' === typeof onReady) {
                onReady();
            }
        });
    };

    /**
     * Checks if the metric object has all the information that is needed to request the metric data
     * @param metric A metric object. At least must have the id. Can have other parameters, like range, userId...
     * @returns {boolean}
     */
    var metricCanBeRequested = function metricCanBeRequested(metric) {

        if(metric.id == null) {
            return false;
        }

        var metricInfo = _metricsInfo[metric.id];

        if(metricInfo == null) {
            return false;
        }

        for(var i in metricInfo.params) {
            var paramId = metricInfo.params[i];
            var paramValue = metric[paramId];

            if(paramValue == null) {
                return false;
            }
        }

        return true;

    };

    /**
     * Checks if all the given metrics fulfill all the requirements to be requested
     * @param metrics Array of normalized metrics
     * @returns {boolean}
     */
    var allMetricsCanBeRequested = function allMetricsCanBeRequested(metrics) {
        for(var i in metrics) {
            if(!metricCanBeRequested(metrics[i])) {
                return false;
            }
        }

        return true;
    };

    /**
     * Request a given metric
     * @param metric
     */
    var makeMetricRequest = function makeMetricRequest(metricId, params, queryParams, callback) {

        var metricInfo = _metricsInfo[metricId];

        if(metricInfo != null) {

            /* Generate path */
            var path = metricInfo.path;

            // Replace params in url skeleton
            for(var i in metricInfo.params) {

                var paramId = metricInfo.params[i];
                var paramValue = params[paramId];

                if(paramValue != null) {
                    path.replace(':'+paramId,  paramValue);
                } else {
                    error("Metric '"+ metricId + "' needs parameter '"+ paramId +"'.");
                }

            }

            /* Make the request */
            requestJSON(path, queryParams, callback);

        } else {
            error("Metric '"+ metricId + "' does not exist.");
        }

    };

    /**
     * Requests multiple metrics
     * @param metrics Normalized metric
     * @param callback
     */
    var multipleMetricsRequest = function multipleMetricsRequest(metrics, callback) {

        var completedRequests = 0;
        var allData = {};

        var onMetricReady = function(metricId, data) {
            allData[metricId] = data;

            if(++completedRequests === metrics.length) {
                callback(allData);
            }
        };

        for(var i in metrics) {

            var metricId = metrics[i].id;
            var params = metrics[i]; //Can use the metric itself because only the needed elements will be used as params.
            var queryParams = {};

            //All the range elements are queryParams
            for(var name in queryParams.range) {
                queryParams[name] = queryParams.range[name];
            }

            //Series is also a queryParam
            queryParams.series = metrics[i].series;

            makeMetricRequest(metricId, params, queryParams, onMetricReady.bind(undefined, metricId))
        }

    };


    /**
     * Converts the array of metrics containing a mixture of strings (for simple metrics) and objects (for complex metrics)
     * into an array of objects with at least an id.
     * @param metrics Array of metrics containing a mixture of strings (for simple metrics) and objects (for complex metrics).
     * It can be modified, so consider cloning it if necessary.
     * @returns {Array}
     */
    var normalizeMetrics = function normalizeMetrics(metrics) {

        var newMetricsParam = [];
        for(var i in metrics) {

            if('string' === typeof metrics[i]) {
                newMetricsParam.push({id: metrics[i]});
            } else if('object' === typeof metrics[i]) {

                //Series boolean can not be given by the user
                if(metrics[i].series != null) {
                    delete metrics[i].series;
                }

                newMetricsParam.push(metrics[i]);
            } else {
                error("One of the metric given was not string nor object");
            }
        }

        return newMetricsParam;

    };

    /**
     * Converts the context to a normalized form to use it internally
     * @param context Context object. It can be modified, so consider cloning it if necessary.
     * @returns {Object}
     */
    var normalizeContext = function normalizeContext(context) {

        //Series parameter can not be set by a context. Neither range.step parameter.
        if(context.series != null || (context.range != null && context.range.step != null)) {

            if(context.series != null){
                delete context.series;
            }

            if(context.range != null && context.range.step != null) {
                delete context.range.step;
            }

        }

        return context;

    };

    /** Clone the object
     * @method
     * @return {object} */
    var clone = function clone(obj1) {
        var result;

        if (obj1 == null) {
            return obj1;
        } else if (Array.isArray(obj1)) {
            result = [];
        } else if (typeof obj1 === 'object') {
            result = {};
        } else {
            return obj1;
        }

        for (var key in obj1) {
            result[key] = clone(obj1[key]);
        }

        return result;
    };

    /** Deep merge in obj1 object. (Priority obj2)
     * @method
     * @return {object}
     */
    var mergeObjects = function mergeObjects(obj1, obj2) {
        if (Array.isArray(obj2) && Array.isArray(obj1)) {
            // Merge Arrays
            var i;
            for (i = 0; i < obj2.length; i ++) {
                if (typeof obj2[i] === 'object' && typeof obj1[i] === 'object') {
                    obj1[i] = mergeObjects(obj1[i], obj2[i]);
                } else {
                    obj1[i] = obj2[i];
                }
            }
        } else if (Array.isArray(obj2)) {
            // Priority obj2
            obj1 = obj2;
        } else {
            // object case j
            for (var p in obj2) {
                if(obj1.hasOwnProperty(p)){
                    if (typeof obj2[p] === 'object' && typeof obj1[p] === 'object') {
                        obj1[p] = mergeObjects(obj1[p], obj2[p]);
                    } else {
                        obj1[p] = obj2[p];
                    }
                } else {
                    obj1[p] = obj2[p];
                }
            }
        }
        return obj1;
    };

    /**
     * Combines an incomplete metric with a context in order to create a complete metric to make a request with.
     * @param metrics
     * @param context Context data
     */
    var combineMetricsWithContext = function combineMetricsWithContext(metrics, context) {

        var newMetrics = [];

        normalizeContext(context);

        for(var i in metrics) {
            newMetrics.push(mergeObjects(clone(metrics[i]), context));
        }
    };

    /**
     *
     * @param metrics
     * @param callback
     * @param contextId
     * @param series
     * @param step
     */
    var observeMetrics = function observeMetrics(metrics, callback, contextId, series, step) {

        if('function' !== typeof callback){
            error("Method 'observeData' requires a valid callback function.");
            return;
        }

        if(!Array.isArray(metrics) || metrics.length === 0 ) {
            error("Method 'observeData' has received an invalid metrics param.");
            return;
        }

        if('string' !== typeof contextId || _metricContexts[contextId] == null) {
            contextId = null;
        }

        //Normalize the array of metrics
        metrics = normalizeMetrics(metrics);

        //Set all metrics type (series = false) and if it is a series set the step if defined
        for(var i in metrics) {

            //Set the type
            metrics[i].series = series;

            //Set the step
            if(series && step != null) {
                if(metrics[i].range == null) {
                    metrics[i].range = {};
                }
                metrics[i].range.step = step;
            }
        }

        //If context is defined, combine the metrics with the context in order to create more complete metrics that could
        // be requested.
        if(contextId != null) {

            //Combine the metrics with the context in order to create more complete metrics that could be requested.
            var metricsWithContext = combineMetricsWithContext(metrics, _metricContexts[contextId].data);

            //Request all the metrics if possible
            if(allMetricsCanBeRequested(metricsWithContext)) {
                multipleMetricsRequest(metricsWithContext, callback);
            }

            //Create the CONTEXT event handler
            var contextEventHandler = function(event, contextCounter) {

                //If it is not the last context event launched, ignore the data because there is another more recent
                // event being executed
                if(contextCounter != _metricContexts[contextId].updateCounter){
                    return;
                }

                //Update the metrics with the context data
                var metricsWithContext = combineMetricsWithContext(metrics, _metricContexts[contextId].data);

                if(allMetricsCanBeRequested(metricsWithContext)) {
                    multipleMetricsRequest(metricsWithContext, callback);
                }
            };

            //Link user callbacks with event handlers
            _event_handlers.push({
                userCallback: callback,
                context: {
                    id: contextId,
                    handler: contextEventHandler
                }
            });

            // Create the CONTEXT event listener
            $(_eventBox).on("CONTEXT" + contextId, contextEventHandler);

        } else { //No context is set

            //Request all the metrics
            if(allMetricsCanBeRequested(metricsWithContext)) {
                multipleMetricsRequest(metricsWithContext, callback);
            } else {
                error("Some of the metrics have not information enough for an 'observeData' without context");
            }
        }

    };




    /*
     FRAMEWORK PUBLIC METHODS
     */

    /**
     *
     * @param metrics Array with metrics. Each metric can be an string or an object. The object must have the following
     * format: {
     *              id: String,
     *              <param1Id>: String,
     *              <paramxId>: String,
     *              range: {
     *                  from: Date,
     *                  to: Date
     *              }
     *          }
     *  For example: {
     *                  id: user-commits,
     *                  userId: 123,
     *                  range: {
     *                      from: new Date(2000, 0, 14)
     *                  }
     *               }
     * @param callback
     * @param contextId (Optional)
     */
    _self.metrics.observeData = function observeData(metrics, callback, contextId) {
        observeMetrics(metrics, callback, contextId, false);
    };

    /**
     *
     * @param metrics Array with metrics. Each metric can be an string or an object. The object must have the following
     * format (id is the only compulsory element of the object): {
     *              id: String,
     *              {param1Id}: String,
     *              {paramxId}: String,
     *              range: {
     *                  from: Date,
     *                  to: Date,
     *                  step: Number of days
     *              }
     *          }
     *  For example: {
     *                  id: user-commits,
     *                  userId: 123,
     *                  range: {
     *                      from: new Date(2000, 0, 14),
     *                      step: 7
     *                  }
     *               }
     * @param callback
     * @param contextId (Optional)
     * @param step (Optional)
     */
    _self.metrics.observeSeries = function observeSeries(metrics, callback, contextId, step) {
        observeMetrics(metrics, callback, contextId, true, step);
    };

    /**
     * Cancels observing for an specific callback
     * @param callback The callback that was given to the observe methods
     */
    _self.metrics.stopObserve = function stopObserve(callback) {
        for (var i in _event_handlers) {
            if(_event_handlers[i].userCallback === callback) {
                $(_eventBox).off("CONTEXT" + _event_handlers[i].context.id, _event_handlers[i].context.handler);
                _event_handlers.splice(i, 1);
                break;
            }
        }
    };

    /**
     * Cancels observing for everything.
     */
    _self.metrics.stopAllObserves = function stopAllObserves() {

        //Remove all the event handlers
        for (var i in _event_handlers) {
            $(_eventBox).off("CONTEXT" + _event_handlers[i].context.id, _event_handlers[i].context.handler);
        }

        //Empty the array
        _event_handlers.splice(0, _event_handlers.length);

    };

    /**
     *
     * @param contextId
     * @param range
     */
    _self.metrics.updateContext = function(contextId, contextData) {

        if('string' !== typeof contextId) {
            error("Method 'updateRange' requires a string for contextId param.");
            return;
        }

        //Convert context to a common format
        normalizeContext(contextData);

        if(_metricContexts[contextId] == null) {
            _metricContexts[contextId] = { updateCounter: 0, data: [] };
        }

        //Update values of the context (if null, remove it)
        var hasChanged = false;
        for(var name in contextData) {

            var newValue = contextData[name];
            var oldValue = _metricContexts[contextId].data[name];

            if(newValue != null && newValue != oldValue) {
                _metricContexts[contextId][name] = newValue;
                hasChanged = true;
            } else if(oldValue != null) {
                delete _metricContexts[contextId][name];
            }
        }

        //Trigger an event to indicate that the context has changed
        if(hasChanged) {
            _metricContexts[contextId].updateCounter++;
            $(_eventBox).trigger("CONTEXT" + contextId, [_metricContexts[contextId].updateCounter]);
        }


    };


    /*
     FRAMEWORK INITIALIZATION
     */

    /**
     * Method that makes the initial checks to determine if the framework can be initialized
     * @returns {boolean}
     */
    var frameworkPreCheck = function frameworkPreCheck(){

        /* CHECK SHD-API SERVER URL */
        if(typeof SDH_API_URL === 'undefined'){
            error("SDH_API_URL global variable must be set with the url to the SDH-API server.");
            return false;
        }

        _serverUrl = SDH_API_URL.trim();

        if(_serverUrl.length === 0) {
            error("SDH_API_URL global variable must be set with a valid url to the SDH-API server.");
            return false;
        }
        if(_serverUrl.substr(-1) !== '/') {
            _serverUrl += '/';
        }

        /* CHECK JQUERY */
        if (typeof jQuery == 'undefined') {
            error("SDH_API_URL global variable must be set with a valid url to the SDH-API server.");
            return false;
        }

        return true;
    };

    /**
     * Add a callback that will be executed when the framework is ready
     * @param callback
     */
    var frameworkReady = function frameworkReady(callback) {
        if('undefined' === typeof _metricsInfo && typeof callback === 'function') {
            $(_eventBox).on("FRAMEWORK_READY", function() {
                $(_eventBox).off("FRAMEWORK_READY");
                callback();
            });
        } else if(typeof callback === 'function') {
            callback();
        }
    };

    var isFrameworkReady = function isFrameworkReady() {
        return _isReady;
    };

    var frameworkInit = function frameworkInit() {

        if(frameworkPreCheck()) {

            loadMetricsInfo(function(){

                window.framework.metrics = _self.metrics;

                _isReady = true;
                $(_eventBox).trigger("FRAMEWORK_READY");
            });

            window.framework = {
                metrics: {},
                widgets: {},
                ready: frameworkReady, /* Method to add a callback that will be executed when the framework is ready */
                isReady: isFrameworkReady
            };

        }

    };

    frameworkInit();

})();