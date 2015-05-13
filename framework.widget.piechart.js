(function() {

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

        return configuration;
    };

    /* PieChart constructor
     *   element: the DOM element that will contain the PieChart
     *   metrics: the metrics id array
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
                this.updateData(event.data);
                this.endLoading();
            }

        }.bind(this);

        framework.metrics.observe(metrics, this.observeCallback , contextId);

    };

    PieChart.prototype = new framework.widgets.CommonWidget(true);

    PieChart.prototype.updateData = function(framework_data) {

        //Update data
        if(this.svg != null) {
            d3.select(this.svg.get(0)).datum(getNormalizedValues(framework_data));
            this.chart.update();

        } else { // Paint it for first time
            paint.call(this, getNormalizedValues(framework_data));
        }

    };

    PieChart.prototype.delete = function() {

        //Stop observing for data changes
        framework.metrics.stopObserve(this.observeCallback);

        //Clear DOM
        $(this.svg).empty();
        this.element.empty();

        this.svg = null;
        this.chart = null;

    };


    // PRIVATE METHODS - - - - - - - - - - - - - - - - - - - - - -


    /**
     * Gets a normalized array of data according to the chart expected input from the data returned by the framework.
     * @param framework_data
     * @returns {Array} Contains objects with 'label' and 'value'.
     */
    var getNormalizedValues = function normalizeData(framework_data) {

        var values = [];

        for(var metricId in framework_data) {

            for(var m in framework_data[metricId]){

                for(var i in framework_data[metricId][m]['values']) {
                    values.push({
                        label: metricId, //TODO
                        value: framework_data[metricId][m]['values'][i]
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
                        return d.label; //TODO:label
                    })
                    .y(function(d) {
                        return d.value; //TODO:value
                    })
                    .donut(this.configuration.donut)//TODO: configurable
                    .width(width)
                    .height(height)
                    .padAngle(this.configuration.padAngle)
                    .cornerRadius(this.configuration.cornerRadius)
                    .growOnHover(this.configuration.growOnHover)
                    .showLegend(this.configuration.showLegend)
                    .showLabels(this.configuration.showLabels)
                    .donutRatio(this.configuration.donutRatio)
                    .duration(this.configuration.duration)
                    .donutLabelsOutside(true);

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

})();





