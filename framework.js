(function() {

    //Variable where public methods and variables will be stored
    var _self;

    //Path to the SDH-API server with the trailing slash
    var _serverUrl;

    // Array with the information about metrics
    var _metricsInfo;

    // Storage of the metrics data
    var _metricsStorage = {};

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
                        'serie': "Bool [true/false]",
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

        if('function' !== typeof callback){
            error("Method 'observeData' requires a valid callback function.");
            return;
        }

        //TODO: implement method

    };

    /**
     *
     * @param metrics
     * @param callback
     * @param filterId (Optional)
     * @param step (Optional)
     */
    _self.observeSeries = function observeSeries(metrics, callback, filterId, step) {

        if('function' !== typeof callback){
            error("Method 'observeData' requires a valid callback function.");
            return;
        }

        //TODO: implement method

    };

    /**
     *
     * @param filterId
     * @param range
     */
    _self.updateRange = function(filterId, range) {

        //TODO: implement method

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