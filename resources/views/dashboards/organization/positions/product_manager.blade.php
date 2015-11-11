{{--
    Product manager dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "vendor/sdh-framework/framework.widget.rangeNv",
    "vendor/sdh-framework/framework.widget.counterbox",
    "vendor/sdh-framework/framework.widget.scatter",
    "vendor/sdh-framework/framework.widget.table",
    "css!vendor/qtip2/jquery.qtip.min.css",
    "vendor/sdh-framework/framework.widget.linesChart",
    "css!vendor/sdh-framework/framework.widget.linesChart.css",
    "vendor/sdh-framework/framework.widget.liquidgauge",
    "vendor/sdh-framework/framework.widget.piechart",
    "vendor/sdh-framework/framework.widget.timebar",
    "css!vendor/sdh-framework/framework.widget.timebar.css",
    "vendor/cytoscape/lib/arbor",
    "vendor/sdh-framework/framework.widget.cytoChart2",
    "css!vendor/sdh-framework/framework.widget.cytoChart2.css",
    "vendor/sdh-framework/framework.widget.multibar",
    "css!assets/css/dashboards/product_manager-dashboard",
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
                <div id="developers-ctr" class="boxCounter col-sm-3"></div>
                <div id="repositories-ctr" class="boxCounter col-sm-3"></div>
                <div id="projects-ctr" class="boxCounter col-sm-3"></div>
            </div>
            <div class="row">
                <div id="empty-ctr1" class="boxCounter col-sm-3"></div>
                <div id="avg-developers-ctr" class="boxCounter col-sm-3"></div>
                <div id="avg-repositories-ctr" class="boxCounter col-sm-3"></div>
                <div id="empty-ctr2" class="boxCounter col-sm-3"></div>
            </div>
        </div>
    </div>

    <div id="productsSect" class="row">
        <div id="prodTitRow" class="row titleRow">
            <span id="productsTitIco" class="titleIcon fa fa-industry"></span>
            <span id="productsTitLabel" class="titleLabel">Products</span>
        </div>
        <div class="row scatterBox">
            <div id="scatter-plot-subtitle" class="row subtitleRow">
                <span id="scatter-plot-stitle-ico" class="subtitleIcon fa fa-balance-scale"></span>
                <span id="scatter-plot-stitle-label" class="subtitleLabel">Products analysis</span>
            </div>
            <div class="row">
                <div class="col-sm-1 auxCol"></div>
                <div class="col-sm-10">
                    <div id="scatter-plot" class="widget"></div>
                </div>
                <div class="col-sm-1 auxCol"></div>
            </div>
        </div>
        <div class="row releasesBox">
            <div class="col-sm-4">
                <div id="products-table-subtitle" class="row subtitleRow">
                    <span id="products-table-stitle-ico" class="subtitleIcon fa fa-hand-pointer-o"></span>
                    <span id="products-table-stitle-label" class="subtitleLabel">Product Selector</span>
                </div>
                <div id="products-table" class="widget"></div>
            </div>
            <div class="col-sm-8">
                <div id="releases-chart-subtitle" class="row subtitleRow">
                    <span id="releases-chart-stitle-ico" class="subtitleIcon fa fa-hourglass-half"></span>
                    <span id="releases-chart-stitle-label" class="subtitleLabel">Releases History</span>
                </div>
                <div class="row">
                    <div id="releases-chart" class="widget"></div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div id="liquid1-chart-subtitle" class="row subtitleRow">
                            <span id="liquid1-chart-stitle-ico" class="subtitleIcon fa fa-link"></span>
                            <span id="liquid1-chart-stitle-label" class="subtitleLabel">Success Time</span>
                        </div>
                        <div class="row">
                            <div id="liquid-1-chart" class="widget"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="liquid2-chart-subtitle" class="row subtitleRow">
                            <span id="liquid2-chart-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                            <span id="liquid2-chart-stitle-label" class="subtitleLabel">Broken Time</span>
                        </div>
                        <div class="row">
                            <div id="liquid-2-chart" class="widget"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="peplTitRow" class="row titleRow">
            <span class="titleIcon titleIcon fa fa-users"></span>
            <span class="titleLabel">Team members</span>
        </div>
        <div class="row treeChartBox">
            <div id="cytograph1" class="col-sm-4 col-centered"></div>
            <div id="cytograph2" class="col-sm-4 col-centered"></div>
            <div id="cytograph3" class="col-sm-4 col-centered"></div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div id="team-members-lines" class="widget"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="row">
                    <div id="team-multibar-subtitle" class="row subtitleRow">
                        <span id="team-multibar-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                        <span id="team-multibar-stitle-label" class="subtitleLabel">Teams Comparison</span>
                    </div>
                    <div id="projects-roles-multibar" class="widget"></div>
                </div>
                <div class="row">
                    <div id="team-pie-subtitle" class="row subtitleRow">
                        <span id="team-pie-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                        <span id="team-pie-stitle-label" class="subtitleLabel">Total Member Roles</span>
                    </div>
                    <div id="team-members-pie" class="widget"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div id="members-table-subtitle" class="row subtitleRow">
                    <span id="members-table-stitle-ico" class="subtitleIcon fa fa-hand-pointer-o"></span>
                    <span id="members-table-stitle-label" class="subtitleLabel"> Projects Selector</span>
                </div>
                <div id="product-projects-table" class="widget"></div>
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
        var productByProjectCtx = "products-projects-context";

        //Show header chart and set titles
        setTitle("Home");
        setSubtitle("Product Manager");
        showHeaderChart();

        var env = framework.dashboard.getEnv();
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
                id: 'orgcommits',  //TODO: implement products metric
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

            // ------------------------------------ PROJECTS -------------------------------------------
            var team_members_dom = document.getElementById("projects-ctr");
            var team_members_metrics = [{
                id: 'orgcommits', //TODO: implement projects metric
                max: 1,
                aggr: 'sum'
            }];
            var team_members_conf = {
                label: 'Projects',
                decimal: 0,
                icon: 'fa fa-cubes',
                iconbackground: '#8A1978',
                background: 'transparent'
            };
            var team_members = new framework.widgets.CounterBox(team_members_dom, team_members_metrics, [orgCtx, timeCtx], team_members_conf);

            // ------------------------------------------ DEVELOPERS ----------------------------------------
            var developers_dom = document.getElementById("developers-ctr");
            var developers_metrics = [{
                id: 'orgcommits',  //TODO: implement developers metric
                max: 1,
                aggr: 'sum'
            }];
            var developers_conf = {
                label: 'Team members',
                decimal: 0,
                icon: 'octicon octicon-organization',
                iconbackground: '#019640',
                background: 'transparent'
            };
            var developers = new framework.widgets.CounterBox(developers_dom, developers_metrics, [orgCtx, timeCtx], developers_conf);

            // ---------------------------------- AVERAGE DEVELOPERS PER PROJECT ---------------------------------
            var avg_developers_dom = document.getElementById("avg-developers-ctr");
            var avg_developers_metrics = [{
                id: 'orgcommits',  //TODO: choose metric
                max: 1,
                aggr: 'sum'
            }];
            var avg_developers_conf = {
                label: 'Developers / project',
                decimal: 0,
                icon: 'octicon octicon-organization',
                iconbackground: '#EE7529',
                background: 'transparent'
            };
            var avg_developers = new framework.widgets.CounterBox(avg_developers_dom, avg_developers_metrics, [orgCtx, timeCtx], avg_developers_conf);

            // ----------------------------------- REPOSITORIES -------------------------------------------
            var repos_dom = document.getElementById("repositories-ctr");
            var repos_metrics = [{
                id: 'orgcommits',  //TODO: choose metric
                max: 1,
                aggr: 'sum'
            }];
            var repos_conf = {
                label: 'Repositories',
                decimal: 0,
                icon: 'octicon octicon-repo',
                iconbackground: 'rgb(0, 75, 139)',
                background: 'transparent'
            };
            var repos = new framework.widgets.CounterBox(repos_dom, repos_metrics, [orgCtx, timeCtx], repos_conf);

            // ------------------------------- AVERAGE REPOSITORIES PER PROJECT ---------------------------------------
            var avg_repositories_dom = document.getElementById("avg-repositories-ctr");
            var avg_repositories_metrics = [{
                id: 'orgcommits',  //TODO: choose metric
                max: 1,
                aggr: 'sum'
            }];
            var avg_repositories_conf = {
                label: 'Repositories / project',
                decimal: 0,
                icon: 'octicon octicon-repo',
                iconbackground: '#EE7529',
                background: 'transparent'
            };
            var avg_repositories = new framework.widgets.CounterBox(avg_repositories_dom, avg_repositories_metrics, [orgCtx, timeCtx], avg_repositories_conf);

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
                        link: {
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
                        },
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
                minRowsSelected: 0,
                maxRowsSelected: 1,
                filterControl: true,
                initialSelectedRows: 1,
                showHeader: false,
                alwaysOneSelected: true
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [orgCtx, timeCtx], table_configuration);

            //  ----------------------------------- RELEASES LINES WIDGET ------------------------------------------
            var releasesLines_dom = document.getElementById("releases-chart");

            var releasesLines_metrics = [{
                id: 'reporeleasestatus',
                max: 20
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

            //  ----------------------------------- LIQUID GAUGE 1 ------------------------------------------
            var test_dom = document.getElementById("liquid-1-chart");
            var test_metrics = [
                {
                    id: 'orgcommits',
                    max: 1
                }
            ];
            var test_configuration = {
                height: 200,
                minValue: 0,
                maxValue: 100,
                waveColor: '#8ACA17',
                textColor: '#4BAD06',
                circleColor: '#4BAD06',
                waveTextColor:'#DBF1B4'
            };
            var test = new framework.widgets.LiquidGauge(test_dom, test_metrics,
                    [orgCtx, timeCtx], test_configuration);

            //  ----------------------------------- LIQUID GAUGE 2 ------------------------------------------
            var test_dom = document.getElementById("liquid-2-chart");
            var test_metrics = [
                {
                    id: 'orgcommits',
                    max: 1
                }
            ];
            var test_configuration = {
                height: 200,
                minValue: 0,
                maxValue: 100,
                waveColor: '#E65538',
                textColor: '#8C1700',
                circleColor: '#8C1700',
                waveTextColor: '#FFC5B9'
            };
            var test = new framework.widgets.LiquidGauge(test_dom, test_metrics,
                    [orgCtx, timeCtx], test_configuration);

            // CYTOCHART CONFIG FOR PRODUCT MANAGER
            function configPManagerCytoChart(productsAux, theProductManagerId, edges) {
                var cytograph1_metrics = [];
                // Add edges
                var cytograph1_configuration = {
                    'nodes': [],
                    'edges': edges
                };
                for (var prId in productsAux) {
                    // Add Metric
                    var aux = {
                        max: 1,
                        aggr: 'sum',
                        prid: prId
                    };
                    var productMetricId = framework.utils.resourceHash('produsers', aux);
                    if (prId == theProductManagerId) {
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
                            tooltip:""
                        }
                    )
                }
                return {'config': cytograph1_configuration, 'metrics': cytograph1_metrics};
            };

            // CYTOCHART1 INITIALIZATION
            // TODO get
            // product managers del director
            // mejores products de los  3 mejores P.Managers
            // Info de cada uno de los productos
            var cytograph1_dom = document.getElementById("cytograph1");
            var theProductManagerId = 1;
            var edges = [
                { source: 'ProductA', target: 'Project1' },
                { source: 'ProductA', target: 'Project2' },
                { source: 'ProductA', target: 'Project3' },
                { source: 'ProductA', target: 'Project4' },
                { source: 'ProductA', target: 'Project5' }
            ];
            var productsAux = {
                1:{
                    'name': "ProductA",
                    'avatar': "assets/images/logo_bg.png"
                },
                2:{
                    'name': "Project1",
                    'avatar': "assets/images/CytoChartDemo/bp1.png"
                },
                3:{
                    'name': "Project2",
                    'avatar': "assets/images/CytoChartDemo/bp2.png"
                },
                4:{
                    'name': "Project3",
                    'avatar': "assets/images/CytoChartDemo/bp3.png"
                },
                5:{
                    'name': "Project4",
                    'avatar': "assets/images/CytoChartDemo/bp4.png"
                },
                6:{
                    'name': "Project5",
                    'avatar': "assets/images/CytoChartDemo/bp5.png"
                }
            };

            var configPM = configPManagerCytoChart(productsAux, theProductManagerId, edges);
            var cytograph1_metrics = configPM.metrics;
            var cytograph1_configuration = configPM.config;

            var cytograph1 = new framework.widgets.CytoChart2(cytograph1_dom, cytograph1_metrics,
                    [orgCtx, timeCtx], cytograph1_configuration);

            // CYTOCHART2 INITIALIZATION
            var cytograph2_dom = document.getElementById("cytograph2");
            var theProductManagerId = 1;
            var edges = [
                { source: 'ProductA', target: 'Project1' },
                { source: 'ProductA', target: 'Project2' },
                { source: 'ProductA', target: 'Project3' }
            ];
            var productsAux = {
                1:{
                    'name': "ProductA",
                    'avatar': "assets/images/logo_bg.png"
                },
                2:{
                    'name': "Project1",
                    'avatar': "assets/images/CytoChartDemo/bp1.png"
                },
                3:{
                    'name': "Project2",
                    'avatar': "assets/images/CytoChartDemo/bp2.png"
                },
                4:{
                    'name': "Project3",
                    'avatar': "assets/images/CytoChartDemo/bp3.png"
                }
            };

            var configPM = configPManagerCytoChart(productsAux, theProductManagerId, edges);
            var cytograph2_metrics = configPM.metrics;
            var cytograph2_configuration = configPM.config;

            var cytograph2 = new framework.widgets.CytoChart2(cytograph2_dom, cytograph2_metrics,
                    [orgCtx, timeCtx], cytograph2_configuration);

            // CYTOCHART3 INITIALIZATION
            var cytograph3_dom = document.getElementById("cytograph3");
            var theProductManagerId = 1;
            var edges = [
                { source: 'ProductA', target: 'Project1' },
                { source: 'ProductA', target: 'Project2' },
                { source: 'ProductA', target: 'Project3' },
                { source: 'ProductA', target: 'Project4' }
            ];
            var productsAux = {
                1:{
                    'name': "ProductA",
                    'avatar': "assets/images/logo_bg.png"
                },
                2:{
                    'name': "Project1",
                    'avatar': "assets/images/CytoChartDemo/bp1.png"
                },
                3:{
                    'name': "Project2",
                    'avatar': "assets/images/CytoChartDemo/bp2.png"
                },
                4:{
                    'name': "Project3",
                    'avatar': "assets/images/CytoChartDemo/bp3.png"
                },
                5:{
                    'name': "Project4",
                    'avatar': "assets/images/CytoChartDemo/bp4.png"
                }
            };

            var configPM = configPManagerCytoChart(productsAux, theProductManagerId, edges);
            var cytograph3_metrics = configPM.metrics;
            var cytograph3_configuration = configPM.config;

            var cytograph3 = new framework.widgets.CytoChart2(cytograph3_dom, cytograph3_metrics,
                    [orgCtx, timeCtx], cytograph3_configuration);

            // ----------------------------- TEAM MEMBERS LINES CHART ----------------------------------
            var team_members_lines_dom = document.getElementById("team-members-lines");
            var team_members_lines_metrics = [
                {
                    id: 'orgcommits',
                    max: 30
                },
                /*{
                    id: 'usercommits',
                    max: 30,
                    aggr: "avg"
                }*/
            ];
            var team_members_lines_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: '¬_D.data.info.title¬',
                colors: ["#2876B8", "#C0485E"],
                area: false,
                _demo: true // Only for demo
            };
            var team_members_lines = new framework.widgets.LinesChart(team_members_lines_dom, team_members_lines_metrics,
                    [orgCtx, timeCtx], team_members_lines_configuration);

            //  --------------------------- PRODUCT / PROJECTS TABLE ---------------------------------

            var superContextData = {};
            var widgetList = [];

            // This method combines different contexts into a supercontext
            var superContextHandler = function(data, changes, contextId) {

                // Update the changed data
                superContextData[contextId] = data;

                // Simple merge of the data from all the contexts...combines arrays with an union of the elements
                var mergedData = {};
                for(var context in superContextData) {

                    for(var prop in superContextData[context]) {
                        if(mergedData[prop] != null && mergedData[prop] instanceof Array && superContextData[context][prop] instanceof Array) {
                            for(var i = 0; i < superContextData[context][prop].length; i++) {
                                if(mergedData[prop].indexOf(superContextData[context][prop][i]) == -1)
                                    mergedData[prop].push(superContextData[context][prop][i]);
                            }
                        } else {
                            mergedData[prop] = superContextData[context][prop];
                        }
                    }

                }

                // Update the super context
                framework.data.updateContext(productByProjectCtx, mergedData);

            };

            framework.data.observe(['repolist'], function (event) { //Change repos with products

                if (event.event === 'data') {

                    // Stop the observer of the context of previous widgets
                    framework.data.stopObserve(superContextHandler);

                    // Destroy previous widgets
                    var removeWidget;
                    while((removeWidget = widgetList.pop()) != null) {
                        removeWidget.delete();
                    }

                    // Remove table rows
                    $("#product-projects-table").empty();

                    // Now we can start adding things
                    var products = event.data['repolist'][Object.keys(event.data['repolist'])[0]]['data'];

                    for(var x = 0; x < products.length; x++) {
                        var name = products[x]['name'];
                        var productId = products[x]['repositoryid'];
                        var context = "product-projects-table-" + Math.floor(Math.random() * 100000000);

                        // Observe the context to handle changes in any of the subtables and actualize the super-context
                        framework.data.observeContext(context, superContextHandler);

                        // Create a new row in the HTML table
                        var newRowHeader = $("<tr class='tableProductRow'><td class='tableProductLabel'>" + name + "</td></tr>");
                        var newRowTable = $("<tr><td></td></tr>");
                        $("#product-projects-table").append(newRowHeader);
                        $("#product-projects-table").append(newRowTable);


                        // Create a new table widget inside that HTML table
                        var product_projects_table_dom = newRowTable.find("td").get(0);
                        var product_projects_table_metrics = [
                            {
                                id: 'repolist', //TODO: change resource
                                //TODO: force the product id
                            }
                        ];
                        var product_projects_table_configuration = {
                            columns: [
                                {
                                    label: "",
                                    link: {
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
                                     },
                                    width: "40px"
                                },
                                {
                                    label: "",
                                    property: "name"
                                }
                            ],
                            updateContexts: [
                                {
                                    id: context,
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
                            maxRowsSelected: 8,
                            filterControl: true,
                            initialSelectedRows: 3,
                            showHeader: false,
                            filterControl: false
                        };

                        //Create the widget
                        var widget = new framework.widgets.Table(product_projects_table_dom, product_projects_table_metrics, [orgCtx, timeCtx], product_projects_table_configuration);

                        // Add the widget to the widget list to then be able to destroy all the widgets in case of context change
                        widgetList.push(widget);
                    }
                }
            }, [timeCtx]);

            // --------------------------ROLES MULTIBAR ------------------------------------
            var project_roles_multibar_dom = document.getElementById("projects-roles-multibar");
            var project_roles_multibar_metrics = [
                {
                    id: 'repodevelopers',
                    max: 1
                },
                                {
                    id: 'repopassedexecutions',
                    max: 1
                },
                {
                    id: 'repocommits',
                    max: 1
                },
                {
                    id: 'repobrokenexecutions',
                    max: 1
                }
            ];
            var roles = {
                'repodevelopers' : 'software developer', 
                'repopassedexecutions': 'software architect', 
                'repocommits': 'project manager', 
                'repobrokenexecutions': 'stakeholder'
            };
            var project_roles_multibar_conf = {
                stacked: false,
                labelFormat: "¬_D.data.info.rid.name¬",
                showControls: false,
                height: 250,
                showLegend: true,
                x: function(metric, extra) {
                    return roles[extra.resource];
                }
            };
            var project_roles_multibar = new framework.widgets.MultiBar(project_roles_multibar_dom, project_roles_multibar_metrics,
                    [orgCtx, timeCtx, productByProjectCtx], project_roles_multibar_conf);
        };

            // TEAM MEMBERS ROLES
            var team_members_pie_dom = document.getElementById("team-members-pie");
            var team_members_pie_metrics = [
                {
                    id: 'orgcommits',
                    max: 1,
                    aggr: "sum"
                },
                {
                    id: 'orgdevelopers',
                    max: 1,
                    aggr: "sum"
                },
                {
                    id: 'orgbranches',
                    max: 1,
                    aggr: "sum"
                },
                {
                    id: 'orgexec',
                    max: 1,
                    aggr: "sum"
                }];
            var team_members_pie_configuration = {
                height: 300,
                labelFormat: "¬(_E.resource == 'orgcommits' ? 'Software developer' : " +
                "(_E.resource == 'orgdevelopers' ? 'Software Arquitect' : " +
                "(_E.resource == 'orgbranches' ? 'Project Manager' : 'Stakeholder')))¬"
            };
            var team_members_pie = new framework.widgets.PieChart(team_members_pie_dom, team_members_pie_metrics,
                    [orgCtx, timeCtx, productByProjectCtx], team_members_pie_configuration);
    }

@stop
