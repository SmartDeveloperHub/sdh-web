{{--
    User dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "vendor/sdh-framework/framework.widget.rangeNv",
    "vendor/sdh-framework/framework.widget.counterbox",
    "vendor/sdh-framework/framework.widget.scatter",
    "vendor/sdh-framework/framework.widget.table",
    "css!vendor/sdh-framework/framework.widget.table.css",
    "css!vendor/qtip2/jquery.qtip.min.css",
    "vendor/sdh-framework/framework.widget.linesChart",
    "css!vendor/sdh-framework/framework.widget.linesChart.css",
    "vendor/sdh-framework/framework.widget.radarchart",
    "vendor/sdh-framework/framework.widget.liquidgauge",
    "vendor/sdh-framework/framework.widget.piechart",
    "vendor/sdh-framework/framework.widget.timebar",
    "css!vendor/sdh-framework/framework.widget.timebar.css",
    "vendor/sdh-framework/framework.widget.cytoChart2",
    "css!vendor/sdh-framework/framework.widget.cytoChart2.css",
    "vendor/sdh-framework/framework.widget.multibar",
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
        <div class="row scatterBox">
            <div id="scatter-plot-subtitle" class="row subtitleRow">
                <span id="scatter-plot-stitle-ico" class="subtitleIcon fa fa-balance-scale"></span>
                <span id="scatter-plot-stitle-label" class="subtitleLabel">Products analysis</span>
                <span id="scatter-plot-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
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
                    <span id="products-table-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
                </div>
                <div id='upProductTableButton' class="upTableButton fa-angle-double-up"></div>
                <div id="products-table" class="widget"></div>
                <div id='downProductTableButton' class="downTableButton fa-angle-double-down"></div>
            </div>
            <div class="col-sm-8">
                <div id="releases-chart-subtitle" class="row subtitleRow">
                    <span id="releases-chart-stitle-ico" class="subtitleIcon fa fa-hourglass-half"></span>
                    <span id="releases-chart-stitle-label" class="subtitleLabel">Status History</span>
                    <span id="releases-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
                </div>
                <div class="row">
                    <div id="releases-chart" class="widget"></div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div id="radar-product-subtitle" class="row subtitleRow">
                            <span id="radar-product-stitle-ico" class="subtitleIcon fa fa-line-chart"></span>
                            <span id="radar-product-stitle-label" class="subtitleLabel"></span>
                            <span id="radar-product-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
                        </div>
                        <div class="row">
                            <div id="radar-product-chart" class="widget"></div>
                        </div>
                    </div>
                    <div id="liquidBox" class="col-sm-6">
                        <div id="liquid1-chart-subtitle" class="row subtitleRow">
                            <span id="liquid1-chart-stitle-ico" class="subtitleIcon fa fa-link"></span>
                            <span id="liquid1-chart-stitle-label" class="subtitleLabel">Success Time</span>
                            <span id="liquid1-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
                        </div>
                        <div class="row">
                            <div id="liquid-1-chart" class="widget"></div>
                        </div>
                        <div id="liquid2-chart-subtitle" class="row subtitleRow">
                            <span id="liquid2-chart-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                            <span id="liquid2-chart-stitle-label" class="subtitleLabel">Broken Time</span>
                            <span id="liquid2-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
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
            <span id="peopleTitIco" class="titleIcon fa fa-users"></span>
            <span id="peopleTitLabel" class="titleLabel">Team Members</span>
        </div>
        <div id="managers-subtitle" class="row subtitleRow">
            <span id="managers-stitle-ico" class="subtitleIcon fa fa-sitemap"></span>
            <span id="managers-stitle-label" class="subtitleLabel">Managers</span>
            <span id="managers-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
        </div>
        <div class="row treeChartBox">
            <div id="cytograph1" class="col-sm-4 col-centered"></div>
            <div id="cytograph2" class="col-sm-4 col-centered"></div>
            <div id="cytograph3" class="col-sm-4 col-centered"></div>
        </div>
        <div class="row">
            <div id="positions-subtitle" class="row subtitleRow">
                <span id="positions-stitle-ico" class="subtitleIcon fa fa-graduation-cap"></span>
                <span id="positions-stitle-label" class="subtitleLabel">Positions</span>
                <span id="positions-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
            <div class="row ">
                <div class="col-sm-12">
                    <div id="position-members-lines" class="widget"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div id="members-table-subtitle" class="row subtitleRow">
                    <span id="members-table-stitle-ico" class="subtitleIcon fa fa-hand-pointer-o"></span>
                    <span id="members-table-stitle-label" class="subtitleLabel"> Manager Selector</span>
                    <span id="members-table-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
                </div>
                <div id='upTeamTableButton' class="upTableButton fa-angle-double-up"></div>
                <div id="team-members-table" class="widget"></div>
                <div id='downTeamTableButton' class="downTableButton fa-angle-double-down"></div>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div id="team-multibar-subtitle" class="row subtitleRow">
                        <span id="team-multibar-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                        <span id="team-multibar-stitle-label" class="subtitleLabel">Manager Comparison</span>
                        <span id="team-multibar-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
                    </div>
                    <div id="projects-roles-multibar" class="widget"></div>
                </div>
                <div class="row">
                    <div id="team-pie-subtitle" class="row subtitleRow">
                        <span id="team-pie-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                        <span id="team-pie-stitle-label" class="subtitleLabel">Total Member Roles</span>
                        <span id="team-pie-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
                    </div>
                    <div id="team-members-pie" class="widget"></div>
                </div>
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
        var teamMembersCtx = "team-members-context";

        //Show header chart and set titles
        setTitle("Home");
        setSubtitle("Director");
        showHeaderChart();

        //change Product subtitle in start chart
        framework.data.observe(['productinfo'], function (event) {

            if (event.event === 'data') {
                var productInfo = event.data['productinfo'][Object.keys(event.data['productinfo'])[0]]['data'];
                $('#radar-product-stitle-label').text(productInfo.name);
            }

        }, [productsCtx]);

        //Subtitles information trying to include touch mode
        /*$('.subtitleRow').on('click') {
            $('.subtitleRow').onhover.call(p);
        }*/
        // Subtitles info
        var addQTip = function addQTip(element, id, htmlText) {
            element.qtip({
                content: function(){
                    return '<div id="' + id + '" class=subtitleTooltip>' + htmlText + '</div>';
                },
                show: {
                    event: 'mouseover'
                },
                hide: {
                    event: 'unfocus'
                },
                position: {
                    my: 'top center',
                    at: 'bottom center'
                },
                style: {
                    classes: 'DirectorQTip qtip-bootstrap',
                    tip: {
                        width: 16,
                        height: 6
                    }
                }
            });
        };
        // Product analysis
        var productAnalysis = '<div><span class="toolTitle"><p>This chart shows the most significant products.</p></span></div><div><span class="toolRow"><span class="ico fa fa-eur red"></span><strong>Cost</strong>. Directly proportional to the size of the circles</span></div><div><span class="toolRow"><span class="ico fa fa-heartbeat orange"></span><strong>Health</strong>. Colour. <span class="red">Red-bad</span> <span class="green">Green-good</span></span></div><div><span class="toolRow"><span class="ico fa fa-balance-scale green"></span><strong>Quality</strong>. Y axis</span></div><div><span class="toolRow"><span class="ico fa fa-hourglass-start violet"></span><strong>Time To Market</strong>. X axis</span></div>';
        addQTip($('#scatter-plot-stitle-help'), "prodAnalisisTool", productAnalysis);
        // Product Selector
        var productSelector = '<div><span class="toolTitle"><p>Most significant products.</p></span></div><div><span class="toolRow">Select one to analyze it.</span></div>';
        addQTip($('#products-table-stitle-help'), "prodTableTool", productSelector);
        // Status History
        var statusHistory = '<div><span class="toolTitle"><p>Product information.</p></span></div><div><span class="toolRow">Analyze the releases status history.</span></div>';
        addQTip($('#releases-chart-stitle-help'), "prodStatusTool", statusHistory);
        // Product radar
        var radarInfo = '<div><span class="toolTitle"><p>Product information.</p></span></div><div><span class="toolRow">Compare the selected product to</span></div><div><span class="toolRow">the average of all other products.</span></div>';
        addQTip($('#radar-product-stitle-help'), "prodRadarTool", radarInfo);
        // Success Time
        var liquidSuccess = '<div><span class="toolTitle"><p>Releases status.</p></span></div><div><span class="toolRow">Success Percent.</span></div>';
        addQTip($('#liquid2-chart-stitle-help'), "prodLiqSuccesTool", liquidSuccess);
        // Broken Time
        var liquidBroken = '<div><span class="toolTitle"><p>Releases status.</p></span></div><div><span class="toolRow">Broken Percent.</span></div>';
        addQTip($('#liquid1-chart-stitle-help'), "prodLiqBrokenTool", liquidBroken);
        // Managers Cytocharts
        var managerCyto = '<div><span class="toolTitle"><p>Most significant managers.</p></span></div><div><span class="toolRow">Analyze the most important products for each manager.</span></div>';
        addQTip($('#managers-stitle-help'), "prodManagersTool", managerCyto);
        // Positions Lines
        var positionLine = '<div><span class="toolTitle"><p>Members by positions.</p></span></div><div><span class="toolRow">Analyze the historic number of members by position.</span></div>';
        addQTip($('#positions-stitle-help'), "positionsLineTool", positionLine);
        // Manager Selector
        var managerSelect = '<div><span class="toolTitle"><p>Most significant Managers.</p></span></div><div><span class="toolRow">Select one to analyze it.</span></div>';
        addQTip($('#members-table-stitle-help'), "managerSelectTool", managerSelect);
        // Manager Comparison
        var managerComp = '<div><span class="toolTitle"><p>Manager team roles comparison.</p></span></div><div><span class="toolRow">Compare the number of manager team roles.</span></div>';
        addQTip($('#team-multibar-stitle-help'), "managerCompTool", managerComp);
        // Total Member Roles
        var memberRoles = '<div><span class="toolTitle"><p>Total members by role.</p></span></div><div><span class="toolRow">Accumulated number of team members by role.</span></div>';
        addQTip($('#team-pie-stitle-help'), "memberRolesTool", memberRoles);

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
                id: 'orgcommits',  //TODO: director Products metric. userproducts con uid. Tengo que conseguir el uid del logueado
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
                id: 'orgcommits', //TODO: director users metric. userrsers con uid. Tengo que conseguir el uid del logueado
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
                id: 'orgcommits',  //TODO: Nº Releases: total builds passed in master branch. userReleases o userPassedBuilds o algo así
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
                id: 'orgcommits',  //TODO: Ad hoc? o userTeamCost?. Total de coste por team member 25*nºmembers * (dias del rango seleccionado)
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
                id: 'orgcommits',  //TODO: AdHoc? userExternalContributors?. Número de externos (contributors) o  % externos
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
                id: 'orgcommits',  //TODO: AdHoc? userExternalContributorCompanies? básicamente sacar del dominio del mail el nombre de  la empresa exerna.
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
                id: 'orgcommits',  //TODO: userProductsMembers AVG
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
                id: 'orgcommits', //TODO: userProductwHealth AVG
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

            // CYTOCHART CONFIG FOR DIRECTOR
            function configDirectorCytoChart(productsAux, theProductManagerId, edges) {
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

                    cytograph1_configuration.tooltip = "Staff: ¬_D.data.values[0]¬";

                    // Add Node
                    cytograph1_configuration.nodes.push(
                        {
                            id: productsAux[prId].name,
                            avatar:productsAux[prId].avatar,
                            shape:"ellipse",
                            volume: productMetricId,
                            tooltip: productsAux[prId].tooltip || ""
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
                { source: 'P_ManagerA', target: 'Product_a' },
                { source: 'P_ManagerA', target: 'Product_b' },
                { source: 'P_ManagerA', target: 'Product_c' },
                { source: 'P_ManagerA', target: 'Product_d' }
            ];
            var productsAux = {
                1:{
                    'name': "P_ManagerA",
                    'avatar': "assets/images/CytoChartDemo/PManager1.jpg",
                    tooltip: "I'm the main circle on the left"
                },
                2:{
                    'name': "Product_a",
                    'avatar': "assets/images/CytoChartDemo/bp1.png"
                },
                3:{
                    'name': "Product_b",
                    'avatar': "assets/images/CytoChartDemo/bp2.png"
                },
                4:{
                    'name': "Product_c",
                    'avatar': "assets/images/CytoChartDemo/bp3.png"
                },
                5:{
                    'name': "Product_d",
                    'avatar': "assets/images/CytoChartDemo/bp4.png"
                }
            };

            var configPM = configDirectorCytoChart(productsAux, theProductManagerId, edges);
            var cytograph1_metrics = configPM.metrics;
            var cytograph1_configuration = configPM.config;

            var cytograph1 = new framework.widgets.CytoChart2(cytograph1_dom, cytograph1_metrics,
                    [orgCtx, timeCtx], cytograph1_configuration);

            // CYTOCHART2 INITIALIZATION
            var cytograph2_dom = document.getElementById("cytograph2");
            var theProductManagerId = 1;
            var edges = [
                { source: 'P_ManagerA', target: 'Product_a' },
                { source: 'P_ManagerA', target: 'Product_b' },
                { source: 'P_ManagerA', target: 'Product_c' }
            ];
            var productsAux = {
                1:{
                    'name': "P_ManagerA",
                    'avatar': "assets/images/CytoChartDemo/PManager2.jpg",
                    tooltip: "I'm the main circle on the center"
                },
                2:{
                    'name': "Product_a",
                    'avatar': "assets/images/CytoChartDemo/gp1.png"
                },
                3:{
                    'name': "Product_b",
                    'avatar': "assets/images/CytoChartDemo/gp2.png"
                },
                4:{
                    'name': "Product_c",
                    'avatar': "assets/images/CytoChartDemo/gp3.png"
                }
            };

            var configPM = configDirectorCytoChart(productsAux, theProductManagerId, edges);
            var cytograph2_metrics = configPM.metrics;
            var cytograph2_configuration = configPM.config;

            var cytograph2 = new framework.widgets.CytoChart2(cytograph2_dom, cytograph2_metrics,
                    [orgCtx, timeCtx], cytograph2_configuration);

            // CYTOCHART3 INITIALIZATION
            var cytograph3_dom = document.getElementById("cytograph3");
            var theProductManagerId = 1;
            var edges = [
                { source: 'P_ManagerA', target: 'Product_a' },
                { source: 'P_ManagerA', target: 'Product_b' },
                { source: 'P_ManagerA', target: 'Product_c' },
                { source: 'P_ManagerA', target: 'Product_d' },
                { source: 'P_ManagerA', target: 'Product_e' }
            ];
            var productsAux = {
                1:{
                    'name': "P_ManagerA",
                    'avatar': "assets/images/CytoChartDemo/PManager3.jpg",
                    tooltip: "I'm the main circle on the right"
                },
                2:{
                    'name': "Product_a",
                    'avatar': "assets/images/CytoChartDemo/rp1.png"
                },
                3:{
                    'name': "Product_b",
                    'avatar': "assets/images/CytoChartDemo/rp2.png"
                },
                4:{
                    'name': "Product_c",
                    'avatar': "assets/images/CytoChartDemo/rp3.png"
                },
                5:{
                    'name': "Product_d",
                    'avatar': "assets/images/CytoChartDemo/rp4.png"
                },
                6:{
                    'name': "Product_e",
                    'avatar': "assets/images/CytoChartDemo/rp5.png"
                }
            };

            var configPM = configDirectorCytoChart(productsAux, theProductManagerId, edges);
            var cytograph3_metrics = configPM.metrics;
            var cytograph3_configuration = configPM.config;

            var cytograph3 = new framework.widgets.CytoChart2(cytograph3_dom, cytograph3_metrics,
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
                yAxisTicks: 3,
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
                image: "¬_D.data.repocommits.info.rid.avatar¬",
                xAxisGradient: ['red', 'orange', 'yellow', 'green'],
                yAxisGradient: ['green', 'yellow', 'orange', 'red'],
                showLegend: false,
                showMaxMin: false
            };

            var scatter = new framework.widgets.Scatter(scatter_dom, scatter_metrics, [orgCtx, timeCtx, scatter_test_cntx], scatter_conf);

            //  ----------------------------------- PRODUCTS TABLE ------------------------------------------
            var table_dom = document.getElementById("products-table");
            var table_metrics = ['productlist']; //TODO: choose resource
            var table_configuration = {
                columns: [
                    {
                        label: "",
                        link: {
                            img: "avatar", //or label
                            href: "product",
                            env: [
                                {
                                    property: "productid",
                                    as: "prid"
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
                                property: "productid", //TODO
                                as: "prid"
                            }
                        ]
                    }
                ],
                keepSelectedByProperty: "productid",
                selectable: true,
                minRowsSelected: 0,
                maxRowsSelected: 1,
                filterControl: true,
                initialSelectedRows: 1,
                showHeader: false,
                alwaysOneSelected: true,
                scrollUpButton: $('#upProductTableButton'),
                scrollDownButton: $('#downProductTableButton')
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [orgCtx, timeCtx], table_configuration);

            //  ----------------------------------- RELEASES LINES WIDGET ------------------------------------------
            var releasesLines_dom = document.getElementById("releases-chart");

            var releasesLines_metrics = [{
                id: 'productreleasestatus',
                max: 20
            }];

            var releasesLines_configuration = {
                height: 65,
                color: function(val) {
                    var color = d3.scale.linear()
                            .domain([0, 0.5, 1])
                            .range(["red", "yellow", "green"]);
                    return color(val);
                },
                tooltip: '<h3>Value: ¬Math.round(_E.value * 100)/100¬</h3>' +
                         '<h3>Date: ¬Widget.format.date(_E.time)¬ </h3>',
                legend: ['Success', 'Broken']
            };
            var releasesLines = new framework.widgets.TimeBar(releasesLines_dom, releasesLines_metrics, [orgCtx, timeCtx, productsCtx], releasesLines_configuration);

            // PRODUCT STAR CHART
            var skills_star_dom = document.getElementById("radar-product-chart");
            var skills_star_metrics = [
                {
                    id: 'productactivity',
                    max: 1
                },
                {
                    id: 'productcost',
                    max: 1
                },
                {
                    id: 'productmembers',
                    max: 1
                },
                {
                    id: 'producthealth',
                    max: 1
                },
                {
                    id: 'productquality',
                    max: 1
                },
                {
                    id: 'producttimetomarket',
                    max: 1
                },
                {
                    id: 'productreleases',
                    max: 1
                }
            ];
            /* TODO add average skills in this chart
            var skills_star_metrics2 = [
                {
                    id: 'userproductactivity',
                    max: 1
                },
                {
                    id: 'userproductcost',
                    max: 1
                },
                {
                    id: 'userproductmembers',
                    max: 1
                },
                {
                    id: 'userproducthealth',
                    max: 1
                },
                {
                    id: 'userproductquality',
                    max: 1
                },
                {
                    id: 'userproducttimetomarket',
                    max: 1
                },
                {
                    id: 'userproductreleases',
                    max: 1
                }
            ];*/
            var skills_star_configuration = {
                height: 200,
                radius: 180,
                labels: ["Activity", "Cost", "Members", 'Health', 'Quality', 'Time To Market', 'Releases'],
                fillColor: "rgba(1, 150, 64, 0.4)",
                strokeColor: "#019640",
                pointLabelFontColor: "#2876B8",
                pointLabelFontSize: 12
            };
            var skills_star = new framework.widgets.RadarChart(skills_star_dom, skills_star_metrics,
                    [orgCtx, timeCtx, productsCtx], skills_star_configuration);

            //  ----------------------------------- LIQUID GAUGE 1 ------------------------------------------
            var test_dom = document.getElementById("liquid-1-chart");
            var test_metrics = [
                {
                    id: 'productreleasestatus',
                    max: 1
                }
            ];
            var test_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: '#8ACA17',
                textColor: '#4BAD06',
                circleColor: '#4BAD06',
                waveTextColor:'#DBF1B4',
                radius: 45
            };
            var test = new framework.widgets.LiquidGauge(test_dom, test_metrics,
                    [orgCtx, timeCtx, productsCtx], test_configuration);

            //  ----------------------------------- LIQUID GAUGE 2 ------------------------------------------
            var test_dom = document.getElementById("liquid-2-chart");
            var test_metrics = [
                {
                    id: 'producthealth',
                    max: 1
                }
            ];
            var test_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: '#E65538',
                textColor: '#8C1700',
                circleColor: '#8C1700',
                waveTextColor: '#FFC5B9',
                radius: 45
            };
            var test = new framework.widgets.LiquidGauge(test_dom, test_metrics,
                    [orgCtx, timeCtx, productsCtx], test_configuration);

            // ----------------------------- TEAM MEMBERS LINES CHART ----------------------------------
            var team_members_lines_dom = document.getElementById("position-members-lines");
            var team_members_lines_metrics = [
                {
                    id: 'directormanagers',
                    max: 40,
                    uid: 1
                },
                {
                    id: 'directorarchitects',
                    max: 40,
                    uid: 1
                },
                {
                    id: 'directordevelopers',
                    max: 40,
                    uid: 1
                }
            ];
            var team_members_lines_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: '¬_D.data.info.title¬',
                area: true
            };
            var team_members_lines = new framework.widgets.LinesChart(team_members_lines_dom, team_members_lines_metrics,
                    [orgCtx, timeCtx], team_members_lines_configuration);


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
                    [orgCtx, timeCtx, teamMembersCtx], team_members_pie_configuration);

            //  ------------------------------ PRODUCT MANAGERS TABLE --------------------------------------
            var team_members_table_dom = document.getElementById("team-members-table");
            var team_members_table_metrics = ['repolist']; //TODO: choose resource
            var team_members_table_configuration = {
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
                        id: teamMembersCtx,
                        filter: [
                            {
                                property: "repositoryid", //TODO
                                as: "rid"
                            }
                        ]
                    }
                ],
                keepSelectedByProperty: "repositoryid",
                selectable: true,
                minRowsSelected: 1,
                maxRowsSelected: 8,
                filterControl: true,
                initialSelectedRows: 3,
                showHeader: false,
                filterControl: false,
                scrollUpButton: $('#upTeamTableButton'),
                scrollDownButton: $('#downTeamTableButton'),
                height: 620
            };
            var team_members_table = new framework.widgets.Table(team_members_table_dom, team_members_table_metrics, [orgCtx, timeCtx], team_members_table_configuration);

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
                    [orgCtx, timeCtx, teamMembersCtx], project_roles_multibar_conf);
        };
    }

@stop
