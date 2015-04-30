(function() {

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

        this.element = $(element); //Store as jquery object
        this.svg = null;
        this.data = null;
        this.aspect = null;
        this.resizeEventHandler = null;
        this.painted = false;

        this.observeCallback = function(data){
            this.updateData(data);
        }.bind(this);

        framework.metrics.observeSeries(metrics, this.observeCallback , contextId, 1);

    };

    Heatmap.prototype.updateData = function(data) {
        this.delete();
        this.data = data;
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
        $(this.svg).empty();
        this.element.empty();

        this.painted = false;

    };


    // EXTRA METHODS - - - - - - - - - - - - - - - - - - - - - -

    Heatmap.prototype.paint = function() {

        if(this.data == null) {
            return; // Nothing to paint
        }

        if(this.painted) {
            this.updateSize();
            return;
        }

        // Add an svg element to draw in it
        this.svg = document.createElement("svg");
        this.element.append(this.svg);

        //UI configuration
        var itemSize = 18,
            cellSize = itemSize-1,
            width = $(this.svg).width(),
            height = $(this.svg).height(),
            margin = {top:20,right:20,bottom:20,left:40};

        //formats
        var dateFormat = d3.time.format('%m/%d/%Y'),
            monthNameFormat = d3.time.format('%b'),
            weekDayFormat = d3.time.format("%w");

        //data vars for rendering
        var dateExtent = null,
            colorCalibration = ['#f6faaa','#FEE08B','#FDAE61','#F46D43','#D53E4F','#9E0142'];

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

        var d3svg = d3.select(this.svg);
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
        var maxValue = -1;
        var minValue = Infinity;
        var paintDelay = 1000;

        this.data.forEach(function(d){
            minValue = d3.min([minValue, d.value]);
            maxValue = d3.max([maxValue, d.value]);
        });

        function renderColor(){
            rect
                .filter(function(d){
                    return (d.value>=0);
                })
                .transition()
                .delay(function(d){
                    var max = dateExtent[1].getTime();
                    var min = dateExtent[0].getTime();
                    var current = d.date.getTime();
                    return paintDelay * (current - min)/(max - min);
                })
                .duration(500)
                .attrTween('fill',function(d,i,a){
                    //choose color dynamicly
                    var colorIndex = d3.scale.quantize()
                        .range([0,1,2,3,4,5])
                        .domain([minValue,maxValue]);
                    return d3.interpolate(a,colorCalibration[colorIndex(d.value)]);
                });
        }

        dateExtent = d3.extent(this.data,function(d){
            return d.date;
        });

        var numWeeks = Math.ceil((dateExtent[1] - dateExtent[0] + weekDayFormat(dateExtent[0]) * 86400000) / 604800000);

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
            .data(this.data)
            .enter().append('rect')
            .attr('width',cellSize)
            .attr('height',cellSize)
            .attr('x',function(d){
                return itemSize * Math.floor((d.date - dateExtent[0] + weekDayFormat(dateExtent[0]) * 86400000) / 604800000);
            })
            .attr('y',function(d){
                return weekDayFormat(d.date)*itemSize;
            })
            .attr('fill','#ffffff');

        rect.filter(function(d){ return d.value>0;})
            .append('title')
            .text(function(d){
                return dateFormat(d.date)+': '+d.value;
            });

        renderColor();

        var chartWidth = numWeeks * itemSize + margin.left + margin.right;
        var chartHeight = 7 * itemSize + margin.top + margin.bottom;
        d3svg.attr('viewBox', '0 0 '+chartWidth+' '+chartHeight);
        this.aspect = chartWidth / chartHeight;

        this.resizeEventHandler = this.updateSize;
        $(window).resize(this.resizeEventHandler);

        this.updateSize();

    };

    Heatmap.prototype.updateSize = function() {
        var parentWidth = this.svg.parentElement.getBoundingClientRect().width;
        var parentHeight = this.svg.parentElement.getBoundingClientRect().height;
        var parentAspect = parentWidth / parentHeight;
        if(parentAspect <= this.aspect){
            this.svg.setAttribute('width', parentWidth);
            this.svg.setAttribute('height', parentWidth / this.aspect);
        } else {
            this.svg.setAttribute('width', parentHeight * this.aspect);
            this.svg.setAttribute('height', parentHeight);
        }
    };

    window.framework.widgets.Heatmap = Heatmap;

})();