(function() {

    // CHECK D3
    if(typeof d3 === 'undefined') {
        console.error("RangeChart could not be loaded because d3 did not exist.");
        return;
    }

	var CommonWidget = function CommonWidget(extending) {
		if (extending === true) {
            return;
        }
		this.loadingContainer = document.createElement('div');
        this.loadingContainer.className ='loadingContainer';
        var loadingLayer = document.createElement('div');
        loadingLayer.className ='loadingLayer off';
        var spinner = document.createElement('i');
        spinner.className ='fa fa-spinner fa-pulse';

        this.loadingContainer.appendChild(loadingLayer);
        loadingLayer.appendChild(spinner);
		this.container.parentElement.appendChild(this.loadingContainer);
        this.loadingLayer = loadingLayer;
	};

    var oldContainerClass;

    CommonWidget.prototype.startLoading = function() {
        if (this.loadingLayer.className == 'loadingLayer off') {
            var wsize = this.container.getBoundingClientRect();
            this.loadingLayer.style.lineHeight = wsize.height + 'px';
            this.loadingContainer.style.height = wsize.height + 'px';
            this.loadingContainer.style.width = wsize.width + 'px';
            //this.loadingContainer.style.top = wsize.top + 'px';// Not necesary with bootstrap
            this.loadingContainer.style.lineHeight = wsize.left + 'px';
            oldContainerClass = this.container.className;
            this.container.className = oldContainerClass +' blurMode';
        	this.loadingLayer.className ='loadingLayer on';
        }
    };

    CommonWidget.prototype.endLoading = function() {
        this.container.className = oldContainerClass;
		this.loadingLayer.className ='loadingLayer off';

    };

	window.framework.widgets.CommonWidget = CommonWidget;
})();