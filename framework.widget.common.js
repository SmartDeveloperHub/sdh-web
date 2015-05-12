(function() {

    // CHECK D3
    if(typeof d3 === 'undefined') {
        console.error("RangeChart could not be loaded because d3 did not exist.");
        return;
    }


	var CommonWidget = function CommonWidget(extending, container) {
		if (extending === true) {
            return;
        }
        this._common = {};
        this._common.container = container;
        this._common.loadingContainer = document.createElement('div');
        this._common.loadingContainer.className ='loadingContainer';
        var loadingLayer = document.createElement('div');
        loadingLayer.className ='loadingLayer off';
        var spinner = document.createElement('i');
        spinner.className ='fa fa-spinner fa-pulse';

        this._common.loadingContainer.appendChild(loadingLayer);
        loadingLayer.appendChild(spinner);
        this._common.container.appendChild(this._common.loadingContainer);
        this._common.loadingLayer = loadingLayer;
	};

    var oldContainerClass;

    CommonWidget.prototype.startLoading = function() {
        if (this._common.loadingLayer.className == 'loadingLayer off') {
            var wsize = this._common.container.getBoundingClientRect();
            this._common.loadingLayer.style.lineHeight = wsize.height + 'px';
            this._common.loadingContainer.style.height = wsize.height + 'px';
            this._common.loadingContainer.style.width = wsize.width + 'px';
            //this._common.loadingContainer.style.top = wsize.top + 'px';// Not necesary with bootstrap
            this._common.loadingContainer.style.lineHeight = wsize.left + 'px';
            oldContainerClass = this._common.container.className;
            this._common.container.className = oldContainerClass +' blurMode';
            this._common.loadingLayer.className ='loadingLayer on';
        }
    };

    CommonWidget.prototype.endLoading = function() {
        this._common.container.className = oldContainerClass;
        this._common.loadingLayer.className ='loadingLayer off';

    };

	window.framework.widgets.CommonWidget = CommonWidget;
})();