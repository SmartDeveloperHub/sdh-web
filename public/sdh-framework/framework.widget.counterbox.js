(function() {

    // CHECK D3
    if(typeof d3 === 'undefined') {
        console.error("CounterBox could not be loaded because d3 did not exist.");
        return;
    }

    var normalizeConfig = function normalizeConfig(configuration) {
        if (typeof configuration !== 'object') {
            configuration = {};
        }

        if (typeof configuration.label !== 'string') {
            configuration.label = "---";
        }

        if (typeof configuration.background !== 'string') {
            configuration.background = "#FFF";
        }

        if (typeof configuration.iconbackground !== 'string') {
            configuration.iconbackground = "#68B828";
        }

        if (typeof configuration.initfrom !== "number") {
            configuration.initfrom = 0;
        }

        if (typeof configuration.initto !== "number") {
            configuration.initto = 0;
        }

        if (typeof configuration.changetime !== "number") {
            configuration.changetime = 3;
        }

        if (typeof configuration.changeeasing !== "boolean") {
            configuration.changeeasing = true;
        }

        if (typeof configuration.label !== "string") {
            configuration.label = true;
        }

        if (typeof configuration.icon !== "string") {
            configuration.icon = "octicon octicon-octoface";
        }

        if (typeof configuration.iconcolor !== "string") {
            configuration.iconcolor = "#FFF";
        }

        if (typeof configuration.labelcolor !== "string") {
            configuration.testcolor = "rgba(0, 0, 0, 0.7)";
        }

        if (typeof configuration.countercolor !== "string") {
            configuration.testcolor = "#000";
        }

        if (typeof configuration.decimal !== "number") {
            configuration.decimal = 2;
        }
        return configuration;
    };

    /* CounterBox constructor
    *   element: the DOM element that will contains the CounterBox div
    *   metrics: the metrics id array
    *   contextId: if necesary, the contextId link this chart metrics data
    *           with changes in other context provider chart.
    *  configuration: you can use his optional parameter to assing a custom
    *       contextID for this context provider chart. Ej:
    *      {
    *         label: label text,
    *         labelcolor: label text color,
    *         countercolor: color of the number,
    *         background: optional color in any css compatible format ej: "#0C0C0C" (default #FFF),
    *         initfrom: optional initial animation value (default 0),
    *         initto: optional final animation value (default 0),
    *         changetime: this time in seconds set the change data animation duration (default 3),
    *         changeeasing: optional animation effect data-easing fast-slow (default true),
    *         icon: optional icon css class (default "octicon octicon-octoface"),
    *         iconcolor: widget icon color,
    *         iconbackground: optional color in any css compatible format ej: "#0C0C0C" (default #68B828),
    *         decimal: number of decimals in metric value
    *      }
    */
    var CounterBox = function CounterBox(element, metrics, contextId, configuration) {

        if(!framework.isReady()) {
            console.error("CounterBox object could not be created because framework is not loaded.");
            return;
        }
        this.element = $(element);

        this.configuration = normalizeConfig(configuration);

        this.data = null;
        this.decimal = this.configuration.decimal;
        this.currentValue = 0;
        // container
        this.container = document.createElement('div');
        this.container.className = "com-widget com-counter";
        this.container.setAttribute("data-easing", this.configuration.changeeasing);
        this.container.style.background = this.configuration.background;
        // icon
        this.icon = document.createElement('div');
        this.icon.className = "com-icon blurable";
        var ico = document.createElement('i');
        ico.className = this.configuration.icon;
        ico.style.background = this.configuration.iconbackground;
        ico.style.color = this.configuration.iconcolor;
        this.icon.appendChild(ico);
        this.container.appendChild(this.icon);
        // value
        this.label = document.createElement('div');
        this.label.className = "com-label blurable";
        this.labn = document.createElement('strong');
        this.labn.className = "num";
        this.labn.style.color = this.configuration.countercolor;
        this.labn.innerHTML = this.configuration.initfrom;
        this.label.appendChild(this.labn);
        // label
        var labt = document.createElement('span');
        labt.innerHTML = this.configuration.label;
        labt.style.color = this.configuration.labelcolor;
        this.label.appendChild(labt);
        this.container.appendChild(this.label);

        element.appendChild(this.container);

        // extending widget
        framework.widgets.CommonWidget.call(this, false, element);

        this.observeCallback = function(event){

            if(event.event === 'loading') {
                this.startLoading();
            } else if(event.event === 'data') {
                this.endLoading(this.updateData.bind(this, event.data));
            }

        }.bind(this);

        framework.metrics.observe(metrics, this.observeCallback , contextId, 1);

    };

    CounterBox.prototype = new framework.widgets.CommonWidget(true);

    CounterBox.prototype.updateData = function(data) {
        this.data = data[Object.keys(data)[0]][0];

        var options = {
            useEasing : this.configuration.changeeasing,
            useGrouping : true,
            separator : '.',
            decimal : this.decimal,
            prefix : '' ,
            suffix : ''
        };

        var cntr = new countUp(this.labn, this.currentValue, this.data.values[0], this.configuration.decimal, this.configuration.changetime, options);
        this.currentValue = this.data.values[0];
        cntr.start();

    };

    CounterBox.prototype.delete = function() {

        //Stop observing for data changes
        framework.metrics.stopObserve(this.observeCallback);

        //Clear DOM
        $(this.container).empty();
        this.element.empty();

    };

    window.framework.widgets.CounterBox = CounterBox;

})();