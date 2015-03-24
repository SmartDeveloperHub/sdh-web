var activityChartObject, setData, chageUpDownIcon, timeChartVisible;
var margin, width, margin2, height, height2, width2;
var x, x2, y, y2, xAxis, xAxis2;

var dragTime4Pixel = 3000000;
var dragFactor = 3000000;

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
var activityChart = function activityChart(currentData, divID, changeHandler) {
    var downLimit, upLimit;
    var drag = d3.behavior.drag()
    .on("drag", function() {
            var mov = d3.event.dx * dragFactor;
            var dateFrom = brush.extent()[0].getTime() - (mov);
            var dateTo = brush.extent()[1].getTime() - (mov);
            var newRange = [new Date(dateFrom), new Date(dateTo)];
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
                return;
            }
            x.domain(brush.empty() ? x2.domain() : [new Date(dateFrom), new Date(dateTo)]);
            brush.extent(newRange);
            repaintChart();
    });

    var setSize = function() {
        var currentWidth = parseInt($("#"+divID).parent().width());
        
        margin = {top: 10, right: 10, bottom: 100, left: 40};
        width = currentWidth - margin.left - margin.right;
        margin2 = {top: 170, right: width*0.2, bottom: 20, left: width*0.2};

        height = 230 - margin.top - margin.bottom;

        height2 = 230 - margin2.top - margin2.bottom;

        width2 = width * 0.6;
    }
    setSize();

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
    setAxis();

    // Using always the same brust instance
    var brush = d3.svg.brush()
    .on("brush", brushed)
    //.on("brushstart", brushstart)
    .on("brushend", brushend);
    
    var coverArea, areaAdd, areaRem, areaAdd2, areaRem2, svg, focus, context, myTooltip, addPoints, remPoints;

    var setGs = function setGs() {
        //brush.x(x2);
        focus = svg.append("g")
            .attr("class", "focus")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
        context = svg.append("g")
            .attr("class", "context")
            .attr("transform", "translate(" + margin2.left + "," + margin2.top + ")");
    };


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

        areaRem2 = d3.svg.area()
            .interpolate("monotone")
            .x(function(d) { return x2(d.date); })
            .y1(function() {
               return y2(0);
             })
            .y0(function(d) { return y2(d.lines); });

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
            .x(function(d) { return x(d.date); })
            .y0(function() {
                return y(0);
             })
            .y1(function(d) {
                return y(d.lines);
            });

        areaRem = d3.svg.area()
            .interpolate("monotone")
            .x(function(d) { return x(d.date); })
            .y1(function() {
                return y(0);
             })
            .y0(function(d) { return y(d.lines); });

        svg = d3.select('#' + divID).append("svg")
            .attr('height', height)
            .attr('width', width)
            .attr("viewBox", "0 0 " + (width + margin.left + margin.right) + " " + (height + margin.top + margin.bottom))

        clipPath = svg.append("defs").append("clipPath")
            .attr("id", "clip")
          .append("rect")
            .attr("width", width)
            .attr("height", height);
    };
    setSvg();
    setGs();

    var addGroup, rmGroup;

    setData = function setData(data) {
        downLimit = new Date(data[0].values[0].date).getTime();
        upLimit = new Date(data[data.length-1].values[data[data.length-1].values.length-1].date).getTime();
        x.domain(d3.extent(data[0].values.map(function(d) { return d.date; })));
        y.domain([d3.min(data[1].values.map(function(d) { return d.lines; })) -100, d3.max(data[0].values.map(function(d) { return d.lines; })) + 100]);
        x2.domain(x.domain());
        y2.domain(y.domain());

        // Lines added
        var addData = data[0].values.map(function(d) { return {'date': d.date, 'lines': d.lines}; });
        var remData = data[1].values.map(function(d) { return {'date': d.date, 'lines': d.lines}; });

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

        focus.append("path")
            .datum(remData)
            .attr("class", "areaRem")
            .attr("d", areaRem)
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

        context.append("path")
            .datum(remData)
            .attr("class", "areaRem2")
            .attr("d", areaRem2);

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

    setData(currentData);

    function brushed() {
        x.domain(brush.empty() ? x2.domain() : brush.extent());
        focus.select(".areaAdd").attr("d", areaAdd);
        focus.select(".areaRem").attr("d", areaRem);
        focus.select(".gridY").call(make_x_axis()
            .tickSize(-height, 0, 0)
            .tickFormat(""));
        focus.select(".x.axis").call(xAxis);
    }

    function brushend(d) {
        var d=brush.empty() ? x2.domain() : brush.extent();
        if (changeHandler) {
            changeHandler(d[0], d[1]);
        }
        var dif = d[1].getTime() - d[0].getTime();
        dragFactor = dif/3252203414 * dragTime4Pixel;
    }

    var repaintChart = function repaintChart(isResize) {
        var oldDomain = brush.extent();
        if (isResize) {
            svg.remove();
            setSvg();
        } else {
            focus.remove();
            context.remove();
        }
        setSize();
        setAxis();
        setGs();
        setData(currentData);
        brush.extent(oldDomain);
        brushed();
    };

    $(window).bind('resizeEnd', function() {
        repaintChart(true);
    });

    timeChartVisible = true;

    $(window).bind('resizing', function() {
        repaintChart(true);
    });
};