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
            'css': 'require-css' // or whatever the path to require-css is
        }
    },
    paths: {
        'require-css': '/assets/vendor/require-css/css.min',
        'framework': "/sdh-framework/framework",
        'headerHandler': "/assets/js/header/headerHandler",
        'widgetCommon': '/sdh-framework/framework.widget.common',
        'bootstrap': "/assets/vendor/bootstrap/dist/js/bootstrap.min",
        'backbone': '/sdh-framework/lib/backbone/backbone-min',
        'underscore': '/sdh-framework/lib/underscore/underscore-min',
        'd3': "/sdh-framework/lib/d3/d3.min",
        'nvd3': "/sdh-framework/lib/nvd3/nv.d3.min",
        'jquery': '/sdh-framework/lib/jquery/jquery-2.1.3.min',
        'jquery-ui': '/assets/vendor/jquery-ui/ui',
        'jquery-qtip': '/sdh-framework/lib/QTip/jquery.qtip',
        'joinable': "/sdh-framework/lib/joinable/joinable",
        'moment': "/sdh-framework/lib/moment/moment",
        'datatables' : '/sdh-framework/lib/jquery/datatables/js/jquery.dataTables',
        'lodash': '/assets/vendor/lodash/lodash.min',
        'gridstack': '/assets/vendor/gridstack/dist/gridstack.min',
        'joint': '/sdh-framework/lib/joint/joint.min',
        'cytoscape': '/sdh-framework/lib/cytoscape/cytoscape.min',
        'cytoscape-qtip': '/sdh-framework/lib/cytoscapeQTip/cytoscape-qtip',

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
            deps: ['framework', 'css!sdh-framework/framework.widget.common.css']
        },
        'backbone': {
            deps: ['underscore']
        },
        'joint': {
            deps: ['jquery', 'lodash', 'backbone']
        },
        'jquery-qtip': {
            deps: ['jquery']
        },
        'cytoscape': {
            exports: 'cytoscape',
            deps: ['jquery']
        },
        'cytoscape-qtip': {
            exports: 'cytoscape-qtip',
            deps: ['jquery', 'jquery-qtip', 'cytoscape']
        }
    }
});