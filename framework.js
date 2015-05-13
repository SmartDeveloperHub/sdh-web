(function() {

    /*
     -------------------------------
     ------ FRAMEWORK GLOBALS ------
     -------------------------------
     */

    //Variable where public methods and variables will be stored
    var _self = { metrics: {}, widgets: {} };

    //Path to the SDH-API server without the trailing slash
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

    var FRAMEWORK_NAME = "SDHWebFramework";



    /*
     -------------------------------
     -- FRAMEWORK PRIVATE METHODS --
     -------------------------------
     */

    /**
     * Prints a framework error
     * @param message Message to display.
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
     *           params: ['param1', 'param2'],
     *           queryParams: ['queryParam1']
     *       },
     *       ...
     *   }
     * @param onReady
     */
    var loadMetricsInfo = function loadMetricsInfo(onReady) {

        requestJSON("/api/", null, function(data) {

            var paths = data['swaggerjson']['paths'];

            //Count number of elements in paths
            var pathsLength = 0;
            for(var i in paths) pathsLength++;

            //Function to check if it has finished and call the callback
            var pathsProcessed = 0;
            var pathProcessed = function() {
                if(++pathsProcessed === pathsLength && 'function' === typeof onReady) {
                    onReady();
                }
            };

            //Initialize the _metricInfo object
            _metricsInfo = {};

            var isMetricList = /\/metrics\/$/;
            var isMetricListWithoutParams = /^((?!\{).)*\/metrics\/$/;
            var isSpecificMetric = /\/\{mid\}$/;


            //Iterate over the path of the api
            for(var path in paths) {

                var pathInfo = paths[path];

                if(isSpecificMetric.test(path)) { //Ignore specific metrics path (like /metrics/{mid}, /project/metrics/{mid}, etc)
                    pathProcessed(); //Finished processing this path
                    continue;

                } else if(isMetricListWithoutParams.test(path)) { // List of metrics (like /metrics/, /project/metrics/, etc)

                    // Make an api request to retrieve all the metrics
                    requestJSON(path, null, function(data) {

                        //Iterate over the metrics
                        for(var j = 0, len = data.length; j < len; ++j) {

                            var metricInfo = data[j];
                            var metricId = metricInfo['metricid'];
                            var metricPath = metricInfo['path'];

                            // Fill the _metricInfo array
                            _metricsInfo[metricId] = {
                                path: metricPath,
                                params: [], //list of url param names
                                queryParams: [] //list of query params
                            };

                            //Get the general metric path info (like /metrics/{mid})
                            var generalMetricPath = metricPath.substring(0, metricPath.lastIndexOf('/')) + '/{mid}';
                            var generalMetricPathInfo = paths[generalMetricPath];

                            if(generalMetricPathInfo == null) {
                                error("General metric path ("+generalMetricPathInfo+") does not exist in API path list.");
                                continue;
                            }

                            //Add the url params and query paramsto the list
                            if(generalMetricPathInfo['get']['parameters'] != null) {
                                var parameters = generalMetricPathInfo['get']['parameters'];

                                //Add all path parameters (not query params) and avoid 'mid'
                                for(var i = 0, len_i = parameters.length; i < len_i; i++) {
                                    if (parameters[i]['in'] === 'path' && parameters[i]['name'] !== 'mid') {
                                        _metricsInfo[metricId].params.push(parameters[i]['name']);
                                    } else if(parameters[i]['in'] === 'query') {
                                        _metricsInfo[metricId].queryParams.push(parameters[i]['name']);
                                    }
                                }
                            }
                        }

                        pathProcessed(); //Finished processing this path
                    });

                } else if(isMetricList.test(path)) { // Ignore list of metrics with params (like /project/{pid}/metrics/)
                    pathProcessed(); //Finished processing this path
                    continue;

                } else if(path === '/api/') { //Ignore api description path
                    pathProcessed(); //Finished processing this path
                    continue;

                } else { // Is a general list (like /, /projects/, etc)

                    var metricId = pathInfo['get']['operationId'];

                    _metricsInfo[metricId] = {
                        path: path,
                        params: [], //list of url param names
                        queryParams: [] //list of query params
                    };

                    //Add the url params and query params to the list
                    if(pathInfo['get']['parameters'] != null) {
                        var parameters = pathInfo['get']['parameters'];

                        for(var i = 0, len = parameters.length; i < len; i++) {
                            if (parameters[i]['in'] === 'path') {
                                _metricsInfo[metricId].params.push(parameters[i]['name']);
                            } else if(parameters[i]['in'] === 'query') {
                                _metricsInfo[metricId].queryParams.push(parameters[i]['name']);
                            }
                        }
                    }

                    pathProcessed(); //Finished processing this path

                }

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
                    path = path.replace('{'+paramId+'}',  paramValue);
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

            if(allData[metricId] == null) {
                allData[metricId] = [];
            }
            allData[metricId].push(data);

            if(++completedRequests === metrics.length) {
                sendDataEventToCallback(allData, callback);
            }
        };

        //Send a loading data event to the listener
        sendLoadingEventToCallback(callback);

        for(var i in metrics) {

            var metricId = metrics[i].id;
            var params = metrics[i]; //Can use the metric itself because only the needed elements will be used as params.
            var queryParams = {};

            //Everything that is not defined as metric param, is considered as queryparam
            for(var name in params) {
                if(_metricsInfo[metricId]['queryParams'].indexOf(name) !== -1) {
                    queryParams[name] =  params[name];
                }
            }

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
                newMetricsParam.push(metrics[i]);
            } else {
                error("One of the metric given was not string nor object");
            }
        }

        return newMetricsParam;

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

        for(var i in metrics) {

            //Clone the metric object to avoid modification
            var metric = clone(metrics[i]);

            //Clean the context
            var mergeContext = getCleanContextByMetric(context, metric);

            newMetrics.push(mergeObjects(metric, mergeContext));
        }

        return newMetrics;
    };

    /**
     * Initializes the context container for the given contextId
     * @param contextId
     */
    var initializeContext = function initializeContext(contextId) {
        _metricContexts[contextId] = { updateCounter: 0, data: {} };
    };

    /**
     * Gets a new context with only the params and query params accepted by the metric (taking into account the static
     * params).
     * @param context Object
     * @param metricId A metric object (only id and static are used).
     */
    var getCleanContextByMetric = function getCleanContextByMetric(context, metric) {
        var newContext = {};
        var metricInfo = _metricsInfo[metric['id']];

        var statics;
        if(metric['static'] != null){
            statics = metric['static'];
        } else {
            statics = [];
        }

        //Add all the params this metric accepts
        for(var i = 0, len = metricInfo.params.length; i < len; ++i) {
            var name =  metricInfo.params[i];
            if(context[name] !== undefined && statics.indexOf(name) === -1){
                newContext[name] = context[name];
            }
        }

        //Add all the query params this metric accepts
        for(var i = 0, len = metricInfo.queryParams.length; i < len; ++i) {
            var name =  metricInfo.queryParams[i];
            if(context[name] !== undefined && statics.indexOf(name) === -1){
                newContext[name] = context[name];
            }
        }

        return newContext;

    };

    /**
     * Checks if the given object is empty
     * @param o
     * @returns {boolean} True if empty; false otherwise.
     */
    var isObjectEmpty = function isObjectEmpty(o) {
        for(var i in o)
            return false;
        return true;
    };

    /**
     * Send a data event to the given observer. This means that the data the framework was loading is now ready.
     * @param data New data.
     * @param callback
     */
    var sendDataEventToCallback = function sendDataEventToCallback(data, callback) {
        if(typeof callback === "function") {
            callback({
                event: "data",
                data: data
            });
        }
    };

    /**
     * Send a loading event to the given observer. That means that the framework is loading new data for that observer.
     * @param callback
     */
    var sendLoadingEventToCallback = function sendLoadingEventToCallback(callback) {
        callback({
            event: "loading"
        });
    };



    /*
       -------------------------------
       -- FRAMEWORK PUBLIC METHODS ---
       -------------------------------
     */

    /**
     *
     * @param metrics Array with metrics. Each metric can be an string or an object. The object must have the following
     * format: {
     *              id: String,
     *              <param1Id>: String,
     *              <paramxId>: String,
     *          }
     *  For example: {
     *                   id: "usercommits",
     *                   uid: "pepito",
     *                   from :  new Date(),
     *                   max: 0,
     *                   static: ["from"] //Static makes this parameter unalterable by the context changes.
     *               }
     * @param callback Callback that receives an object containing at least an "event" that can be "data" or "loading".
     *  - loading means that the framework is retrieving new data for the observer.
     *  - data means that the new data is ready and can be accessed through the "data" element of the object returned to
     *  the callback. The "data" element of the object is a hashmap using as key the metricId of the requested metrics
     *  and as value an array with data for each of the request done for that metricId.
     * @param contextId
     */
    _self.metrics.observe = function observe(metrics, callback, contextId) {

        if('function' !== typeof callback){
            error("Method 'observeData' requires a valid callback function.");
            return;
        }

        if(!Array.isArray(metrics) || metrics.length === 0 ) {
            error("Method 'observeData' has received an invalid metrics param.");
            return;
        }

        if('string' !== typeof contextId) {
            contextId = null;
        } else if(_metricContexts[contextId] == null) {
            initializeContext(contextId);
        }

        //Normalize the array of metrics
        metrics = normalizeMetrics(metrics);

        //If context is defined, combine the metrics with the context in order to create more complete metrics that could
        // be requested.
        if(contextId != null) {

            //Combine the metrics with the context in order to create more complete metrics that could be requested.
            var metricsWithContext = combineMetricsWithContext(metrics, _metricContexts[contextId]['data']);

            //Request all the metrics if possible
            if(allMetricsCanBeRequested(metricsWithContext)) {
                multipleMetricsRequest(metricsWithContext, callback);
            }

            //Create the CONTEXT event handler
            var contextEventHandler = function(event, contextCounter, contextChanges) {

                //If it is not the last context event launched, ignore the data because there is another more recent
                // event being executed
                if(contextCounter != _metricContexts[contextId]['updateCounter']){
                    return;
                }

                //Check if the changes affect to the metrics
                var affectedMetrics = [];
                for(var i in metrics) {
                    var cleanContextChanges = getCleanContextByMetric(contextChanges, metrics[i]);
                    if(!isObjectEmpty(cleanContextChanges)){
                        affectedMetrics.push(metrics[i]);
                    }
                }

                if(affectedMetrics.length === 0) {
                    return; //The context change did not affect to none the metrics
                }

                //TODO: when implementing the cache, affectedMetrics should be used to only request the changed metrics.
                //Currently, as there is no cache, all the data must be requested because it is not stored anywhere.

                //Update the metrics with the context data
                var metricsWithContext = combineMetricsWithContext(metrics, _metricContexts[contextId]['data']);

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
            if(allMetricsCanBeRequested(metrics)) {
                multipleMetricsRequest(metrics, callback);
            } else {
                error("Some of the metrics have not information enough for an 'observe' without context or does not exist.");
            }
        }

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
     * Updates the context with the given data.
     * @param contextId String
     * @param contextData An object with the params to update. A param value of null means to delete the param from the
     * context, i.e the following sequence os updateContext with data {uid: 1, max:5, pid: 2} and {pid: 3, max:null}
     * will result in the following context: {uid: 1, pid:3}
     */
    _self.metrics.updateContext = function updateContext(contextId, contextData) {

        if('string' !== typeof contextId) {
            error("Method 'updateRange' requires a string for contextId param.");
            return;
        }

        if(_metricContexts[contextId] == null) {
            initializeContext(contextId);
        }

        //Update values of the context (if null, remove it)
        var hasChanged = false;
        var changes = {};
        for(var name in contextData) {

            var newValue = contextData[name];
            var oldValue = _metricContexts[contextId]['data'][name];

            //Save the changes
            if(newValue != oldValue) {
                hasChanged = true;
                changes[name] = newValue;
            }

            //Change the context
            if(newValue != null && newValue != oldValue) {
                _metricContexts[contextId]['data'][name] = newValue;
            } else if(newValue == null && oldValue != null) {
                delete _metricContexts[contextId]['data'][name];
            }
        }

        //Trigger an event to indicate that the context has changed
        if(hasChanged) {
            _metricContexts[contextId].updateCounter++;
            $(_eventBox).trigger("CONTEXT" + contextId, [_metricContexts[contextId].updateCounter, changes]);
        }


    };


    /*
     --------------------------------
     --- FRAMEWORK INITIALIZATION ---
     --------------------------------
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
        if(_serverUrl.substr(-1) === '/') {
            _serverUrl = _serverUrl.substr(0, _serverUrl.length - 1);
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