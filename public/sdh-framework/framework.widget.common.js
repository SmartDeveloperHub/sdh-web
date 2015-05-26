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
      contributors: Alejandro Vera (alejandro.vera@centeropenmiddleware.com ),
                    Carlos Blanco. (carlos.blanco@centeropenmiddleware.com)
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
*/

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
        this.isloading = 0;
        this.callback = null;
        this.callbackList = [];
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
        this.isloading += 1;
        if (this.isloading > 1) {
            return;
        }
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

    /*
    The transitionend event doesn't fire if the transition is aborted before
    the transition is completed because either the element is made display: none
    or the animating property's value is changed.
    */
    CommonWidget.prototype.endLoading = function endLoading(callback) {
        this.isloading -= 1;
        if(this.isloading == 0) {
            this.callback = callback;
            this._common.loadingLayer.addEventListener('transitionend', this.restoreContainerHandler);
            setTimeout(function() {
                $(this._common.loadingLayer).removeClass('on')
            }.bind(this), 50);
        } else {
            console.log('discarding data...');
        }
    };

	window.framework.widgets.CommonWidget = CommonWidget;
})();
