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

    /**
     *
     * @param configuration
     * @returns {*}
     */
    var normalizeConfig = function normalizeConfig(configuration) {
        if (configuration == null) {
            configuration = {};
        }

        var defaultConfig = {
            columns: {
                type: 'object',
                default: []
            },
            height: {
                type: 'number',
                default: 240
            },
            filterControl: {
                type: 'boolean',
                default: false
            },
            paginationControl: {
                type: 'boolean',
                default: false
            },
            lengthControl: {
                type: 'boolean',
                default: false
            },
            tableInfo: {
                type: 'boolean',
                default: false
            },
            selectable: {
                type: 'boolean',
                default: true
            },
            maxRowsSelected: {
                type: 'number',
                default: 1
            },
            updateContexts: {
                type: 'object',
                default: null
            }

        };

        for(var confName in defaultConfig) {
            var conf = defaultConfig[confName];
            if (typeof configuration[confName] != conf['type']) {
                configuration[confName] = conf['default'];
            }
        }

        return configuration;
    };

    /* Table constructor
     *   element: the DOM element that will contain the rangeNv
     *   data: the data id array
     *   contextId: optional.
     *   configuration: additional table configuration
     */
    var Table = function Table(element, metrics, contextId, configuration) {

        if(!framework.isReady()) {
            console.error("Table object could not be created because framework is not loaded.");
            return;
        }

        this.element = $(element); //Store as jquery object
        this.data = null;
        this.chart = null;

        // Extending widget
        framework.widgets.CommonWidget.call(this, false, this.element.get(0));

        // Configuration
        this.configuration = normalizeConfig(configuration);

        this.element.append('<table class="blurable table table-striped table-bordered"><thead><tr></tr></thead><tbody></tbody></table>');
        this.tableDom = this.element.children("table");
        this.tableDom.get(0).style.minHeight = configuration.height;

        this.observeCallback = function(event){

            if(event.event === 'loading') {
                this.startLoading();
            } else if(event.event === 'data') {
                this.endLoading(this.updateData.bind(this, event.data));
            }

        }.bind(this);

        framework.data.observe(metrics, this.observeCallback , contextId);

    };

    Table.prototype = new framework.widgets.CommonWidget(true);

    Table.prototype.updateData = function(framework_data) {

        var normalizedData = getNormalizedData.call(this,framework_data);

        var head = this.tableDom.find("thead tr");
        head.empty();

        for(var i in this.configuration.columns) {
            head.append("<th>" + this.configuration.columns[i]['label'] + "</th>")
        }

        //Create dom configuration string
        var dom = "t";
        if(this.configuration.filterControl) dom += 'f';
        if(this.configuration.lengthControl) dom += 'l';
        if(this.configuration.tableInfo) dom += 'i';
        if(this.configuration.paginationControl) dom += 'p';

        // Get columns
        var columns = [];
        for(var i in this.configuration.columns) {
            columns.push({data: this.configuration.columns[i]['property']});
        }

        //DataTable object
        this.table = this.tableDom.DataTable({
            data: normalizedData,
            dom: dom,
            columns: columns
        });

        //The rows can be selected
        if(this.configuration.selectable) {

            this.tableDom.on( 'click', 'tbody tr', this, function (e) {

                var widget = e.data;

                if ( $(this).hasClass('selected') ) { //It is already selected
                    $(this).removeClass('selected');

                } else { //Not selected

                    //Select it if the maximum of selected rows has not been achieved
                    if(widget.configuration.maxRowsSelected > widget.table.$('tr.selected').length) {
                        console.log(widget.table.row($(this)).data());
                        $(this).addClass('selected');

                    }

                }

                // Update contexts
                var contexts = widget.configuration.updateContexts;
                if(contexts != null) {

                    for(var i in contexts) {
                        var context = contexts[i];

                        if(context['id'] != null) {

                            // Data to send through the context
                            var data = {};

                            // Filter the data to send through the context update
                            if(context['filter'] != null) {
                                for(var f = 0; f < context['filter'].length; ++f) {

                                    var filter = context['filter'][f];
                                    var property = filter['property'];
                                    var as = filter['as'] || property;

                                    if(data[as] == null) {
                                        data[as] = [];
                                    }

                                    //Selected items
                                    var selected = widget.table.$('tr.selected');

                                    //Calculate the data to send for all the selected rows
                                    for(var s = 0, len = selected.length; s < len; ++s) {

                                        var selectedData = widget.table.row(selected[s]).data();
                                        var propertyValue = selectedData[property];

                                        if(property != null && typeof propertyValue !== 'undefined') {
                                            data[as].push(propertyValue);
                                        }

                                    }
                                }

                            }

                            //Send the update context
                            framework.data.updateContext(context['id'], data);
                        }
                    }
                }

            });

        }


    };

    Table.prototype.delete = function() {

        //Stop observing for data changes
        framework.data.stopObserve(this.observeCallback);

        //TODO

    };

    // PRIVATE METHODS - - - - - - - - - - - - - - - - - - - - - -

    //Function that returns the value to replace with the label variables
    var replacer = function(metricId, metricData, str) {

        //Remove the initial an trailing '%' of the string
        str = str.substring(1, str.length-1);

        //Check if it is a parameter an return its value
        if(str === "mid") {
            return metricId;
        } else if(metricData['request']['params'][str] != null) {
            return metricData['request']['params'][str];
        }

        return "";
    };


    /**
     * Gets a normalized array of data according to the chart expected input from the data returned by the framework.
     * @param framework_data
     * @returns {Array} Contains objects with 'label' and 'value'.
     */
    var getNormalizedData = function getNormalizedData(framework_data) {

        var values = [];
        var labelVariable = /%\w+%/g; //Regex that matches all the "variables" of the label such as %mid%, %pid%...

        for(var metricId in framework_data) {

            for(var m = 0; m < framework_data[metricId].length; ++m) {

                var metricData = framework_data[metricId][m];

                for(var d = 0; d < metricData.length; ++d) {
                    values.push(metricData[d]);
                }

            }
        }

        return values;

    };

    window.framework.widgets.Table = Table;

})();





