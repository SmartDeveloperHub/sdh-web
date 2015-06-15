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
    },
    shim : {
        bootstrap : {
            exports: "jQuery.fn.popover",
            deps : ['jquery']
        },
        framework: {
            exports: "framework",
            deps :['jquery']
        },
        d3: {
            exports: 'd3',
            deps: ['jquery']
        },
        joinable: {
            deps: ['jquery']
        },
        nvd3: {
            exports: 'nv',
            deps: ['d3']
        },
        headerHandler: {
            deps: ['jquery']
        }
    }
});

var DashboardController = function DashboardController() {
    this.widgets = [];
    this.previousDashboard = null;
    this.currentDashboard = null;
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
    }

    // Just to be sure in case some widget did not registered with the framework
    framework.data.stopAllObserves();

    //Now load the new dashboard
    showLoadingMessage("Downloading dashboard template...");

    var _this = this; //Closure

    $.get(newDashboard, function( data ) {

        //Tell the framework that the new template was retrieved correctly and that the controller is ready to chane the dashboard
        typeof onSuccess === 'function' && onSuccess();

        //Update previous and current dashboard
        _this.previousDashboard = _this.currentDashboard;
        _this.currentDashboard = newDashboard;

        $("#template-exec").html(data);

    }).fail(function(e) {
        alert("Oups! I couldn't get the dashboard '" + newDashboard + "'\nError " + e.status + " (" + e.statusText + ")\n\nReturning to the previous dashboard...");
        _this.changeTo((_this.previousDashboard != null ? _this.previousDashboard : BASE_DASHBOARD));
    });

};

var showLoadingMessage = function showLoadingMessage(mes) {

    //Change loading info
    $("#loading .loading-info span").text(mes);

    //Display the loading animation
    $("#loading").show();
};

var finishLoading = function() {
    //Remove loading
    $( "#loading" ).fadeOut(750, function() {
        $(this).find(".loading-info span").text("");
    });

};


define(function(require, exports, module) {

    document.getElementById("loading").className = "";
    document.getElementById("loading").getElementsByTagName("span")[0].textContent = "Initializing SDH Framework...";

    require(["jquery", "d3", "nvd3", "moment", "framework", "bootstrap", "joinable", "headerHandler"], function($, d3, nv, moment) {

        framework.ready(function() {
            console.log("Framework ready");

            var dashboardController = new DashboardController();

            framework.dashboard.setDashboardController(dashboardController);

            if(BASE_DASHBOARD != null) {
                dashboardController.changeTo(BASE_DASHBOARD);
            } else {
                console.error("BASE_DASHBOARD is not defined.");
            }

        });


    });
});


