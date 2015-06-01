/*
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      This file is part of the Smart Developer Hub Project:
        http://www.smartdeveloperhub.org/
      Center for Open Middleware
            http://www.centeropenmiddleware.com/
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      Copyright (C) 2015 Center for Open Middleware.
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      Licensed under the Apache License, Version 2.0 (the "License");
      you may not use this file except in compliance with the License.
      You may obtain a copy of the License at
                http://www.apache.org/licenses/LICENSE-2.0
      Unless required by applicable law or agreed to in writing, software
      distributed under the License is distributed on an "AS IS" BASIS,
      WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
      See the License for the specific language governing permissions and
      limitations under the License.
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      contributors: Alejandro Vera (alejandro.vera@centeropenmiddleware.com ),
                    Carlos Blanco. (carlos.blanco@centeropenmiddleware.com)
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
*/

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

    // List of all the parameters that can be used with the API.
    // It is only for performance purposes while checking input.
    var _existentParametersList = [];

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

    /**
     * Prints a framework warning
     * @param message Message to display.
     */
    var warn = function warn(message) {
        console.warn("[" + FRAMEWORK_NAME +  "] " + message);
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

                                //Add all parameters and avoid 'mid'
                                for(var i = 0, len_i = parameters.length; i < len_i; i++) {

                                    //Add the parameter in params or queryParams
                                    if (parameters[i]['in'] === 'path' && parameters[i]['name'] !== 'mid') {
                                        _metricsInfo[metricId].params.push(parameters[i]['name']);
                                    } else if(parameters[i]['in'] === 'query') {
                                        _metricsInfo[metricId].queryParams.push(parameters[i]['name']);
                                    }

                                    //Add it to the list of possible parameters (cache)
                                    if(_existentParametersList.indexOf(parameters[i]['name']) === -1) {
                                        _existentParametersList.push(parameters[i]['name']);
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
        var requests = [];

        var onMetricReady = function(metricId, params, queryParams, data) {

            if(allData[metricId] == null) {
                allData[metricId] = [];
            }

            //Add the request info to the data received from the api
            data['request'] = {
                params: params,
                queryParams: queryParams
            };
            allData[metricId].push(data);

            if(++completedRequests === requests.length) {
                sendDataEventToCallback(allData, callback);
            }
        };

        //Send a loading data event to the listener
        sendLoadingEventToCallback(callback);

        for(var i in metrics) {

            var metricId = metrics[i].id;
            var params = {};
            var queryParams = {};
            var multiparams = [];
            var multiQueryParams = [];

            //Fill the params and queryparams
            for(var name in metrics[i]) {

                if(_metricsInfo[metricId]['queryParams'].indexOf(name) !== -1) { //Is a queryparam

                    //Check if is multi parameter and add it to the list of multi parameters
                    if(metrics[i][name] instanceof Array) {
                        multiQueryParams.push(name);
                    }

                    queryParams[name] =  metrics[i][name];

                } else if(_metricsInfo[metricId]['params'].indexOf(name) !== -1) { //Is a param

                    //Check if is multi parameter and add it to the list of multi parameters
                    if(metrics[i][name] instanceof Array) {
                        multiparams.push(name);
                    }

                    params[name] =  metrics[i][name];

                }
            }

            var requestsCombinations = generateMetricRequestParamsCombinations(metricId, params, queryParams, multiparams, multiQueryParams);
            requests = requests.concat(requestsCombinations);

        }

        for(var i in requests) {
            var metricId = requests[i]['metricId'];
            var params = requests[i]['params'];
            var queryParams = requests[i]['queryParams'];

            makeMetricRequest(metricId, params, queryParams, onMetricReady.bind(undefined, metricId, params, queryParams));
        }

    };

    /**
     * Generates an array of requests combining all the values of the multi parameters (param and queryParam).
     * @param metricId
     * @param params Hash map of param name and values.
     * @param queryParams  Hash map of queryParam name and values.
     * @param multiparam List of parameter names that have multiple values.
     * @param multiQueryParams List of queryParameter names that have multiple values.
     * @returns {Array} Array of requests to execute for one metric
     */
    var generateMetricRequestParamsCombinations = function (metricId, params, queryParams, multiparam, multiQueryParams) {

        var paramsCombinations = generateParamsCombinations(params, multiparam);
        var queryParamsCombinations = generateParamsCombinations(queryParams, multiQueryParams);
        var allCombinations = [];

        //Create the combinations of params and queryParams
        for(var i = 0, len_i = paramsCombinations.length; i < len_i; ++i) {
            for(var j = 0, len_j = queryParamsCombinations.length; j < len_j; ++j) {
                allCombinations.push({
                    metricId: metricId,
                    params: paramsCombinations[i],
                    queryParams: queryParamsCombinations[j]
                });
            }
        }

        return allCombinations;

    };

    /**
     * Generates all the combinations of multi parameters.
     * @param params Hash map of param name and values.
     * @param multiParams List of parameter names that have multiple values.
     * @returns {Array} Array of parameter combinations.
     */
    var generateParamsCombinations = function generateParamsCombinations(params, multiParams) {

        //Clone params before modifying them
        params = clone(params);

        if(multiParams.length > 0) {

            var result = [];

            //Clone function params before modifying them
            multiParams = clone(multiParams);

            //Remove the parameter from the list of multi parameters
            var param = multiParams.pop();

            //Save the values of the parameter because it will be modified
            var values = params[param];

            //For each value generate the possible combinations
            for(var i in values) {

                var value = values[i];

                //Overwrite array with only one value
                params[param] = value;

                //Generate the combinations for that value
                var combinations = generateParamsCombinations(params, multiParams);

                result = result.concat(combinations);

            }

            return result;

        } else { //End of recursion
            return [ params ];
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
            } else if('object' === typeof metrics[i] && metrics[i]['id']) { //Metrics objects must have an id
                newMetricsParam.push(metrics[i]);
            } else {
                warn("One of the metrics given was not string nor object so it has been ignored.");
            }
        }

        //Remove invalid metrics and parameters
        newMetricsParam = cleanMetrics(newMetricsParam);

        return newMetricsParam;

    };

    /**
     * Cleans an array of metric objects removing the non existent ones and the invalid parameters of them.
     * @param metrics Array of metric objects to clean.
     */
    var cleanMetrics = function cleanMetrics(metrics) {

        var newMetrics = [];

        for(var i = 0; i < metrics.length; ++i) {
            var metric = metrics[i];
            var metricId = metric['id'];
            var metricInfo = _metricsInfo[metricId];

            if(metricInfo == null) {
                warn("Metric '"+metricId+"' does not exist.");
            } else { //Check its parameters
                var cleanParameters = {};
                for(var paramName in metric) {
                    if(paramName != 'id' && paramName != 'static' && metricInfo.params.indexOf(paramName) === -1 && metricInfo.queryParams.indexOf(paramName) === -1) {
                        warn("Parameter '"+paramName+"' is not a valid parameter for metric '"+metricId+"'.");
                    } else {
                        cleanParameters[paramName] = metric[paramName];
                    }
                }

                if(Object.keys(cleanParameters).length > 0) {
                    newMetrics.push(cleanParameters);
                }
            }
        }

        return newMetrics;
    };

    /** Clone the object
     * @obj1 Object to clone.
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


    /**
     * Deep merge in obj1 object. (Priority obj2)
     * @param obj1
     * @param obj2
     * @param mergeArrays If true, combines arrays. Oherwirse, if two arrays must be merged,
     * the obj2's array overwrites the other. Default: true.
     * @returns {*}
     */
    var mergeObjects = function mergeObjects(obj1, obj2, mergeArrays) {

        mergeArrays = mergeArrays || true;

        if (Array.isArray(obj2) && Array.isArray(obj1) && mergeArrays) {
            // Merge Arrays
            var i;
            for (i = 0; i < obj2.length; i++) {
                if (typeof obj2[i] === 'object' && typeof obj1[i] === 'object') {
                    obj1[i] = mergeObjects(obj1[i], obj2[i], mergeArrays);
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
                        obj1[p] = mergeObjects(obj1[p], obj2[p], mergeArrays);
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
     * @param contexts Context ids
     */
    var combineMetricsWithContext = function combineMetricsWithContext(metrics, contexts) {

        var newMetrics = [];
        var contextsData = [];

        //Fill the array with data for each context
        for(var i in contexts) {
            contextsData.push(_metricContexts[contexts[i]]['data']);
        }

        //Iterate through the metrics and combine them with the contexts
        for(var i in metrics) {

            //Clone the metric object to avoid modification
            var metric = clone(metrics[i]);

            //Modify the metric with all the contexts
            for(var c in contextsData) {

                //Clean the context
                var mergeContext = getCleanContextByMetric(contextsData[c], metric);

                metric = mergeObjects(metric, mergeContext, false);
            }

            //Add the metric to the returned array
            newMetrics.push(metric);
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
     * @param o Object to check.
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
     * @param metrics Array with metrics. Each metric can be an String or an Object. The object must have the following
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
     *                                    //Static parameters must have a value; otherwise, an error will be returned.
     *               }
     * @param callback Callback that receives an object containing at least an "event" that can be "data" or "loading".
     *  - loading means that the framework is retrieving new data for the observer.
     *  - data means that the new data is ready and can be accessed through the "data" element of the object returned to
     *  the callback. The "data" element of the object is a hashmap using as key the metricId of the requested metrics
     *  and as value an array with data for each of the request done for that metricId.
     * @param contextIds Array of context ids.
     */
    _self.metrics.observe = function observe(metrics, callback, contextIds) {

        if('function' !== typeof callback){
            error("Method 'observeData' requires a valid callback function.");
            return;
        }

        if(!Array.isArray(metrics) || metrics.length === 0 ) {
            error("Method 'observeData' has received an invalid metrics parameter.");
            return;
        }

        if(contextIds != null && !(contextIds instanceof Array) ) {
            error("Method 'observeData' expects contextIds parameter to be null or an array.");
            return;
        }

        //Normalize the array of metrics
        metrics = normalizeMetrics(metrics);

        if(metrics.length === 0) {
            warn("No metrics to observe.");
            return;
        }

        //Check that static parameters have their value defined in the metric
        for(var i = 0; i < metrics.length; ++i) {
            if(metrics[i]['static'] != null && metrics[i]['static'].length > 0) {
                for(var s = 0; s < metrics[i]['static'].length; ++s) {
                    var staticParam = metrics[i]['static'][s];
                    if(metrics[i][staticParam] == null) {
                        error("Static parameter '"+staticParam+"' must have its value defined.");
                        return;
                    }
                }
            }

        }

        //Is an Array, verify that it only contains strings
        if(contextIds instanceof Array) {

            //We will use it internally, so we need to clone it to prevent the user changing it
            contextIds = clone(contextIds);

            //If one of the contexts is not an string, remove it from the array
            for(var i = 0; i < contextIds.length; ++i) {
                if(typeof contextIds[i] != 'string') {
                    contextIds.splice(i,  1);
                }
            }
        } else { //Invalid parameter type (or null)
            contextIds = []
        }

        //Initialize contexts it they are not initialized
        for(var i = 0; i < contextIds.length; ++i) {
            if (_metricContexts[contextIds[i]] == null) {
                initializeContext(contextIds[i]);
            }
        }

        //If contexts are defined, combine the metrics with the context in order to create more complete metrics that could
        // be requested.
        if(contextIds.length > 0) {

            //Combine the metrics with the context in order to create more complete metrics that could be requested.
            var metricsWithContext = combineMetricsWithContext(metrics, contextIds);

            //Request all the metrics if possible
            if(allMetricsCanBeRequested(metricsWithContext)) {
                multipleMetricsRequest(metricsWithContext, callback);
            }

            //Create the CONTEXT event handler
            var contextEventHandler = function(event, contextCounter, contextChanges, contextId) {

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
                var metricsWithContext = combineMetricsWithContext(metrics, contextIds);

                if(allMetricsCanBeRequested(metricsWithContext)) {
                    multipleMetricsRequest(metricsWithContext, callback);
                }
            };

            //Link user callbacks with event handlers
            _event_handlers.push({
                userCallback: callback,
                contexts: contextIds,
                contextHandler: contextEventHandler
            });

            // Create the CONTEXT event listener for each of the contexts
            for(var c in contextIds) {
                $(_eventBox).on("CONTEXT" + contextIds[c], contextEventHandler);
            }

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
                for (var c in _event_handlers[i]['contexts']) {
                    $(_eventBox).off("CONTEXT" + _event_handlers[i]['contexts'][c], _event_handlers[i]['contextHandler']);
                    _event_handlers.splice(i, 1);
                    break;
                }
            }
        }
    };

    /**
     * Cancels observing for everything.
     */
    _self.metrics.stopAllObserves = function stopAllObserves() {

        //Remove all the event handlers
        for (var i in _event_handlers) {
            for (var c in _event_handlers[i]['contexts']) {
                $(_eventBox).off("CONTEXT" + _event_handlers[i]['contexts'][c], _event_handlers[i]['contextHandler']);
            }
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

            //Check if that parameter exists. If not, ignore it
            if(_existentParametersList.indexOf(name) === -1) {
                warn("Parameter '" + name + "' given in updateContext does not exist.");
                continue;
            }

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
            $(_eventBox).trigger("CONTEXT" + contextId, [_metricContexts[contextId].updateCounter, changes, contextId]);
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
            error("SDH Framework requires JQuery to work properly.");
            return false;
        }

        return true;
    };

    /**
     * Add a callback that will be executed when the framework is ready
     * @param callback
     */
    var frameworkReady = function frameworkReady(callback) {
        if(!_isReady && typeof callback === 'function') {
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