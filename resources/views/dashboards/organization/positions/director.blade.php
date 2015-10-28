{{--
    User dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "sdh-framework/framework.widget.rangeNv",
    "sdh-framework/framework.widget.counterbox",
    "sdh-framework/framework.widget.scatter",
    "sdh-framework/framework.widget.table",
    "css!sdh-framework/lib/QTip/jquery.qtip.css",
    "sdh-framework/framework.widget.linesChart",
    "css!sdh-framework/framework.widget.linesChart.css",
    "sdh-framework/framework.widget.liquidgauge",
    "sdh-framework/framework.widget.piechart",
    "sdh-framework/framework.widget.timebar",
    "css!sdh-framework/framework.widget.timebar.css",
    "sdh-framework/lib/cytoscape/arbor",
    "sdh-framework/framework.widget.cytoChart2",
    "sdh-framework/framework.widget.cytoChart_old",
    "css!sdh-framework/framework.widget.cytoChart2.css",
    "css!assets/css/dashboards/director-dashboard",
    "http://crypto-js.googlecode.com/svn/tags/3.0.2/build/rollups/aes.js"
    ]
@stop

@section('html')
    <div id="metricsSect" class="row">
        <div id="metTitRow" class="row titleRow">
            <span id="metricsTitIco" class="titleIcon octicon octicon-dashboard"></span>
            <span id="metricsTitLabel" class="titleLabel">Metrics</span>
        </div>
        <div id="metricBoxes"class="row">
            <div class="row">
                <div id="products-ctr" class="boxCounter col-sm-3"></div>
                <div id="team-members-ctr" class="boxCounter col-sm-3"></div>
                <div id="personnel-cost-ctr" class="boxCounter col-sm-3"></div>
                <div id="releases-ctr" class="boxCounter col-sm-3"></div>
            </div>
            <div class="row">
                <div id="avg-health-ctr" class="boxCounter col-sm-3"></div>
                <div id="avg-team-ctr" class="boxCounter col-sm-3"></div>
                <div id="contributors-ctr" class="boxCounter col-sm-3"></div>
                <div id="companies-ctr" class="boxCounter col-sm-3"></div>
            </div>
        </div>
    </div>

    <div id="productsSect" class="row">
        <div id="prodTitRow" class="row titleRow">
            <span id="productsTitIco" class="titleIcon fa fa-industry"></span>
            <span id="productsTitLabel" class="titleLabel">Products</span>
        </div>
        <div class="row treeChartBox">
            <div id="cytograph1" class="col-sm-4 col-centered"></div>
            <div id="cytograph2" class="col-sm-4 col-centered"></div>
            <div id="cytograph3" class="col-sm-4 col-centered"></div>
        </div>
        <div class="row">
            <div class="row" id="managers-graph"></div>
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <div id="scatter-plot" class="widget"></div>
                </div>
                <div class="col-sm-10"></div>
            </div>
            <!-- ADD -->
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div id="products-table" class="widget"></div>
            </div>
            <div class="col-sm-8">
                <div id="releases-chart" class="widget"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="peplTitRow" class="row titleRow">
            <span id="peopleTitIco" class="titleIcon fa fa-users"></span>
            <span id="peopleTitIco" class="titleLabel">Team members</span>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div id="team-members-lines" class="widget"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div id="team-members-pie" class="widget"></div>
            </div>
            <div class="col-sm-4">
                <div id="team-members-table" class="widget"></div>
            </div>
        </div>
    </div>

@stop

@section('script')
/* <script>*/

    function _() {

        //Contexts used in this dashboard
        var orgCtx = "org-context";
        var timeCtx = "time-context";
        var productsCtx = "products-context";

        //Show header chart and set titles
        setTitle("Home");
        setSubtitle("Director");
        showHeaderChart();

        var env = framework.dashboard.getEnv();
        console.log(env);
        framework.data.updateContext(orgCtx, {oid: env['oid']});


        // --------------------------------- UPPER SELECTOR RANGENV --------------------------------------
        var rangeNv_dom = document.getElementById("fixed-chart");
        var rangeNv_metrics = [
            {
                id: 'orgcommits', //TODO: director activity metric
                max: 101,
                aggr: 'avg'
            }
        ];
        var rangeNv_configuration = {
            ownContext: timeCtx,
            isArea: true,
            showLegend: false,
            interpolate: 'monotone',
            showFocus: false,
            height: 140,
            duration: 500,
            colors: ["#004C8B"],
            axisColor: "#004C8B"
        };

        var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [orgCtx], rangeNv_configuration);
        $(rangeNv).on("CONTEXT_UPDATED", function () {
            $(rangeNv).off("CONTEXT_UPDATED");

            //Load widgets
            loadTimeDependentWidgets();

            // Hide the loading animation
            finishLoading();
        });


        var loadTimeDependentWidgets = function loadTimeDependentWidgets() {

            // --------------------------------------- PRODUCTS --------------------------------------------
            var products_dom = document.getElementById("products-ctr");
            var products_metrics = [{
                id: 'orgcommits',  //TODO: Total Products
                max: 1,
                aggr: 'sum'
            }];
            var products_conf = {
                label: 'Products',
                decimal: 0,
                icon: 'fa fa-industry',
                iconbackground: '#F75333',
                background: 'transparent'
            };
            var products = new framework.widgets.CounterBox(products_dom, products_metrics, [orgCtx, timeCtx], products_conf);

            // ------------------------------------ TEAM MEMBERS -------------------------------------------
            var team_members_dom = document.getElementById("team-members-ctr");
            var team_members_metrics = [{
                id: 'orgcommits', //TODO: Total Team Members
                max: 1,
                aggr: 'sum'
            }];
            var team_members_conf = {
                label: 'Team members',
                decimal: 0,
                icon: 'octicon octicon-organization',
                iconbackground: '#019640',
                background: 'transparent'
            };
            var team_members = new framework.widgets.CounterBox(team_members_dom, team_members_metrics, [orgCtx, timeCtx], team_members_conf);

            // ---------------------------------------- RELEASES -------------------------------------------
            var some1_dom = document.getElementById("releases-ctr");
            var some1_metrics = [{
                id: 'orgcommits',  //TODO: Nº Releases: total builds passed in master branch
                max: 1,
                aggr: 'sum'
            }];
            var some1_conf = {
                label: 'Releases',
                decimal: 0,
                icon: 'fa-flag-checkered',
                iconbackground: '#8A1978',
                background: 'transparent'
            };
            var some1 = new framework.widgets.CounterBox(some1_dom, some1_metrics, [orgCtx, timeCtx], some1_conf);

            // ------------------------------------------ PERSONEL COST ----------------------------------------
            var some2_dom = document.getElementById("personnel-cost-ctr");
            var some2_metrics = [{
                id: 'orgcommits',  //TODO: Total de coste por team member 25*nºmembers * (dias del rango seleccionado)
                max: 1,
                aggr: 'sum'
            }];
            var some2_conf = {
                label: 'Personnel Cost',
                decimal: 0,
                icon: 'fa-eur',
                iconbackground: '#EE7529',
                background: 'transparent'
            };
            var some2 = new framework.widgets.CounterBox(some2_dom, some2_metrics, [orgCtx, timeCtx], some2_conf);

            // ------------------------------------------ CONTRIBUTORS ----------------------------------------
            var some2_dom = document.getElementById("contributors-ctr");
            var some2_metrics = [{
                id: 'orgcommits',  //TODO: Número de externos (contributors) o  % externos
                max: 1,
                aggr: 'sum'
            }];
            var some2_conf = {
                label: 'External Contributors',
                decimal: 0,
                icon: 'fa-user',
                iconbackground: '#737373',
                background: 'transparent'
            };
            var some2 = new framework.widgets.CounterBox(some2_dom, some2_metrics, [orgCtx, timeCtx], some2_conf);

            // --------------------------------- EXTERNAL COMPANIES --------------------------------
            var some2_dom = document.getElementById("companies-ctr");
            var some2_metrics = [{
                id: 'orgcommits',  //TODO: Número de externos (contributors) o  % externos
                max: 1,
                aggr: 'sum'
            }];
            var some2_conf = {
                label: 'External Companies',
                decimal: 0,
                icon: 'fa-globe',
                iconbackground: 'rgb(0, 75, 139)',
                background: 'transparent'
            };
            var some2 = new framework.widgets.CounterBox(some2_dom, some2_metrics, [orgCtx, timeCtx], some2_conf);

            // ------------------------------- AVG TEAM MEMBERS PER PRODUCT-------------------------------------
            var avgteam_dom = document.getElementById("avg-team-ctr");
            var avgteam_metrics = [{
                id: 'orgcommits',  //TODO: Total Products
                max: 1,
                aggr: 'sum'
            }];
            var avgteam_conf = {
                label: 'Team Members Per Product',
                decimal: 0,
                icon: 'fa-users',
                iconbackground: '#6895BA',
                background: 'transparent'
            };
            var avgTeam = new framework.widgets.CounterBox(avgteam_dom, avgteam_metrics, [orgCtx, timeCtx], avgteam_conf);

            // ------------------------------------ AVG HEALTH PER PRODUCT -------------------------------------------
            var avghealth_dom = document.getElementById("avg-health-ctr");
            var avghealth_metrics = [{
                id: 'orgcommits', //TODO: Total Team Members
                max: 1,
                aggr: 'sum'
            }];
            var avghealth_conf = {
                label: 'Health Per Product',
                decimal: 0,
                icon: 'fa-heart',
                iconbackground: '#29BB67',
                background: 'transparent'
            };
            var avgHealth = new framework.widgets.CounterBox(avghealth_dom, avghealth_metrics, [orgCtx, timeCtx], avghealth_conf);



            function getRandomGravatar(size) {
                return "https://secure.gravatar.com/avatar/"+CryptoJS.MD5(""+Math.random())+"?d=identicon&s="+size;
            }

            // CYTOCHART1 INITIALIZATION
            // TODO get
            // product managers del director
            // mejores products de los  3 mejores P.Managers
            // Info de cada uno de los productos
            var cytograph1_dom = document.getElementById("cytograph1");
            // TODO add ~productManager metrics
            var theProductManagerId = 1; // id del usuario P.Manager?
            var mainNode = 1;
            var productsAux = {
                1:{
                    'name': "P.ManagerA",
                    'avatar': getRandomGravatar(128)
                },
                2:{
                    'name': "Product_a",
                    'avatar': getRandomGravatar(128)
                },
                3:{
                    'name': "Product_b",
                    'avatar': getRandomGravatar(128)
                },
                4:{
                    'name': "Product_c",
                    'avatar': getRandomGravatar(128)
                },
                5:{
                    'name': "Product_d",
                    'avatar': getRandomGravatar(128)
                }
            };
            var cytograph1_metrics = [];
            // Add edges
            var cytograph1_configuration = {
                'nodes': [],
                'edges': [
                    { source: 'P.ManagerA', target: 'Product_a' },
                    { source: 'P.ManagerA', target: 'Product_b' },
                    { source: 'P.ManagerA', target: 'Product_c' },
                    { source: 'P.ManagerA', target: 'Product_d' }
                ],
                "mainNode":"P.ManagerA"
            };
            for (var prId in productsAux) {
                // Add Metric
                var aux = {
                    max: 1,
                    aggr: 'sum',
                    prid: prId
                };
                var productMetricId = framework.utils.resourceHash('produsers', aux);
                if (prId == mainNode) {
                    productMetricId = "_static_";
                }
                aux['id']= 'produsers';
                cytograph1_metrics.push(aux);
                // Add Node
                cytograph1_configuration.nodes.push(
                    {
                        id: productsAux[prId].name,
                        avatar:productsAux[prId].avatar,
                        shape:"ellipse",
                        volume: productMetricId,
                        metric: 'produsers',
                        tooltip:""
                    }
                )
            }
            //console.log("!!!cytograph testing!!!: " + JSON.stringify(cytograph1_configuration));
            var cytograph1 = new framework.widgets.CytoChart_old(cytograph1_dom, cytograph1_metrics,
                    [orgCtx, timeCtx], cytograph1_configuration);

            // CYTOCHART2 INITIALIZATION
            var cytograph2_dom = document.getElementById("cytograph2");
            // TODO add ~productManager metrics
            var cytograph2_metrics = [
                {
                    id: 'orgdevelopers',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'orgcommits',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'orgbranches',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'orgrepositories',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'orgbuilds',
                    max: 1,
                    aggr: 'sum'
                },
            ];
            var cytograph2_configuration = {
                nodes:[
                    { 'id': 'PManager', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgdevelopers"},
                    { 'id': 'product1', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgcommits"},
                    { 'id': 'product2', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgbranches"},
                    { 'id': 'product3', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgrepositories"},
                    { 'id': 'product4', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgbuilds"}
                ],
                edges: [
                    { source: 'PManager', target: 'product1' },
                    { source: 'PManager', target: 'product2' },
                    { source: 'PManager', target: 'product3' },
                    { source: 'PManager', target: 'product4' }
                ],
                mainNode: 'PManager',
            };
            //console.log("!!!cytograph testing 2 !!!: " + JSON.stringify(cytograph2_configuration));
            var cytograph2 = new framework.widgets.CytoChart_old(cytograph2_dom, cytograph2_metrics,
                    [orgCtx, timeCtx], cytograph2_configuration);

            // CYTOCHART3 INITIALIZATION
            var cytograph3_dom = document.getElementById("cytograph3");
            // TODO add ~productManager metrics
            var cytograph3_metrics = [
                {
                    id: 'orgdevelopers',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'orgcommits',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'orgbranches',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'orgrepositories',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'orgbuilds',
                    max: 1,
                    aggr: 'sum'
                },
            ];
            var cytograph3_configuration = {
                nodes:[
                    { 'id': 'PManager', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgdevelopers"},
                    { 'id': 'product1', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgcommits"},
                    { 'id': 'product2', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgbranches"},
                    { 'id': 'product3', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgrepositories"},
                    { 'id': 'product4', 'avatar':getRandomGravatar(128), 'shape': 'ellipse', metric:"orgbuilds"}
                ],
                edges: [
                    { source: 'PManager', target: 'product1' },
                    { source: 'PManager', target: 'product2' },
                    { source: 'PManager', target: 'product3' },
                    { source: 'PManager', target: 'product4' }
                ],
                mainNode: 'PManager',
            };
            var cytograph3 = new framework.widgets.CytoChart_old(cytograph3_dom, cytograph3_metrics,
                    [orgCtx, timeCtx], cytograph3_configuration);

            // ------------------------------------------ SCATTER PLOT -------------------------------------------
            var scatter_dom = document.getElementById("scatter-plot");
            var scatter_test_cntx = "test_cntx";
            framework.data.updateContext(scatter_test_cntx, {rid: [1,2,3,4,5]}); //TODO: this wont be needed
            var scatter_metrics = [ //TODO: required metrics
                {
                    id: 'repocommits',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'repodevelopers',
                    max: 1,
                    aggr: 'sum'
                },
                {
                    id: 'repopassedexecutions',
                    max: 1,
                    aggr: 'sum'
                }
            ];
            var scatter_conf = {
                color: function(data) {
                    var color = d3.scale.linear()
                            .domain([0, 0.5, 1])
                            .range(["red", "yellow", "green"]);
                    return color(Math.random());
                },
                size: function(data) {
                    return Math.random();
                },
                shape: function(data) {
                    return 'circle';
                },
                x: function(data) {
                    //return (data['repodevelopers']['values'][0] > 100 ? data['repodevelopers']['values'][0]/10 : data['repodevelopers']['values'][0]);
                    return Math.random();
                },
                xAxisLabel: "Time to market",
                y: function(data) {
                    //return (data['repopassedexecutions']['values'][0] > data['repopassedexecutions']['values'][0]/10 ? 100 : data['repopassedexecutions']['values'][0]);
                    return Math.random();
                },
                xAxisTicks: 3,
                yAxisLabel: "Quality",
                height: 390,
                groupBy: 'rid',
                labelFormat: '¬_D.repocommits.info.rid.name¬',
                showDistX: false,
                showDistY: false,
                xDomain: [0,1],
                yDomain: [0,1],
                pointDomain: [0,1],
                clipEdge: true,
                tooltip: "<div>" +
                "<img class='img-responsive center-block' height='60' width='60' src=\"¬_D.data.repocommits.info.rid.avatar¬\" />" +
                "<h3>¬_D.data.repocommits.info.rid.name¬</h3>" +
                "<h4>Quality: ¬Math.round(_D.y * 100)/100¬</h4>" +
                "<h4>Time to market: ¬_D.x¬</h4>" +
                "</div>",
                image: "¬_D.data.repocommits.info.rid.avatar¬"
            };

            var scatter = new framework.widgets.Scatter(scatter_dom, scatter_metrics, [orgCtx, timeCtx, scatter_test_cntx], scatter_conf);

            //  ----------------------------------- PRODUCTS TABLE ------------------------------------------
            var table_dom = document.getElementById("products-table");
            var table_metrics = ['repolist']; //TODO: choose resource
            var table_configuration = {
                columns: [
                    {
                        label: "",
                        /*link: {
                            img: "avatar", //or label
                            href: "repository",
                            env: [
                                {
                                    property: "repositoryid",
                                    as: "rid"
                                },
                                {
                                    property: "name",
                                    as: "name"
                                }
                            ]
                        },*/
                        width: "40px"
                    },
                    {
                        label: "",
                        property: "name"
                    }
                ],
                updateContexts: [
                    {
                        id: productsCtx,
                        filter: [
                            {
                                property: "repositoryid", //TODO
                                as: "rid"
                            }
                        ]
                    }
                ],
                selectable: true,
                minRowsSelected: 1,
                maxRowsSelected: 6,
                filterControl: true,
                initialSelectedRows: 5,
                showHeader: false
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [orgCtx, timeCtx], table_configuration);

            //  ----------------------------------- RELEASES LINES WIDGET ------------------------------------------
            var releasesLines_dom = document.getElementById("releases-chart");

            var releasesLines_metrics = [{
                id: 'repocommits',
                max: 100
            }];

            var releasesLines_configuration = {
                height: 100,
                color: function(val) {
                    var color = d3.scale.linear()
                            .domain([0, 0.5, 1])
                            .range(["red", "yellow", "green"]);
                    return color(val);
                },
                tooltip: '<h3>Value: ¬Math.round(_E.value * 100)/100¬</h3>' +
                         '<h3>Date: ¬moment(_E.time).toString()¬ </h3>',
                legend: ['Broken', 'Success']
            };
            var releasesLines = new framework.widgets.TimeBar(releasesLines_dom, releasesLines_metrics, [orgCtx, timeCtx, productsCtx], releasesLines_configuration);

            // ----------------------------- TEAM MEMBERS LINES CHART ----------------------------------
            var team_members_lines_dom = document.getElementById("team-members-lines");
            var team_members_lines_metrics = [
                {
                    id: 'orgcommits',
                    max: 30
                },
                {
                    id: 'orgcommits',
                    max: 30,
                    aggr: "avg"
                }
            ];
            var team_members_lines_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: '%data.info.title%',
                colors: ["#2876B8", "#C0485E"],
                area: false,
                _demo: true // Only for demo
            };
            var team_members_lines = new framework.widgets.LinesChart(team_members_lines_dom, team_members_lines_metrics,
                    [orgCtx, timeCtx], team_members_lines_configuration);


            // SKILLS STAR CHART
            var team_members_pie_dom = document.getElementById("team-members-pie");
            var team_members_pie_metrics = [
                {
                    id: 'orgcommits',
                    max: 1,
                    aggr: "sum"
                },
                {
                    id: 'orgcommits',
                    max: 1,
                    aggr: "sum"
                },
                {
                    id: 'orgcommits',
                    max: 1,
                    aggr: "sum"
                },
                {
                    id: 'orgcommits',
                    max: 1,
                    aggr: "sum"
                }];
            var team_members_pie_configuration = {
                height: 300,
                labelFormat: "%resourceId%"
            };
            var team_members_pie = new framework.widgets.PieChart(team_members_pie_dom, team_members_pie_metrics,
                    [orgCtx, timeCtx], team_members_pie_configuration);


            //  ------------------------------ PRODUCT MANAGERS TABLE --------------------------------------
            var team_members_table_dom = document.getElementById("team-members-table");
            var team_members_table_metrics = ['repolist']; //TODO: choose resource
            var team_members_table_configuration = {
                columns: [
                    {
                        label: "",
                        /*link: {
                         img: "avatar", //or label
                         href: "repository",
                         env: [
                         {
                         property: "repositoryid",
                         as: "rid"
                         },
                         {
                         property: "name",
                         as: "name"
                         }
                         ]
                         },*/
                        width: "40px"
                    },
                    {
                        label: "",
                        property: "name"
                    }
                ],
                updateContexts: [
                    {
                        id: productsCtx,
                        filter: [
                            {
                                property: "repositoryid", //TODO
                                as: "rid"
                            }
                        ]
                    }
                ],
                selectable: true,
                minRowsSelected: 1,
                maxRowsSelected: 6,
                filterControl: true,
                initialSelectedRows: 5,
                showHeader: false
            };
            var team_members_table = new framework.widgets.Table(team_members_table_dom, team_members_table_metrics, [orgCtx, timeCtx], team_members_table_configuration);


            //TODO: use the liquid gauge
            /*var test_dom = document.getElementById("releases-chart");
            var test_metrics = [
                {
                    id: 'orgcommits',
                    max: 1
                }
            ];
            var test_configuration = {
                minValue: 0,
                maxValue: 30000
            };
            var test = new framework.widgets.LiquidGauge(test_dom, test_metrics,
                    [orgCtx, timeCtx], test_configuration);
            */


        };
    }

@stop
