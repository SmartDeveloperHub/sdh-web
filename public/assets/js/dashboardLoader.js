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

require.config({
    baseUrl: "/",
    //enforceDefine: true,
    map: {
        '*': {
            'css': 'assets/js/requirejs/css.min' // or whatever the path to require-css is
        }
    },
    paths: {
        'bootstrap': "assets/js/bootstrap/bootstrap.min",
        'jquery': 'sdh-framework/lib/jquery/jquery-2.1.3.min',
        'd3': "sdh-framework/lib/d3/d3.min",
        'nvd3': "sdh-framework/lib/nvd3/nv.d3.min",
        'joinable': "sdh-framework/lib/joinable/joinable",
        'moment': "sdh-framework/lib/moment/moment",
        'framework': "sdh-framework/framework",
        'headerHandler': "assets/js/header/headerHandler",
        'datatables' : 'sdh-framework/lib/jquery/datatables/js/jquery.dataTables',
        'widgetCommon': 'sdh-framework/framework.widget.common'
    },
    shim : {
        'bootstrap' : {
            exports: "jQuery.fn.popover",
            deps : ['jquery']
        },
        'framework': {
            deps :['jquery']
        },
        'd3': {
            exports: 'd3',
            deps: ['jquery']
        },
        'joinable': {
            deps: ['jquery']
        },
        'nvd3': {
            exports: 'nv',
            deps: ['d3']
        },
        'headerHandler': {
            deps: ['jquery']
        },
        'widgetCommon': {
            deps: ['framework']
        }
    }
});

var DashboardController = function DashboardController() {
    this.widgets = [];
    this.previousDashboard = null;
    this.currentDashboard = null;
    this.cssRequirejsMaps = [];
};

DashboardController.prototype.registerWidget = function registerWidget(widget) {
    this.widgets.push(widget);
};

DashboardController.prototype.changeTo = function changeTo(newDashboard, onSuccess) {

    showLoadingMessage("Disposing the previous dashboard...");

    // Delete all the widgets
    var widget;
    while((widget = this.widgets.pop()) != null) {
        widget.delete();
        widget.dispose();
    }

    // Just to be sure in case some widget did not registered with the framework
    framework.data.stopAllObserves();

    //Now load the new dashboard
    showLoadingMessage("Downloading dashboard template...");

    var _this = this; //Closure

    var onLoadError = function(e) {
        alert("Oups! I couldn't get the dashboard '" + newDashboard + "'\nError " + e.status + " (" + e.statusText + ")\n\nReturning to the previous dashboard...");
        if(_this.previousDashboard != null) {
            _this.changeTo(_this.previousDashboard);
        }
    };

    $.get(newDashboard, function( data ) {

        //Tell the framework that the new template was retrieved correctly and that the controller is ready to chane the dashboard
        typeof onSuccess === 'function' && onSuccess();

        //Update previous and current dashboard
        _this.previousDashboard = _this.currentDashboard;
        _this.currentDashboard = newDashboard;

        // Remove previous css dependencies
        var deps = (typeof _REQUIREJS_DASHBOARD_DEPENDENCIES === 'undefined' ? [] : _REQUIREJS_DASHBOARD_DEPENDENCIES);
        for(var i = deps.length - 1; i >= 0; i-- ) {
            var dependency = deps[i];

            if(dependency.startsWith("css!")) {

                //Search it in the maps
                var map = null;
                for(var m = _this.cssRequirejsMaps.length - 1; m >= 0; m--) {
                    if(_this.cssRequirejsMaps[m]['originalName'] == dependency) {
                        map = _this.cssRequirejsMaps[m];
                        break;
                    }
                }

                // It should be found
                if(map != null) {

                    //Remove the css link from the head
                    var cssurl = require.toUrl(dependency.slice(4));
                    if(!cssurl.startsWith("http")) { //If not an url, add the trailing .css
                        cssurl += ".css";
                    }
                    $("link[href='" + cssurl + "']").remove();

                    //Undefine it from requirejs to be loaded next time is required
                    requirejs.undef(map.prefix + '!' + map.name);
                }

            }
        }

        //Clear the cssMaps
        _this.cssRequirejsMaps = [];

        try{
            $("#template-exec").html(data);
        } catch(e) {
            console.err(e);
            e.status = 0;
            e.statusText = "Could not parse response";
            onLoadError(e);
        }


    }).fail(onLoadError);

};

var showLoadingMessage = function showLoadingMessage(mes) {

    //Change loading info
    $("#loading .loading-info span").text(mes);

    //Display the loading animation
    $("#loading").show();
};

var finishLoading = function() {
    //Remove loading
    $( "#loading" ).fadeOut(250, function() {
        $(this).find(".loading-info span").text("");
    });

};


define(function(require, exports, module) {

    document.getElementById("loading").className = "";
    document.getElementById("loading").getElementsByTagName("span")[0].textContent = "Initializing SDH Framework...";

    require(["jquery", "d3", "nvd3", "moment", "framework", "bootstrap", "joinable", "headerHandler", "widgetCommon"], function() {

        framework.ready(function() {
            console.log("Framework ready");

            var dashboardController = new DashboardController();

            //Set an error handler for require js
            requirejs.onError = function (err) {
                console.err(err);
                alert("Oups! There were some problems trying to download all the dependencies of the dashboard." +
                " If problems persist, check your Internet connection. \n\nReturning to the previous dashboard...");
                if(this.previousDashboard != null) {
                    this.changeTo(this.previousDashboard);
                }
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
                dashboardController.changeTo(BASE_DASHBOARD);
            } else {
                console.error("BASE_DASHBOARD is not defined.");
            }

        });


    });
});


