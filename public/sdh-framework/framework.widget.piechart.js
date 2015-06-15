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

define(['sdh-framework/framework.widget.common'], function() {

    // CHECK D3
    if(typeof d3 === 'undefined') {
        console.error("PieChart could not be loaded because d3 did not exist.");
        return;
    }

    // CHECK NVD3
    if(typeof nv === 'undefined') {
        console.error("PieChart could not be loaded because nvd3 did not exist.");
        return;
    }

    var normalizeConfig = function normalizeConfig(configuration) {
        if (configuration == null) {
            configuration = {};
        }
        if (typeof configuration.donut != "boolean") {
            configuration.donut = false;
        }
        if (typeof configuration.growOnHover != "boolean") {
            configuration.growOnHover = false;
        }
        if (typeof configuration.cornerRadius != "number") {
            configuration.cornerRadius = 4;
        }
        if (typeof configuration.padAngle != "number") {
            configuration.padAngle = 0.05;
        }
        if (typeof configuration.showLegend != "boolean") {
            configuration.showLegend = true;
        }
        if (typeof configuration.showLabels != "boolean") {
            configuration.showLabels = true;
        }
        if (typeof configuration.donutRatio != "number") {
            configuration.donutRatio = 0.5;
        }
        if (typeof configuration.duration != "number") {
            configuration.duration = 250;
        }
        if (typeof configuration.labelFormat != "string") {
            configuration.labelFormat = "%mid%";
        }
        if (typeof configuration.labelsOutside != "boolean") {
            configuration.labelsOutside = true;
        }

        return configuration;
    };

    /* PieChart constructor
     *   element: the DOM element that will contain the PieChart
     *   data: the data id array
     *   contextId: optional.
     *   configuration: additional chart configuration:
     *      {
     *       ~ donut: boolean - Whether to make a pie graph a donut graph or not.
     *       ~ growOnHover: boolean - For pie/donut charts, whether to increase slice radius on hover or not.
     *       ~ cornerRadius: number - For donut charts only, the corner radius (in pixels) of the slices.
     *       ~ padAngle: number - The percent of the chart that should be spacing between slices.
     *       ~ showLegend: boolean - Whether to display the legend or not.
     *       ~ showLabels: boolean - Show pie/donut chart labels for each slice.
     *       ~ donutRatio: number - Percent of pie radius to cut out of the middle to make the donut. It is multiplied
     *         by the outer radius to calculate the inner radius, thus it should be between 0 and 1.
     *       ~ duration: number - Duration in ms to take when updating chart. For things like bar charts, each bar can
     *         animate by itself but the total time taken should be this value.
     *       ~ labelFormat: string - Format string for the labels. Metric parameters can be used as variables by
     *         surrounding their names with percentages. The metric name can also be accessed with %mid%. For example,
     *         the following is a valid labelFormat: "User: %uid%".
     *       ~ labelsOutside: boolean - Whether pie/donut chart labels should be outside the slices instead of inside them.
     *      }
     */
    var PieChart = function PieChart(element, metrics, contextId, configuration) {

        if(!framework.isReady()) {
            console.error("PieChart object could not be created because framework is not loaded.");
            return;
        }

        this.element = $(element); //Store as jquery object
        this.svg = null;
        this.data = null;
        this.chart = null;

        // Extending widget
        framework.widgets.CommonWidget.call(this, false, this.element.get(0));

        // Configuration
        this.configuration = normalizeConfig(configuration);

        this.observeCallback = function(event){

            if(event.event === 'loading') {
                this.startLoading();
            } else if(event.event === 'data') {
                this.endLoading(this.updateData.bind(this, event.data));
            }

        }.bind(this);

        framework.data.observe(metrics, this.observeCallback , contextId);

    };

    PieChart.prototype = new framework.widgets.CommonWidget(true);

    PieChart.prototype.updateData = function(framework_data) {

        var normalizedData = getNormalizedData.call(this,framework_data);

        //Update data
        if(this.svg != null) {
            d3.select(this.svg.get(0)).datum(normalizedData);
            this.chart.update();

        } else { // Paint it for first time
            paint.call(this, normalizedData);
        }

    };

    PieChart.prototype.delete = function() {

        //Stop observing for data changes
        framework.data.stopObserve(this.observeCallback);

        //Clear DOM
        $(this.svg).empty();
        this.element.empty();

        this.svg = null;
        this.chart = null;

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

            // TODO i<.length
            for(var m in framework_data[metricId]){

                var metricData = framework_data[metricId][m];

                //Create a replacer for this metric
                var metricReplacer = replacer.bind(null, metricId, metricData);

                //Generate the label by replacing the variables
                var label = this.configuration.labelFormat.replace(labelVariable,metricReplacer);

                for(var i in metricData['values']) {

                    values.push({
                        label: label,
                        value: metricData['values'][i]
                    });
                }
            }
        }

        return values;

    };

    var paint = function paint(data) {

        this.element.append('<svg class="blurable"></svg>');
        this.svg = this.element.children("svg");

        nv.addGraph({
            generate: function() {

                var width = this.element.get(0).getBoundingClientRect().width,
                    height = this.element.get(0).getBoundingClientRect().height;

                this.chart = nv.models.pieChart()
                    .x(function(d) {
                        return d.label;
                    })
                    .y(function(d) {
                        return d.value;
                    })
                    .donut(this.configuration.donut)
                    .width(width)
                    .height(height)
                    .padAngle(this.configuration.padAngle)
                    .cornerRadius(this.configuration.cornerRadius)
                    .growOnHover(this.configuration.growOnHover)
                    .showLegend(this.configuration.showLegend)
                    .showLabels(this.configuration.showLabels)
                    .donutRatio(this.configuration.donutRatio)
                    .duration(this.configuration.duration)
                    .labelsOutside(this.configuration.labelsOutside);

                d3.select(this.svg.get(0))
                    .datum(data) //TODO
                    .transition().duration(0)
                    .call(this.chart);

                return this.chart;

            }.bind(this),
            callback: function(graph) {
                nv.utils.windowResize(function() {
                    var width = this.element.get(0).getBoundingClientRect().width;
                    var height = this.element.get(0).getBoundingClientRect().height;
                    graph.width(width).height(height);

                    d3.select(this.svg.get(0))
                        .attr('width', width)
                        .attr('height', height)
                        .transition().duration(0)
                        .call(graph);

                }.bind(this));
            }.bind(this)
        });

    };

    window.framework.widgets.PieChart = PieChart;

});