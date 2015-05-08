(function() {

    //Constants
    var ONE_DAY_MS = 86400000;

    // CHECK D3
    if(typeof d3 === 'undefined') {
        console.error("Heatmap could not be loaded because d3 did not exist.");
        return;
    }

    // BASIC METHODS - - - - - - - - - - - - - - - - - - - - - -

    var Heatmap = function (element, metrics, contextId, configuration) {

        if(!framework.isReady()) {
            console.error("Heatmap object could not be created because framework is not loaded.");
            return;
        }

        if(metrics.length > 1) {
            metrics = metrics.splice(1, metrics.length-1);
            console.warn("This widget only allows one metric. The other metrics will be ignored.");
        }

        this.element = $(element); //Store as jquery object
        this.svg = null;
        this.data = null;
        this.aspect = null;
        this.resizeEventHandler = null;
        this.painted = false;
        this.metricId = metrics[0]['id'];

        this.observeCallback = function(data){
            this.updateData(data);
        }.bind(this);

        framework.metrics.observe(metrics, this.observeCallback , contextId);

    };

    Heatmap.prototype.updateData = function(data) {
        this.delete();
        this.data = data[this.metricId];
        this.paint();
    };

    Heatmap.prototype.delete = function() {

        if(!this.painted)
            return;

        //Stop listening to window resize events
        $(window).off("resize", this.resizeEventHandler);
        this.resizeEventHandler = null;

        //Stop observing for data changes
        framework.metrics.stopObserve(this.observeCallback);

        //Clear DOM
        this.svg.empty();
        this.element.empty();

        this.painted = false;

    };


    // EXTRA METHODS - - - - - - - - - - - - - - - - - - - - - -

    Heatmap.prototype.paint = function() {

        console.log("paint");

        if(this.data == null) {
            return; // Nothing to paint
        }

        if(this.painted) {
            this.updateSize();
            return;
        }

        // Add an svg element to draw in it
        this.element.append('<svg role="heatmap" class="heatmap" preserveAspectRatio="xMidYMid"></svg>');
        this.svg = this.element.children("svg");
        this.element.append(this.svg);

        // Metric values
        var dataValues = this.data['values'];
        var timeStep = this.data['step'];

        //UI configuration
        var itemSize = 18,
            cellSize = itemSize-1,
            width = this.element.width(),
            height = this.element.height(),
            margin = {top:20,right:20,bottom:20,left:40};

        //formats
        var dateFormat = d3.time.format('%m/%d/%Y'),
            monthNameFormat = d3.time.format('%b'),
            weekDayFormat = d3.time.format("%w");

        //data vars for rendering
        var colorCalibration = ['#f6faaa','#FEE08B','#FDAE61','#F46D43','#D53E4F','#9E0142'];

        //axises and scales
        var axisWidth = 0 ,
            axisHeight = itemSize*7,
            xAxisScale = d3.time.scale(),
            yAxisScale = d3.scale.linear(),
            xAxis = d3.svg.axis()
                .orient('top')
                .ticks(d3.time.months,1)
                .tickFormat(monthNameFormat),
            weekDayNames = ["Sun", "Mon", "Tues", "Wed", "Thurs", "Fri", "Sat"];
            yAxis = d3.svg.axis()
                .orient('left')
                .ticks(7)
                .tickFormat(function(d, i){
                    return weekDayNames[d];
                });

        var d3svg = d3.select(this.svg.get(0));
        var heatmap = d3svg
            .attr('width',width)
            .attr('height',height)
            .style('max-height', height)
            .append('g')
            .attr('width',width-margin.left-margin.right)
            .attr('height',height-margin.top-margin.bottom)
            .attr('class','heatmap-content')
            .attr('transform','translate('+margin.left+','+margin.top+')');

        var rect = null;
        var paintDelay = 1000;

        var maxValue = -1;
        var minValue = Infinity;
        var dailyValues = [];

        //Create a date extent object with the from and to dates
        var dateExtent = [new Date(this.data['interval']['from']), new Date(this.data['interval']['to'])];

        //Clean the from date to start from midnight
        var firstDay = new Date(dateExtent[0]);
        firstDay.setHours(0);
        firstDay.setMinutes(0);
        firstDay.setMilliseconds(0);

        dataValues.forEach(function(value,index){

            //Given the data index and the from date, calculate the current date
            var curDate = dateExtent[0].getTime() + index * timeStep;

            //How many days has been since the from date
            var day = Math.floor((curDate - firstDay) / ONE_DAY_MS);

            //Increment the value for that day
            dailyValues[day] = (dailyValues[day] != null ? dailyValues[day] + value : value);

        });

        //Get the min and max values (to create later the color scale)
        dailyValues.forEach(function(value){
            minValue = d3.min([minValue, value]);
            maxValue = d3.max([maxValue, value]);
        });


        function renderColor(){
            rect
                .filter(function(value){
                    return value >= 0;
                })
                .transition()
                .delay(function(d, index){
                    var max = dateExtent[1].getTime();
                    var min = dateExtent[0].getTime();
                    var current = dateExtent[0].getTime() + index * timeStep;
                    return paintDelay * (current - min)/(max - min);
                })
                .duration(500)
                .attrTween('fill',function(value,i,a){
                    //choose color dynamicly
                    var colorIndex = d3.scale.quantize()
                        .range([0,1,2,3,4,5])
                        .domain([minValue,maxValue]);
                    return d3.interpolate(a,colorCalibration[colorIndex(value)]);
                });
        }

        var numWeeks = Math.ceil((dateExtent[1] - dateExtent[0] + weekDayFormat(dateExtent[0]) * ONE_DAY_MS) / 604800000);

        axisWidth = itemSize * numWeeks; //53 weeks in a year

        //render axises
        xAxis.scale(xAxisScale.range([0,axisWidth]).domain([dateExtent[0],dateExtent[1]]));
        yAxis.scale(yAxisScale.range([0,axisHeight]).domain([0,6]));

        d3svg.append('g')
            .attr('transform','translate('+margin.left+','+margin.top+')')
            .attr('class','x axis')
            .call(xAxis);
        d3svg.append('g')
            .attr('transform','translate('+margin.left+','+margin.top+')')
            .attr('class','y axis')
            .call(yAxis);

        //render heatmap rects
        rect = heatmap.selectAll('rect')
            .data(dailyValues)
            .enter().append('rect')
            .attr('width',cellSize)
            .attr('height',cellSize)
            .attr('x',function(d,index){
                var curDate = dateExtent[0].getTime() + index * ONE_DAY_MS;
                return itemSize * Math.floor((curDate - firstDay + weekDayFormat(firstDay) * ONE_DAY_MS) / 604800000);
            })
            .attr('y',function(d,index){
                var curDate = dateExtent[0].getTime() + index * ONE_DAY_MS;
                return weekDayFormat(new Date(curDate))*itemSize;
            })
            .attr('fill','#ffffff');

        rect.filter(function(value){ return value>0;})
            .append('title')
            .text(function(value, index){
                var curDate = dateExtent[0].getTime() + index * ONE_DAY_MS;
                return dateFormat(new Date(curDate))+': '+value;
            });

        renderColor();

        var chartWidth = numWeeks * itemSize + margin.left + margin.right;
        var chartHeight = 7 * itemSize + margin.top + margin.bottom;
        d3svg.attr('viewBox', '0 0 '+chartWidth+' '+chartHeight);
        this.aspect = chartWidth / chartHeight;

        this.resizeEventHandler = this.updateSize.bind(this);
        $(window).resize(this.resizeEventHandler);

        this.updateSize();

    };

    Heatmap.prototype.updateSize = function() {

        console.log("updateSize");
        var parentWidth = this.svg.parent().get(0).getBoundingClientRect().width;
        var parentHeight = this.svg.parent().get(0).getBoundingClientRect().height;
        var parentAspect = parentWidth / parentHeight;
        if(parentAspect <= this.aspect){
            this.svg.attr('width', parentWidth);
            this.svg.attr('height', parentWidth / this.aspect);
        } else {
            this.svg.attr('width', parentHeight * this.aspect);
            this.svg.attr('height', parentHeight);
        }
    };

    window.framework.widgets.Heatmap = Heatmap;

})();