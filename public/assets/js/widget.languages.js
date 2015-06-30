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
*/

(function() {

    var topLanguagesNumber = 3;

    var normalizeConfig = function normalizeConfig(configuration) {
        if (configuration == null) {
            configuration = {};
        }
        if (configuration['pie'] == null) {
            configuration['pie'] = {};
        }
        if (typeof configuration['pie'].donut != "boolean") {
            configuration['pie'].donut = false;
        }
        if (typeof configuration['pie'].growOnHover != "boolean") {
            configuration['pie'].growOnHover = false;
        }
        if (typeof configuration['pie'].cornerRadius != "number") {
            configuration['pie'].cornerRadius = 4;
        }
        if (typeof configuration['pie'].padAngle != "number") {
            configuration['pie'].padAngle = 0.05;
        }
        if (typeof configuration['pie'].showLegend != "boolean") {
            configuration['pie'].showLegend = true;
        }
        if (typeof configuration['pie'].showLabels != "boolean") {
            configuration['pie'].showLabels = true;
        }
        if (typeof configuration['pie'].donutRatio != "number") {
            configuration['pie'].donutRatio = 0.5;
        }
        if (typeof configuration.duration != "number") {
            configuration['pie'].duration = 250;
        }
        if (typeof configuration['pie'].labelsOutside != "boolean") {
            configuration['pie'].labelsOutside = true;
        }

        if (configuration['horiz'] == null) {
            configuration['horiz'] = {};
        }

        var horizDefaultConfig = {
            height: {
                type: 'number',
                default: 240
            },
            color: {
                type: 'object',
                default: null
            },
            stacked: {
                type: 'boolean',
                default: false
            },
            groupSpacing: {
                type: 'number',
                default: 0.1
            },
            duration: {
                type: 'number',
                default: 250
            },
            showControls: {
                type: 'boolean',
                default: true
            },
            showLegend: {
                type: 'boolean',
                default: true
            },
            showXAxis: {
                type: 'boolean',
                default: true
            },
            showYAxis: {
                type: 'boolean',
                default: true
            },
            yAxisTicks: {
                type: 'number',
                default: 5
            }
        };

        for(var confName in horizDefaultConfig) {
            var conf = horizDefaultConfig[confName];
            if (typeof configuration['horiz'][confName] != conf['type']) {
                configuration['horiz'][confName] = conf['default'];
            }
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
    var Languages = function Languages(element, contextId, configuration) {

        if(!framework.isReady()) {
            console.error("Languages object could not be created because framework is not loaded.");
            return;
        }

        // CHECK D3
        if(typeof d3 === 'undefined') {
            console.error("Languages could not be loaded because d3 did not exist.");
            return;
        }

        // CHECK NVD3
        if(typeof nv === 'undefined') {
            console.error("Languages could not be loaded because nvd3 did not exist.");
            return;
        }

        this.element = $(element); //Store as jquery object
        this.svg = null;
        this.horizontals = null;
        this.horizontalCharts = [];
        this.context = contextId;
        this.data = null;
        this.piechart = null;

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

        metrics = [{
            id: 'userprojectlanguagelines'
        }];

        framework.data.observe(metrics, this.observeCallback , contextId);

    };

    Languages.prototype = new framework.widgets.CommonWidget(true);

    Languages.prototype.updateData = function(framework_data) {

        var normalizedData = getNormalizedData.call(this,framework_data);

        //Update data
        if(this.piechart != null) {
            d3.select(this.svg.get(0)).datum(normalizedData['pie']);
            this.piechart.color(this.generateColors(framework_data));
            this.piechart.update();

            paintHorizontals.call(this, data.horizontal, framework_data);

        } else { // Paint it for first time
            paint.call(this, normalizedData, framework_data);
        }

    };

    Languages.prototype.delete = function() {

        //Stop observing for data changes
        framework.data.stopObserve(this.observeCallback);

        //Remove the horizontal widgets
        for(var i = 0; i < this.horizontalCharts.length; ++i) {
            this.horizontalCharts[i].delete();
        }

        //Clear DOM
        this.horizontals.empty();
        this.element.empty();

        this.svg = null;
        this.horizontals = null;
        this.piechart = null;

    };


    // PRIVATE METHODS - - - - - - - - - - - - - - - - - - - - - -

    /**
     * Gets a normalized array of data according to the chart expected input from the data returned by the framework.
     * @param framework_data
     * @returns {Object} Contains objects with 'label' and 'value'.
     */
    var getNormalizedData = function getNormalizedData(framework_data) {

        var values = {pie: [], horizontal:[]};
        var totals = {};
        var topLanguages = [];

        // Calculate the data required by the pie chart (the totals)
        for(var metricId in framework_data) {

            for(var m in framework_data[metricId]){

                var metric = framework_data[metricId][m];
                var metricData = framework_data[metricId][m]['data'];

                for(var lang in metricData['values']) {

                    if(totals[lang] == null) {
                        totals[lang] = 0;
                    }

                    totals[lang] += metricData['values'][lang];

                }
            }
        }

        for(var lang in totals) {
            values.pie.push({
                label: lang,
                value: totals[lang]
            });
        }

        //Calculate the top languages
        for(var i = 0; i < values.pie.length; ++i) {

            var lang = values.pie[i]['label'];
            var newVal = totals[lang];

            if(topLanguages.length >= topLanguagesNumber) {

                for(var l = topLanguagesNumber - 1; l >= 0; l-- ) {
                    var currentVal = totals[topLanguages[l]];

                    if(newVal <= currentVal && l < topLanguagesNumber) {
                        topLanguages.splice(l + 1, 0, lang);
                        topLanguages.pop();
                        break;
                    } else if(newVal <= currentVal) {
                        break;
                    } else if(l == 0) {
                        topLanguages.splice(0, 0, lang);
                        topLanguages.pop();
                        break;
                    }

                }
            } else {
                for(var l = topLanguages.length - 1; l >= 0; l-- ) {
                    var currentVal = totals[topLanguages[l]];
                    if(currentVal >= newVal)
                        break;
                }
                topLanguages.splice(l + 1, 0, lang);
            }
        }


        // Get the data required by the horizontal charts
        for(var l = 0; l < topLanguages.length; l++) {

            var lang = topLanguages[l];
            values.horizontal[l] = {
                label: lang,
                chart: []
            };

            for(var metricId in framework_data) {

                for(var m in framework_data[metricId]){

                    var metric = framework_data[metricId][m];
                    var metricData = framework_data[metricId][m]['data'];

                    //Now calculate the data for the horizontal charts
                    var mData = {
                        key: metricData.info.rid.name,
                        values: [{
                            x: 1,
                            y: metricData['values'][lang]
                        }]
                    };

                    values.horizontal[l]['chart'].push(mData);

                }
            }


        }


        return values;

    };

    var paint = function paint(data, framework_data) {
        paintPie.call(this, data.pie, framework_data);
        paintHorizontals.call(this, data.horizontal, framework_data);
    };

    var paintPie = function paintPie(data, framework_data) {

        var html = '<div class="row">';
        html+= '        <div class="col-sm-8">';
        html+= '            <div class="languages-horizontals"></div>';
        html+= '        </div>';
        html+= '        <div class="col-sm-4">';
        html+= '            <div class="languages-piechart-widget">';
        html+= '                <svg class="blurable"></svg>';
        html+= '            </div>';
        html+= '        </div>';
        html+= '    </div>';
        html = $(html);

        this.element.append(html);
        var svg = html.find(".languages-piechart-widget svg");
        this.horizontals = html.find(".languages-horizontals");

        nv.addGraph({
            generate: function() {

                var width = this.element.get(0).getBoundingClientRect().width,
                    height = this.element.get(0).getBoundingClientRect().height;

                this.piechart = nv.models.pieChart()
                    .x(function(d) {
                        return d.label;
                    })
                    .y(function(d) {
                        return d.value;
                    })
                    .donut(this.configuration.pie.donut)
                    .width(width)
                    .height(height)
                    .padAngle(this.configuration.pie.padAngle)
                    .cornerRadius(this.configuration.pie.cornerRadius)
                    .growOnHover(this.configuration.pie.growOnHover)
                    .showLegend(this.configuration.pie.showLegend)
                    .showLabels(this.configuration.pie.showLabels)
                    .donutRatio(this.configuration.pie.donutRatio)
                    .duration(this.configuration.pie.duration)
                    .labelsOutside(this.configuration.pie.labelsOutside)
                    .color(this.generateColors(framework_data));

                d3.select(svg.get(0))
                    .datum(data) //TODO
                    .transition().duration(0)
                    .call(this.piechart);

                return this.piechart;

            }.bind(this),
            callback: function(graph) {
                nv.utils.windowResize(function() {
                    var width = this.element.get(0).getBoundingClientRect().width;
                    var height = this.element.get(0).getBoundingClientRect().height;
                    graph.width(width).height(height);

                    d3.select(svg.get(0))
                        .attr('width', width)
                        .attr('height', height)
                        .transition().duration(0)
                        .call(graph);

                }.bind(this));
            }.bind(this)
        });

    };

    var paintHorizontals = function paintHorizontals(data, framework_data) {

        var topLanguages = [];

        //If already painted, destroy them
        if(this.horizontalCharts.length > 0) { //TODO: this should be done only when the languages change

            //Remove the horizontal widgets
            for(var i = 0; i < this.horizontalCharts.length; ++i) {
                this.horizontalCharts[i].delete();
            }
            this.horizontalCharts = [];

            this.horizontals.empty();

        }

        //Create the horizontal widgets
        for(var i = 0; i < data.length; ++i) {

            var horizHtml = '';
            horizHtml += '<div class="row">';
            horizHtml += '  <div class="col-sm-10">';
            horizHtml += '      <div class="languages-horizontal-widget"><svg class="blurable"></svg></div>';
            horizHtml += '  </div>';
            horizHtml += '  <div class="col-sm-2 languages-label"></div>';
            horizHtml += '</div>';

            var horizDom = $(horizHtml);
            this.horizontals.append(horizDom);

            horizDom.find(".languages-label").text(data[i]['label']);
            var svg = horizDom.find(".languages-horizontal-widget svg");

            paintHorizontal.call(this, i, svg, data[i]['chart'], framework_data);

        }

    };

    var paintHorizontal = function PaintHorizontal(number, svg, data, framework_data) {
        nv.addGraph(function() {
            var chart = nv.models.multiBarHorizontalChart()
                .x(function(d) { return d.x; })
                .y(function(d) { return d.y; })
                .height(this.configuration.horiz.height)
                .color(this.generateColors(framework_data, this.configuration.horiz.color))
                .stacked(this.configuration.horiz.stacked)
                .groupSpacing(this.configuration.horiz.groupSpacing)
                .duration(this.configuration.horiz.duration)
                .showControls(this.configuration.horiz.showControls)
                .showLegend(this.configuration.horiz.showLegend)
                .showXAxis(this.configuration.horiz.showXAxis)
                .showYAxis(this.configuration.horiz.showYAxis);
            this.horizontalCharts[number] = chart;

            chart.xAxis.tickFormat(function(d) {
                return d3.time.format('%x')(new Date(d));
            })
                .showMaxMin(false);

            chart.yAxis.tickFormat(function(d) {
                if (d >= 1000 || d <= -1000) {
                    return Math.abs(d/1000) + " K";
                } else {
                    return Math.abs(d);
                }
            }).showMaxMin(true).ticks(this.configuration.horiz.yAxisTicks - 1);

            d3.select(svg.get(0))
                .datum(data)
                .call(chart);


            nv.utils.windowResize(chart.update);

            return chart;
        }.bind(this));

    };

    window.framework.widgets.Languages = Languages;

    // AMD compliant
    if ( typeof define === "function" && define.amd) {
        define( [], function () { return Languages; } );
    }

})();