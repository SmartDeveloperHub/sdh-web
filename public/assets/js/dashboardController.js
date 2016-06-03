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

var getDashboardGridConfigId = function(name, userId) {
    return [name, userId].join(":");
};

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

    //Also add the logged user id
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

        $('.grid-stack').each(function() {
            $(this).data('gridstack').destroy();
        });

        $(".main-content").remove();
        $(".page-container").append('<div class="main-content">');

        //Remove tooltips that are generated outside the page container
        $(".qtip").remove();
        $(".nvtooltip").remove();

        // Load the new HTML
        try{
            $("#template-exec").html(data);

            var dashboardUID = getDashboardGridConfigId(newDashboard, USER_ID);

            var options = {
                cellHeight: 20,
                height: 0,
                width: 12,
                animate: false,
                float: true,
                verticalMargin: 0
            };

            // Create the grid
            var gridEl = $('.grid-stack');
            gridEl.gridstack(options);

            // Load the configuration of the grid if there is data in the local storage and remove it if outdated
            var gridConfigData = JSON.parse(localStorage.getItem(dashboardUID));
            if(gridConfigData && gridConfigData.hash !== DASHBOARD_HTML_HASH) {
                localStorage.removeItem(dashboardUID);
                gridConfigData = null;
            }

            // Generate ids for each cell and apply the configuration of the grid
            var gridCells = $('.grid-stack .grid-stack-item:visible');
            gridCells.each(function (index, el) {
                el = $(el);
                var node = el.data('_gridstack_node');
                var stack = el.parent(".grid-stack");
                var grid = stack.data('gridstack');
                var stackIndex = stack.index(); // There can be multiple stacks in the same dashboard
                var id = [stackIndex, node.x, node.y, node.width, node.height].join(":");
                el.data('cell-id', id);

                if(gridConfigData) {
                    var widgetConf = gridConfigData.config[id];
                    if(widgetConf) {
                        grid.update(el, widgetConf.x, widgetConf.y, widgetConf.width, widgetConf.height);
                    }
                }

            });

            // Update the grid configuration if the change event is triggered
            gridEl.on('change', function(event, items) {

                var gridConfigData = JSON.parse(localStorage.getItem(dashboardUID));
                var config = (gridConfigData ? gridConfigData.config : {});
                for(var i = 0; i < items.length; i++) {
                    var item = items[i];
                    var id = item.el.data('cell-id');
                    if(id) {
                        config[id] = {
                            x: item.x,
                            y: item.y,
                            width: item.width,
                            height: item.height
                        };
                    }

                }

                localStorage.setItem(dashboardUID, JSON.stringify({
                    hash: DASHBOARD_HTML_HASH,
                    config: config
                }));

            });

            // The grid should start disabled
            _this.disableGrid();

            var objectId, objectType;
            if(newEnv['uid']) {
                objectId = newEnv['uid'];
                objectType = 'member';
            } else if(newEnv['rid']) {
                objectId = newEnv['rid'];
                objectType = 'repository';
            } else if(newEnv['pjid']) {
                objectId = newEnv['pjid'];
                objectType = 'project';
            } else if(newEnv['prid']) {
                objectId = newEnv['prid'];
                objectType = 'product';
            } else if(newEnv['oid']) {
                objectId = newEnv['oid'];
                objectType = 'organization';
            }

            //Google analytics
            if(typeof ga === 'function') {
                ga('send', 'pageview', {
                    'dimension1':  USER_ID,
                    'dimension2':  objectId,
                    'dimension3': objectType
                });
            }


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