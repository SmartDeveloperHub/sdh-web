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

// Some polyfills
if (!String.prototype.startsWith) {
    String.prototype.startsWith = function(searchString, position) {
        position = position || 0;
        return this.indexOf(searchString, position) === position;
    };
}

var DashboardController = function DashboardController() {
    this.widgets = [];
    this.cssRequirejsMaps = [];
    this.isLoaderPage = true;
    this.onHistoryChange = false;
    this.historyOwnIndex = 0;
    this.gridEnabled = false;

    window.onpopstate = function(event) {

        if(event.state.dashboard != null) {

            this.onHistoryChange = true;

            this.historyOwnIndex = event.state.index;

            //Set the new environment
            framework.dashboard.changeTo(event.state.dashboard, event.state.env, event.state.categoryInfo);

        }
    }.bind(this);

    $("#activate-grid").click(function() {
        if(this.gridEnabled) {
            this.disableGrid();
        } else {
            this.enableGrid();
        }
    }.bind(this));
};

DashboardController.prototype.authenticationError = function authenticationError() {
    document.location.href = "/auth/logout";
};

DashboardController.prototype.registerWidget = function registerWidget(widget) {
    this.widgets.push(widget);
};

DashboardController.prototype.goToPrevious = function goToPrevious() {

    if(this.historyOwnIndex > 0) {
        history.back();
    }

};

DashboardController.prototype.changeTo = function changeTo(newDashboard, newEnv, categoryInfo, onSuccess) {

    //Loading animation
    startLoading();

    //Clear title and subtitle
    setTitle("");
    setSubtitle("");

    //Clear time info
    clearTimeInfo();

    //Scroll to top
    window.scrollTo(0, 0);

    // Delete all the widgets
    var widget;
    while((widget = this.widgets.pop()) != null) {
        widget.delete();
        widget.dispose();
    }

    // Tell the framework to clear itself (remove observers and context data)
    framework.data.clear();

    // Clear angular scope if defined
    if(typeof angular !== 'undefined') {
        var scope = angular.element(".main-content").scope();
        if(scope != null) {
            scope.$destroy();
        }
    }

    var _this = this; //Closure

    var onLoadError = function(e) {
        alert("Oups! I couldn't get the dashboard '" + newDashboard + "'\nError " + e.status + " (" + e.statusText + ")\n\nReturning to the previous dashboard...");
        _this.goToPrevious();
    };

    // Always add the organization id to the request
    if(newEnv['oid'] == null) {
        newEnv['oid'] = ORGANIZATION_ID;
    }

    //Also add the user id
    if(newEnv['user_id'] == null) {
        newEnv['user_id'] = USER_ID;
    }

    // Create the url for the dashboard
    var encEnv = encodeURIComponent(JSON.stringify(newEnv));
    var dashboardUrl = 'dashboard/' + newDashboard + '/' + encEnv;

    // Specify a category
    if(categoryInfo != null) {
        dashboardUrl += '?' + categoryInfo.category + "=" + categoryInfo.value;
    }


    // Request the dashboard content
    $.get(dashboardUrl, function( data ) {

        //Tell the framework that the new template was retrieved correctly and that the controller is ready to chane the dashboard
        typeof onSuccess === 'function' && onSuccess();

        //Update history
        if(!_this.onHistoryChange) {

            //In the first page we need to overwrite the current history to avoid having it duplicated
            if(_this.isLoaderPage) { //First page

                _this.isLoaderPage = false;
                history.replaceState({
                    dashboard: newDashboard,
                    env: framework.dashboard.getEnv(),
                    categoryInfo: categoryInfo,
                    index: _this.historyOwnIndex
                }, "");

            } else { //Next dashboards

                history.pushState({
                    dashboard: newDashboard,
                    env: framework.dashboard.getEnv(),
                    categoryInfo: categoryInfo,
                    index: ++_this.historyOwnIndex
                }, "");

            }

        } else {
            _this.onHistoryChange  = false;
        }

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

        $(".main-content").remove();
        $(".page-container").append('<div class="main-content">');

        // Load the new HTML
        try{
            $("#template-exec").html(data);

            var options = {
                cell_height: 20,
                height: 0,
                width: 12,
                animate: false,
                float: true,
                vertical_margin: 0
            };

            // Create the grid
            $('.grid-stack').gridstack(options);

            // The grid should start disabled
            _this.disableGrid();

        } catch(e) {
            console.error(e);
            e.status = 0;
            e.statusText = "Could not parse response";
            onLoadError(e);
        }


    }).fail(onLoadError);

};

DashboardController.prototype.enableGrid = function enableGrid() {
    $('.grid-stack').each(function() {
        $(this).data('gridstack').enable();
    });
    this.gridEnabled = true;
    $("#activate-grid").addClass("activated");
    $(".page-container").addClass("grid-edition");
};

DashboardController.prototype.disableGrid = function disableGrid() {
    $('.grid-stack').each(function() {
        $(this).data('gridstack').disable();
    });
    this.gridEnabled = false;
    $("#activate-grid").removeClass("activated");
    $(".page-container").removeClass("grid-edition");
};

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

document.getElementById("loading").className = "";


// Load all the modules needed
require(["jquery", "d3", "nvd3", "moment", "framework", "bootstrap", "headerHandler",
    "widgetCommon", 'gridstack', 'css!/vendor/gridstack/dist/gridstack.min.css'], function() {

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

                // Load the initial dashboard
                framework.dashboard.changeTo(BASE_DASHBOARD);

            });

        } else {
            console.error("BASE_DASHBOARD is not defined.");
        }

    });


});