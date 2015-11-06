/*
#-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
  This file is part of the Smart Developer Hub Project:
    http://www.smartdeveloperhub.org
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
*/
require.config({
    baseUrl: "/",
    //enforceDefine: true,
    map: {
        '*': {
            'css': '/assets/js/requirejs/css.min.js' // or whatever the path to require-css is
        }
    },
    paths: {
        'bootstrap': "/assets/js/bootstrap/bootstrap.min",
        'jquery': '/sdh-framework/lib/jquery/jquery-2.1.3.min',
        'd3': "/sdh-framework/lib/d3/d3.min",
        'nvd3': "/sdh-framework/lib/nvd3/nv.d3.min",
        'joinable': "/sdh-framework/lib/joinable/joinable",
        'moment': "/sdh-framework/lib/moment/moment",
        'framework': "/sdh-framework/framework",
        'headerHandler': "/assets/js/header/headerHandler",
        'datatables' : '/sdh-framework/lib/jquery/datatables/js/jquery.dataTables',
        'widgetCommon': '/sdh-framework/framework.widget.common',
        'gridstack': '/assets/libs/gridstack/gridstack.min',
        'lodash': '/assets/libs/lodash/lodash.min',
        'jquery-ui': '/assets/libs/jquery-ui'
    },
    shim : {
        'bootstrap' : {
            exports: "jQuery.fn.popover",
            deps : ['jquery']
        },
        'framework': {
            deps :['jquery']
        },
        'd3': {
            exports: 'd3',
            deps: ['jquery']
        },
        'joinable': {
            deps: ['jquery']
        },
        'nvd3': {
            exports: 'nv',
            deps: ['d3']
        },
        'headerHandler': {
            deps: ['jquery']
        },
        'widgetCommon': {
            deps: ['framework']
        }
    }
});

define(function(require, exports, module) {

    require(["jquery", 'gridstack', 'css!/assets/libs/gridstack/gridstack.min.css'], function() {

        $(document).ready(function() {

            //Show the page container
            $('body').removeClass('hidd');
            $(".page-container").show();
            $("footer.footer-container").show();

            var options = {
                cell_height: 80,
                vertical_margin: 10
            };
            $('.grid-stack').gridstack(options);

        });

    });
});