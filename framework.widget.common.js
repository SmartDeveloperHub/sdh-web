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
        this.callback = null;
        this._common = {};
        this._common.container = container;
        this._common.loadingContainer = document.createElement('div');
        this._common.loadingContainer.className ='loadingContainer';
        var loadingLayer = document.createElement('div');
        loadingLayer.className ='loadingLayer';
        var spinner = document.createElement('i');
        spinner.className ='fa fa-spinner fa-pulse';

        this._common.loadingContainer.appendChild(loadingLayer);
        loadingLayer.appendChild(spinner);
        this._common.container.appendChild(this._common.loadingContainer);
        this._common.loadingLayer = loadingLayer;

        this.restoreContainerHandler = function restoreContainerHandler(e) {
            this._common.loadingLayer.removeEventListener('transitionend', this.restoreContainerHandler);
            $(this._common.container).removeClass('blurMode');
            if (typeof this.callback == 'function') {
                this.callback();
            }
        }.bind(this);
	};

    var oldContainerClass;

    CommonWidget.prototype.startLoading = function startLoading() {
        var wsize = this._common.container.getBoundingClientRect();
        // center the spinner vertically because a responsive 
        // widget can change it height dynamically
        this._common.loadingLayer.style.lineHeight = wsize.height + 'px';
        this._common.loadingContainer.style.height = wsize.height + 'px';
        this._common.loadingContainer.style.width = wsize.width + 'px';
        //this._common.loadingContainer.style.top = wsize.top + 'px';// Not necesary with bootstrap
        this._common.loadingContainer.style.left = 'auto';
        $(this._common.container).addClass('blurMode');
        $(this._common.loadingLayer).addClass('on');
    };

    CommonWidget.prototype.endLoading = function endLoading(callback) {
        this.callback = callback;
        if($(this._common.loadingLayer).hasClass('on')) {
            this._common.loadingLayer.addEventListener('transitionend', this.restoreContainerHandler);
            $(this._common.loadingLayer).removeClass('on');
        }
    };

	window.framework.widgets.CommonWidget = CommonWidget;
})();