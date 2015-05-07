(function() {

    // CHECK D3
    if(typeof d3 === 'undefined') {
        console.error("RangeChart could not be loaded because d3 did not exist.");
        return;
    }
    // BASIC METHODS - - - - - - - - - - - - - - - - - - - - - -
    function normalizeConfig(configuration) {
        if (typeof configuration !== 'object') {
            configuration = {};
        }
        if (typeof configuration.ownContext != "string") {
            configuration.ownContext = "dafault_rangeChartD3_Context_id";
        }
        if (typeof configuration.ownContext != "number") {
            configuration.maxData = 100;
        }
        if (typeof configuration.brushedHandler != "function") {
            configuration.brushedHandler = null;
        }
        return configuration;
    };
    // TODO TEST THIS WIDGET!!!

    /* RangeChart constructor
    *   element: the DOM element that will contains the range chart svg
    *   metrics: the metrics id array
    *   contextId: if necesary, the contextId link this chart metrics data
    *           with changes in other context provider chart.
    *  configuration: you can use his optional parameter to assing a custom
    *           contextID for this context provider chart. Ej:
    *               {
    *                   ownContext: "myCustomContextID",
    *                   maxData: max serie data numbers
    *               }
    */
    var brush;

    var RangeChart = function RangeChart(element, metrics, contextId, configuration) {

        if(!framework.isReady()) {
            console.error("RangeChart object could not be created because framework is not loaded.");
            return;
        }
        configuration = normalizeConfig(configuration);

        this.ownContext = configuration.ownContext;
        this.maxData = configuration.maxData;
        this.brushedHandler = configuration.brushedHandler;

        this.element = element;
        this.svg = null;
        this.data = null;
        this.maxData = 100;

        brush = d3.svg.brush()
            .on("brush", brushed.bind(this))
            .on("brushstart", brushstart.bind(this))
            .on("brushend", brushend.bind(this));

        initChart.call(this);

        this.observeMetric = function(data) {
            // TODO two series in the same graph
            var metric = data[Object.keys(data)[0]];
            var timePoint = metric.interval.from - metric.step;
            this.data = {
                "key": metric.metricinfo.description,
                "color": "#2ca02c",
                "area": true,
                "values": metric.values.map(function(dat, index) {
                    timePoint += metric.step;
                    return {'date': new Date(new Date(timePoint).getTime()), 'lines': dat};
                })
            };

            this.updateData(this.data);
        }.bind(this);

        framework.metrics.observe(metrics, this.observeMetric , contextId, this.maxData);
        this.resizeHandler = function() {
            repaintChart.call(this, true);
        }.bind(this);
        $(window).resize(this.resizeHandler);
    };
    //RangeChart.prototype = new widget();

    RangeChart.prototype.updateData = function(data) {
        repaintChart.call(this);
        // Incomprehensible graph bug. brush.extend() returns:
        // [Thu Jan 01 1970 01:00:00 GMT+0100 (Hora estándar romance), Thu Jan 01 1970 01:00:00 GMT+0100 (Hora estándar romance)]
        // if not repaint re-seting svg element :\
        repaintChart.call(this, true);
    };

    RangeChart.prototype.delete = function() {

        //Stop observing for data changes
        framework.metrics.stopObserve(this.observeCallback);

        //Clear DOM
        $(this.svg).empty();
        this.element.empty();
    };

    RangeChart.prototype.getContext = function() {
        return this.ownContext;
    };

    window.framework.widgets.RangeChart = RangeChart;


    // PRIVATE METHODS - - - - - - - - - - - - - - - - - - - - - -
    var dragTime4Pixel = 4500000;
    var dragFactor = 3000000;

    var activityChartObject, setData, chageUpDownIcon, timeChartVisible;
    var margin, width, margin2, height, height2, width2;

    var x, x2, y, y2, xAxis, xAxis2;

    function make_x_axis() {
        return d3.svg.axis()
            .scale(x)
            .orient("bottom");
    }

    function make_y_axis() {
        return d3.svg.axis()
            .scale(y)
            .orient("left");
    }

    var downLimit, upLimit;

    var drag = d3.behavior.drag()
    .on("dragstart", function() {
            if (brush.empty()) {
                return;
            }
    })
    .on("drag", function() {
            if (brush.empty()) {
                return;
            }
            var mov = d3.event.dx * dragFactor;
            var dateFrom = brush.extent()[0].getTime() - (mov);
            var dateTo = brush.extent()[1].getTime() - (mov);
            var newRange = [new Date(dateFrom).toString(), new Date(dateTo)];
            x.domain(brush.empty() ? x2.domain() : [new Date(dateFrom), new Date(dateTo)]);
            brush.extent(newRange);
            repaintChart();
    })
    .on("dragend", function() {
            if (brush.empty()) {
                return;
            }
            var mov = d3.event.dx * dragFactor;
            var dateFrom = brush.extent()[0].getTime();
            var dateTo = brush.extent()[1].getTime();
            var newRange = [new Date(dateFrom), new Date(dateTo)];
            if (dateFrom < downLimit) {
                var dif = dateTo - dateFrom;
                dateFrom = downLimit;
                dateTo = downLimit + dif;
                var newRange = [new Date(dateFrom), new Date(dateTo)];
            } else if(dateTo > upLimit) {
                var dif = dateTo - dateFrom;
                dateFrom = upLimit - dif;
                dateTo = upLimit;
                var newRange = [new Date(dateFrom), new Date(dateTo)];
            } else {
                //return;
            }
            x.domain(brush.empty() ? x2.domain() : [new Date(dateFrom), new Date(dateTo)]);
            brush.extent(newRange);
            repaintChart.call(this);
            brushend.call(this);
    });

    var setSize = function() {
        var currentWidth = parseInt($(this.element).parent().width());
        
        margin = {top: 10, right: 10, bottom: 100, left: 40};
        width = currentWidth - margin.left - margin.right;
        margin2 = {top: 170, right: width*0.2, bottom: 20, left: width*0.2};

        height = 230 - margin.top - margin.bottom;

        height2 = 230 - margin2.top - margin2.bottom;

        width2 = width * 0.6;
    }

    var setAxis = function setAxis() {
        x = d3.time.scale().range([0, width]);
        x2 = d3.time.scale().range([0, width/1.5]);
        y = d3.scale.linear().range([height, 0]);
        y2 = d3.scale.linear().range([height2, 0]);

        xAxis = d3.svg.axis().scale(x).orient("bottom");
        xAxis2 = d3.svg.axis().scale(x2).orient("bottom");
        yAxis = d3.svg.axis().scale(y).orient("left");

        xAxis.tickFormat(d3.time.format('%x'));
        xAxis2.tickFormat(d3.time.format('%x'));
        yAxis.tickFormat(function(d) {
            if (d >= 1000 || d <= -1000) {
                return Math.abs(d/1000) + " K";
            } else {
                return Math.abs(d);
            }
        });
    };

    var coverArea, areaAdd, areaAdd2, svg, focus, context, myTooltip, addPoints, remPoints;

    var setSvg = function setSvg() {
        brush.x(x2);
        areaAdd2 = d3.svg.area()
            .interpolate("monotone")
            .x(function(d) { return x2(d.date); })
            .y0(function() {
                return y2(0);
             })
            .y1(function(d) {
                return y2(d.lines);
            });

        coverArea = d3.svg.area()
            .interpolate("monotone")
            .x(function(d) { return x(d.date); })
            .y0(function() {
                return height;
             })
            .y1(function(d) {
                return 0;
            });

        areaAdd = d3.svg.area()
            .interpolate("monotone")
            .x(function(d) {
                return x(d.date);
            })
            .y0(function() {
                return y(0);
            })
            .y1(function(d) {
                return y(d.lines);
            });

        svg = d3.select(this.element)
            .attr('class', "activityRangeChart")
          .append("svg")
            .attr('height', height)
            .attr('width', width)
            .attr("viewBox", "0 0 " + (width + margin.left + margin.right) + " " + (height + margin.top + margin.bottom))
        this.svg = svg;

        clipPath = svg.append("defs").append("clipPath")
            .attr("id", "clip")
          .append("rect")
            .attr("width", width)
            .attr("height", height);
    };

    var setGs = function setGs() {
        //brush.x(x2);
        focus = svg.append("g")
            .attr("class", "focus")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
        context = svg.append("g")
            .attr("class", "context")
            .attr("transform", "translate(" + margin2.left + "," + margin2.top + ")");
    };

    setData = function setData() {
        data = this.data;
        downLimit = new Date(data.values[0].date).getTime();
        upLimit = new Date(data.values[data.values.length-1].date).getTime();
        x.domain(d3.extent(data.values.map(function(d) {
                return d.date;
            }
        )));
        y.domain([d3.min(data.values.map(function(d) {
                                                return d.lines;
                                            }
        )), d3.max(data.values.map(function(d) {
                return d.lines;
            }
        ))]);
        x2.domain(x.domain());
        y2.domain(y.domain());

        // Lines added
        var addData = data.values.map(function(d) { return {'date': d.date, 'lines': d.lines}; });

        /*TODO
        myTooltip = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0])
            .html(function(d) {
            return "<strong>Lines added:</strong> <span style='color:green'>" + d.lines + "</span> </br> <strong>Lines removed:</strong> <span style='color:red'>" + d.lines + "</span>";
        });*/

        focus.append("g")
            .attr("class", "gridY")
            .attr("transform", "translate(0," + height + ")")
            .call(make_x_axis()
                .tickSize(-height, 0, 0)
                .tickFormat("")
            )

        focus.append("g")
            .attr("class", "gridX")
            .call(make_y_axis()
                .tickSize(-width, 0, 0)
                .tickFormat("")
            )

        focus.append("path")
            .datum(addData)
            .attr("class", "areaAdd")
            .attr("d", areaAdd)
            .attr("clip-path", "url(#clip)");

        focus.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis);

        focus.append("g")
            .attr("class", "y axis")
            .call(yAxis);

        focus.append("path")
            .datum(addData)
            .attr("class", "cover")
            .attr("d", coverArea)
            .call(drag);

        /* Points */
        //TODO

        context.append("path")
            .datum(addData)
            .attr("class", "areaAdd2")
            .attr("d", areaAdd2);

        context.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height2 + ")")
            .call(xAxis2);

        context.append("g")
            .attr("class", "x brush")
            .call(brush)
            .selectAll("rect")
            .attr("y", -6)
            .attr("height", height2 + 7);

        //svg.call(myTooltip);
    };

    var repositioningDates = function repositioningDates() {
        // Discrete Time positions. Day by day by the moment. TODO using data frecuency
        // Take the current range
        var theInitial = brush.extent();
        theFinal= theInitial[1];
        theInitial = theInitial[0];
        return [theInitial, theFinal];
    }

    function brushstart() {
        console.log(brush.extent());
    };

    function brushed() {
        x.domain(brush.empty() ? x2.domain() : brush.extent());
        focus.select(".areaAdd").attr("d", areaAdd);
        focus.select(".gridY").call(make_x_axis()
            .tickSize(-height, 0, 0)
            .tickFormat(""));
        focus.select(".x.axis").call(xAxis);
        if (this.brushedHandler) {
            this.brushedHandler(brush.extent()[0], brush.extent()[1]);
        }
    }

    function brushend() {
        var d;

        if (brush.empty()) {
            d = x2.domain();
        } else {
            // Adjust to the closer position
            d = repositioningDates.call(this);
        }
        /*if (changeHandler) {
            changeHandler(d[0], d[1]);
        }*/
        framework.metrics.updateContext(this.ownContext, {from: moment(d[0]).format("YYYY-MM-DD"), to: moment(d[1]).format("YYYY-MM-DD")});
        var dif = d[1].getTime() - d[0].getTime();
        dragFactor = dif/3252203414 * dragTime4Pixel;
    }

    var repaintChart = function repaintChart(isResize) {
        var oldDomain = brush.extent();
        if (isResize) {
            this.svg.remove();
            setSvg.call(this);
        } else {
            focus.remove();
            context.remove();
        }
        setSize.call(this);
        setAxis.call(this);
        setGs.call(this);
        setData.call(this);
        brush.extent.call(this, oldDomain);
        brushed.call(this);
    };

    var initChart = function initChart() {
        setSize.call(this);
        setAxis.call(this);
        setSvg.call(this);
        setGs.call(this);
    };
})();