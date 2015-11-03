{{--
    Product manager dashboard
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
    "css!sdh-framework/framework.widget.cytoChart2.css",
    "sdh-framework/framework.widget.multibar",
    "css!assets/css/dashboards/product_manager-dashboard",
    "http://crypto-js.googlecode.com/svn/tags/3.0.2/build/rollups/aes.js"
    ]
@stop

@section('html')
    <div class="row">
        <div class="row titleRow">
            <span class="titleIcon titleIcon octicon octicon-dashboard"></span>
            <span class="titleLabel">Metrics</span>
        </div>
        <div class="row">
            <div id="products-ctr" class="boxCounter col-sm-4"></div>
            <div id="developers-ctr" class="boxCounter col-sm-4"></div>
            <div id="repositories-ctr" class="boxCounter col-sm-4"></div>
            <div id="projects-ctr" class="boxCounter col-sm-4"></div>
            <div id="avg-developers-ctr" class="boxCounter col-sm-4"></div>
            <div id="avg-repositories-ctr" class="boxCounter col-sm-4"></div>
        </div>
    </div>

    <div class="row">
        <div class="row titleRow">
            <span class="titleIcon titleIcon fa fa-gift"></span>
            <span class="titleLabel">Products</span>
        </div>
        <div class="row">
            <div class="row">
                <div id="managers-graph" class="widget"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div id="scatter-plot" class="widget"></div>
                </div>
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
        <div class="row titleRow">
            <span class="titleIcon titleIcon fa fa-users"></span>
            <span class="titleLabel">Team members</span>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div id="team-members-lines" class="widget"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="projects-roles-pie" class="widget"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div id="projects-roles-multibar" class="widget"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <table id="product-projects-table" class="widget"></table>
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
                icon: 'fa fa-gift',
                iconbackground: 'rgb(0, 75, 139)',
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
                iconbackground: 'rgb(0, 75, 139)',
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
                label: 'Developers',
                decimal: 0,
                icon: 'fa fa-users',
                iconbackground: 'rgb(0, 75, 139)',
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
                icon: 'octicon octicon-git-commit',
                iconbackground: 'rgb(0, 75, 139)',
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
                icon: 'octicon octicon-git-commit',
                iconbackground: 'rgb(0, 75, 139)',
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
                height: 500,
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
            var table_metrics = ['repolist']; //TODO: choose resourcemonit
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
                labelFormat: '%data.info.title%',
                colors: ["#2876B8", "#C0485E"],
                area: false,
                _demo: true // Only for demo
            };
            var team_members_lines = new framework.widgets.LinesChart(team_members_lines_dom, team_members_lines_metrics,
                    [orgCtx, timeCtx], team_members_lines_configuration);


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
                    [orgCtx, timeCtx], test_configuration); */


            //  --------------------------- PRODUCT / PROJECTS TABLE ---------------------------------
            framework.data.observe(['repolist'], function (event) { //Change repos with products

                if (event.event === 'data') {
                    var products = event.data['repolist'][Object.keys(event.data['repolist'])[0]]['data'];

                    for(var x = 0; x < products.length; x++) {
                        var name = products[x]['name'];
                        var productId = products[x]['repositoryid'];

                        // Create a new row in the HTML table
                        var newRowHeader = $("<tr><th>" + name + "</th></tr>");
                        var newRowTable = $("<tr><td></td></tr>");
                        $("#product-projects-table").append(newRowHeader);
                        $("#product-projects-table").append(newRowTable);


                        // Create a new table widget inside that HTML table
                        var product_projects_table_dom = newRowTable.find("td").get(0);
                        var product_projects_table_metrics = [
                            {
                                id: 'repolist', //TODO: change resource
                                //force the product id
                            }
                        ];
                        var product_projects_table_configuration = {
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
                                    id: productByProjectCtx,
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
                            showHeader: false,
                            filterControl: false
                        };
                        new framework.widgets.Table(product_projects_table_dom, product_projects_table_metrics, [orgCtx, timeCtx], product_projects_table_configuration);



                    }
                }
            }, []);


            // PROJECTS CHART
            var projects_pie_dom = document.getElementById("projects-roles-pie");
            var projects_pie_metrics = [
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
            var projects_pie_configuration = {
                height: 300,
                labelFormat: "%resourceId%"
            };
            var projects_pie = new framework.widgets.PieChart(projects_pie_dom, projects_pie_metrics,
                    [orgCtx, timeCtx, productByProjectCtx], projects_pie_configuration);


            // --------------------------ROLES MULTIBAR ------------------------------------
            var project_roles_multibar_dom = document.getElementById("projects-roles-multibar");
            var project_roles_multibar_metrics = [
                {
                    id: 'orgfailedbuilds',
                    max: 30
                },
                {
                    id: 'orgpassedexecutions',
                    max: 30
                }];
            var project_roles_multibar_conf = {
                stacked: false,
                labelFormat: "%data.info.title%",
                showControls: false,
                height: 250,
                color: {
                    orgfailedbuilds: "#0A8931",
                    orgpassedexecutions: "#DB0013"
                }
            };
            var project_roles_multibar = new framework.widgets.MultiBar(project_roles_multibar_dom, project_roles_multibar_metrics,
                    [orgCtx, timeCtx], project_roles_multibar_conf);



        };
    }

@stop
