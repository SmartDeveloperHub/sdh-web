(function() {

    //Variable where public methods and variables will be stored
    var _self;

    //Path to the SDH-API server with the trailing slash
    var _serverUrl;

    // Array with the information about metrics
    var _metricsInfo;

    // Storage of the metrics data
    var _metricsStorage = {};

    var _metricFilters = {};

    // This is a variable to make the events invisible outside the framework
    var _eventHandler = {};

    var FRAMEWORK_NAME = "frameworkname";



    /*
     FRAMEWORK PRIVATE METHODS
     */

    var error = function error(message) {
        console.error("[" + FRAMEWORK_NAME +  "] " + message);
    };


    var requestJSON = function requestJSON(path, queryParams, callback) {

        $.getJSON( _serverUrl + path, queryParams, callback);

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
            requestJSON(path, queryParams, callback)

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
        var allData = [];

        var onMetricReady = function(data) {
            allData.push(data); //TODO: handle errors
            //TODO: data format?
            if(++completedRequests === metrics.length) {
                callback(allData);
            }
        };

        for(var i in metrics) {
            makeMetricRequest(metricId, params, queryParams, onMetricReady)
        }

    };

    /**
     * Converts the array of metrics containing a mixture of strings (for simple metrics) and objects (for complex metrics)
     * into an array of objects with at least an id.
     * @param metrics Array of metrics containing a mixture of strings (for simple metrics) and objects (for complex metrics)
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
     * Combines an incomplete metric with a filter in order to create a complete metric to make a request with.
     * @param metrics
     * @param filter Filter data
     */
    var combineMetricsWithFilter = function combineMetricsWithFilter(metrics, filter) {

        var newMetrics = [];

        //Series parameter can not be set by a filter. Neither range.step parameter.
        if(filter.series != null || (filter.range != null && filter.range.step != null)) {
            filter = clone(filter);

            if(filter.series != null){
                delete filter.series;
            }

            if(filter.range != null && filter.range.step != null) {
                delete filter.range.step;
            }

        }

        for(var i in metrics) {
            newMetrics.push(mergeObjects(clone(metrics[i]), filter));
        }
    };

    /**
     *
     * @param metrics
     * @param callback
     * @param filterId
     * @param series
     * @param step
     */
    var observeMetrics = function observeMetrics(metrics, callback, filterId, series, step) {

        if('function' !== typeof callback){
            error("Method 'observeData' requires a valid callback function.");
            return;
        }

        if(!Array.isArray(metrics) || metrics.length === 0 ) {
            error("Method 'observeData' has received an invalid metrics param.");
            return;
        }

        if('string' !== typeof filterId || _metricFilters[filterId] == null) {
            filterId = null;
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

        //If filter is defined, combine the metrics with the filter in order to create more complete metrics that could
        // be requested.
        if(filterId != null) {

            //Combine the metrics with the filter in order to create more complete metrics that could be requested.
            var metricsWithFilter = combineMetricsWithFilter(metrics, _metricFilters[filterId].data);

            //Request all the metrics if possible
            if(allMetricsCanBeRequested(metricsWithFilter)) {
                multipleMetricsRequest(metricsWithFilter, callback);
            }

            // Create the FILTER event listener
            $(_eventHandler).on("FILTER" + filterId, function(event, filterCounter) {

                //If it is not the last filter event launched, ignore the data because there is another more recent
                // event being executed
                if(filterCounter != _metricFilters[filterId].updateCounter){
                    return;
                }

                //Update the metrics with the filter data
                var metricsWithFilter = combineMetricsWithFilter(metrics, _metricFilters[filterId].data);

                if(allMetricsCanBeRequested(metricsWithFilter)) {
                    multipleMetricsRequest(metricsWithFilter, callback);
                }
            });

        } else { //No filter is set

            //Request all the metrics
            if(allMetricsCanBeRequested(metricsWithFilter)) {
                multipleMetricsRequest(metricsWithFilter, callback);
            } else {
                error("Some of the metrics have not information enough for an 'observeData' without filter");
            }
        }

    };




    /*
     FRAMEWORK PUBLIC METHODS
     */

    /**
     *
     * @param metrics
     * @param callback
     * @param filterId (Optional)
     */
    _self.observeData = function observeData(metrics, callback, filterId) {
        observeMetrics(metrics, callback, filterId, false);
    };

    /**
     *
     * @param metrics
     * @param callback
     * @param filterId (Optional)
     * @param step (Optional)
     */
    _self.observeSeries = function observeSeries(metrics, callback, filterId, step) {
        observeMetrics(metrics, callback, filterId, true, step);
    };

    /**
     *
     * @param filterId
     * @param range
     */
    _self.updateFilter = function(filterId, filterData) {

        if('string' !== typeof filterId) {
            error("Method 'updateRange' requires a string for filterId param.");
            return;
        }

        if(_metricFilters[filterId] == null) {
            _metricFilters[filterId] = { updateCounter: 0, data: [] };
        }

        //Update values of the filter (if null, remove it)
        var hasChanged = false;
        for(var name in filterData) {

            var newValue = filterData[name];
            var oldValue = _metricFilters[filterId].data[name];

            if(newValue != null && newValue != oldValue) {
                _metricFilters[filterId][name] = newValue;
                hasChanged = true;
            } else if(oldValue != null) {
                delete _metricFilters[filterId][name];
            }
        }

        //Trigger an event to indicate that the filter has changed
        if(hasChanged) {
            _metricFilters[filterId].updateCounter++;
            $(_eventHandler).trigger("FILTER" + filterId, [_metricFilters[filterId].updateCounter]);
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
        if('undefined' !== typeof SDH-API-URL){
            error("SDH-API-URL global variable must be set with the url to the SDH-API server.");
            return false;
        }

        _serverUrl = SDH-API-URL.trim();

        if(_serverUrl.length === 0) {
            error("SDH-API-URL global variable must be set with a valid url to the SDH-API server.");
            return false;
        }
        if(_serverUrl.substr(-1) !== '/') {
            _serverUrl += '/';
        }

        /* CHECK JQUERY */
        if (typeof jQuery == 'undefined') {
            error("SDH-API-URL global variable must be set with a valid url to the SDH-API server.");
            return false;
        }

        return true;
    };

    var frameworkInit = function frameworkInit() {

        if(frameworkPreCheck()) {

            loadMetricsInfo();

            window.frameworkname = _self;
        }

    };

    frameworkInit();

})();