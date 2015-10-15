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
    "sdh-framework/framework.widget.linesChart",
    "css!sdh-framework/framework.widget.linesChart.css",
    "sdh-framework/framework.widget.liquidgauge",
    "sdh-framework/framework.widget.piechart",
    "css!assets/css/dashboards/director-dashboard"
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
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 300,
                labelFormat: '%data.info.title%',
                colors: ["#2876B8", "#C0485E"],
                area: false,
                showPoints: true,
                showLines: false
            };
            var releasesLines = new framework.widgets.LinesChart(releasesLines_dom, releasesLines_metrics, [orgCtx, timeCtx, productsCtx], releasesLines_configuration);

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
