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
    paths: {
        'css':		'assets/js/requirejs/css.min', // Alias for CSS plugin
        'bootstrap': "assets/js/bootstrap/bootstrap.min",
        'jquery': 'sdh-framework/lib/jquery/jquery-2.1.3.min',
        'd3': "sdh-framework/lib/d3/d3.min",
        'nvd3': "sdh-framework/lib/nvd3/nv.d3.min",
        'joinable': "sdh-framework/lib/joinable/joinable",
        'moment': "sdh-framework/lib/moment/moment",
        'framework': "sdh-framework/framework"
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
        }
    }
});

define(function(require, exports, module) {

    document.getElementById("loading").className = "";
    document.getElementById("loading").getElementsByTagName("span")[0].textContent = "Initializing SDH Framework...";

    require(["jquery", "framework", "d3", "nvd3", "moment", "bootstrap", "joinable"], function($, framework,d3, nv, moment) {

        framework.ready(function() {
            console.log("Framework ready");

            if(BASE_DASHBOARD != null) {
                loadTemplate(BASE_DASHBOARD);
            } else {
                console.error("BASE_DASHBOARD is not defined.");
            }

        });


    });
});


var loadTemplate = function loadTemplate(path) {

    //Change loading info
    $("#loading .loading-info span").text("Downloading template...");

    //Display the loading animation
    $("#loading").show();

    framework.metrics.stopAllObserves();

    $.get(path, function( data ) {

        $("#template-exec").html(data);

    });


};

var finishLoading = function() {
    //Remove loading
    $( "#loading" ).fadeOut(750, function() {
        $(this).find(".loading-info span").text("");
    });

};


