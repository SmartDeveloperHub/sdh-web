/*
#-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
  This file is part of the Smart Developer Hub Project:
    http://www.smartdeveloperhub.org
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
*/

//Display the loading animation
var startLoading = function startLoading() {
    $("#loading .loading-icon").show();
    $("#loading").show();
};

//Remove loading animation
var finishLoading = function() {
    $("#loading .loading-icon").hide();
    $( "#loading" ).fadeOut(250);
};

var parseUrl = function(url) {
    var parser = document.createElement('a');
    parser.href = decodeURI(url);

    var res = {
        protocol: parser.protocol, // => "http:"
        hostname: parser.hostname, // => "example.com"
        port: parser.port,     // => "3000"
        pathname: parser.pathname, // => "/pathname/"
        search: parser.search,   // => "?search=test"
        hash: parser.hash,     // => "#hash"
        host: parser.host     // => "example.com:3000"
    };

    res.params = {};

    if(res.search.length > 1) {
        var paramstr = res.search.split('?')[1];
        var paramsarr = paramstr.split('&');
        for (var i = 0; i < paramsarr.length; i++) {
            var tmparr = paramsarr[i].split('=');
            res.params[decodeURIComponent(tmparr[0])] = decodeURIComponent(tmparr[1]);
        }
    }

    return res;
};

document.getElementById("loading").className = "";


// Load all the modules needed
require(["jquery", "d3", "nvd3", "moment", "sdh-framework", "bootstrap", "headerHandler",
    'gridstack', 'css!/vendor/gridstack/dist/gridstack.min.css', 'dashboard-controller'], function() {

    framework.ready(function() {
        console.log("Framework ready");

        var dashboardController = new DashboardController();

        //Set an error handler for require js
        requirejs.onError = function (err) {
            console.error(err);
            alert("Oups! There were some problems trying to download all the dependencies of the dashboard." +
                " If problems persist, check your Internet connection. \n\nReturning to the previous dashboard...");
            this.goToPrevious();
            //throw err; //Should I throw it?
        }.bind(dashboardController);

        //Set a load handler to add the load maps to the dashboard controller
        requirejs.onResourceLoad = function(context, map)
        {
            dashboardController.cssRequirejsMaps.push(map);
        };

        //Tell the framework this is the Dashboard Controller
        framework.dashboard.setDashboardController(dashboardController);

        //Show header
        $('body').removeClass('hidd');

        if(BASE_DASHBOARD != null) {

            $(document).ready(function() {

                //Show the page container
                $(".page-container").show();
                $("footer.footer-container").show();

                var url = parseUrl(document.location);

                if(url.params.dashboard != null && url.params.env != null) {

                    framework.dashboard.changeTo(url.params.dashboard, JSON.parse(url.params.env));

                } else {

                    // Load the initial dashboard
                    framework.dashboard.changeTo(BASE_DASHBOARD);

                }

            });

        } else {
            console.error("BASE_DASHBOARD is not defined.");
        }

    });


});