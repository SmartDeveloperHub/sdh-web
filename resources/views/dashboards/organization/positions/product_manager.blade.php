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
    "css!assets/css/dashboards/product_manager-dashboard",
    "http://crypto-js.googlecode.com/svn/tags/3.0.2/build/rollups/aes.js"
    ]
@stop

@section('html')
    <div id="metricsSect" class="row">
        <div id="metricBoxes" class="grid-stack">
            <div class="grid-stack-item" data-gs-width="11" data-gs-height="4" data-gs-x="0" data-gs-y="1">
                <div id="metTitRow" class="titleRow grid-stack-item-content">
                    <span id="metricsTitIco" class="titleIcon octicon octicon-dashboard"></span>
                    <span id="metricsTitLabel" class="titleLabel">Metrics</span>
                </div>
            </div>

            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="0" data-gs-y="5">
                <div id="products-ctr" class="boxCounter grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="3" data-gs-y="5">
                <div id="developers-ctr" class="boxCounter grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="6" data-gs-y="5">
                <div id="repositories-ctr" class="boxCounter grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="9" data-gs-y="5">
                <div id="projects-ctr" class="boxCounter grid-stack-item-content"></div>
            </div>

            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="0" data-gs-y="13">
                <div id="personnel-cost-ctr" class="grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="3" data-gs-y="13">
                <div id="avg-developers-ctr" class="boxCounter grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="6" data-gs-y="13">
                <div id="avg-repositories-ctr" class="boxCounter grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="9" data-gs-y="13">
                <div id="empty-ctr2" class="boxCounter grid-stack-item-content"></div>
            </div>
        </div>
    </div>

    <div id="productsSect" class="grid-stack">

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="4" data-gs-x="0" data-gs-y="0">
            <div id="prodTitRow" class="titleRow grid-stack-item-content">
                <span id="productsTitIco" class="titleIcon fa fa-industry"></span>
                <span id="productsTitLabel" class="titleLabel">Products</span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="2" data-gs-x="0" data-gs-y="4">
            <div id="scatter-plot-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="scatter-plot-stitle-ico" class="subtitleIcon fa fa-balance-scale"></span>
                <span id="scatter-plot-stitle-label" class="subtitleLabel">Products analysis</span>
                <span id="scatter-plot-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="10" data-gs-height="20" data-gs-x="1" data-gs-y="6">
            <div id="scatter-plot" class="widget grid-stack-item-content"></div>
        </div>

        <!-- Left table - Product selector -->
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="3" data-gs-x="0" data-gs-y="26">
            <div id="products-table-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="products-table-stitle-ico" class="subtitleIcon fa fa-hand-pointer-o"></span>
                <span id="products-table-stitle-label" class="subtitleLabel">Product Selector</span>
                <span id="products-table-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="24" data-gs-x="0" data-gs-y="29">
            <div class="grid-stack-item-content">
                <div id="products-table" class="widget"></div>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="2" data-gs-x="4" data-gs-y="26">
            <div id="releases-chart-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="releases-chart-stitle-ico" class="subtitleIcon fa fa-hourglass-half"></span>
                <span id="releases-chart-stitle-label" class="subtitleLabel">Status History</span>
                <span id="releases-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="8" data-gs-x="4" data-gs-y="28">
            <div id="releases-chart" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="2" data-gs-height="2" data-gs-x="5" data-gs-y="36">
            <div id="radar-product-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="radar-product-stitle-ico" class="subtitleIcon fa fa-line-chart"></span>
                <span id="radar-product-stitle-label" class="subtitleLabel">Product Profile</span>
                <span id="radar-product-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="4" data-gs-y="38">
            <div id="radar-product-chart" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="2" data-gs-x="8" data-gs-y="36">
            <div id="liquid1-chart-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="liquid1-chart-stitle-ico" class="subtitleIcon fa fa-check-circle"></span>
                <span id="liquid1-chart-stitle-label" class="subtitleLabel">Product Status</span>
                <span id="liquid1-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="6" data-gs-x="8" data-gs-y="38">
            <div id="liquid-1-chart" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="2" data-gs-x="8" data-gs-y="44">
            <div id="liquid2-chart-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="liquid2-chart-stitle-ico" class="subtitleIcon fa fa-heartbeat"></span>
                <span id="liquid2-chart-stitle-label" class="subtitleLabel">Product Health</span>
                <span id="liquid2-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="6" data-gs-x="8" data-gs-y="46">
            <div id="liquid-2-chart" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="2" data-gs-x="0" data-gs-y="53">
            <div class="grid-stack-item-content subtitleRow">
                <span id="products-stitle-ico" class="subtitleIcon fa fa-sitemap"></span>
                <span id="products-stitle-label" class="subtitleLabel">Products</span>
                <span id="products-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="0" data-gs-y="55">
            <div id="cytograph1" class="widget grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="4" data-gs-y="55">
            <div id="cytograph2" class="widget grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="8" data-gs-y="55">
            <div id="cytograph3" class="widget grid-stack-item-content"></div>
        </div>

    </div>

    <!-- Section: Team Members -->
    <div class="grid-stack">

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="4" data-gs-x="0" data-gs-y="0">
            <div class="grid-stack-item-content titleRow">
                <span id="peopleTitIco" class="titleIcon fa fa-users"></span>
                <span id="peopleTitLabel" class="titleLabel">Team Members</span>
            </div>
        </div>

        <!-- Subsection: Developers/externals -->
        <div class="grid-stack-item" data-gs-width="6" data-gs-height="3" data-gs-x="0" data-gs-y="5">
            <div id="external-members-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="external-members-table-stitle-ico" class="subtitleIcon fa fa-user-secret"></span>
                <span id="external-members-table-stitle-label" class="subtitleLabel">External developers</span>
                <span id="external-members-table-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>
        <div class="grid-stack-item" data-gs-width="6" data-gs-height="10" data-gs-x="0" data-gs-y="8">
            <div id="external-members-lines" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="6" data-gs-height="3" data-gs-x="6" data-gs-y="5">
            <div id="internal-members-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="internal-members-table-stitle-ico" class="subtitleIcon fa fa-user"></span>
                <span id="internal-members-table-stitle-label" class="subtitleLabel">Internal developers</span>
                <span id="internal-members-table-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>
        <div class="grid-stack-item" data-gs-width="6" data-gs-height="10" data-gs-x="6" data-gs-y="8">
            <div id="internal-members-lines" class="widget grid-stack-item-content"></div>
        </div>

        <!-- Subsection: Roles -->

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="10" data-gs-x="0" data-gs-y="19">
            <div id="team-members-lines" class="widget grid-stack-item-content"></div>
        </div>

        <!-- Subsection: Project Selector -->

        <!-- Column -->
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="3" data-gs-x="0" data-gs-y="29">
            <div class="grid-stack-item-content subtitleRow">
                <span id="members-table-stitle-ico" class="subtitleIcon fa fa-hand-pointer-o"></span>
                <span id="members-table-stitle-label" class="subtitleLabel">Project Selector</span>
                <span id="members-table-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="30" data-gs-x="0" data-gs-y="32">
            <div class="grid-stack-item-content">
                <div id="product-projects-table" class="widget grid-stack-item-content"></div>
            </div>
        </div>

        <!-- Column -->
        <div class="grid-stack-item" data-gs-width="8" data-gs-height="2" data-gs-x="4" data-gs-y="29">
            <div id="team-multibar-subtitle" class="row subtitleRow">
                <span id="team-multibar-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                <span id="team-multibar-stitle-label" class="subtitleLabel">Teams Comparison</span>
                <span id="team-multibar-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="13" data-gs-x="4" data-gs-y="31">
            <div id="projects-roles-multibar" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="2" data-gs-x="4" data-gs-y="44">
            <div id="team-pie-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="team-pie-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                <span id="team-pie-stitle-label" class="subtitleLabel">Roles Summary</span>
                <span id="team-pie-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="13" data-gs-x="4" data-gs-y="46">
            <div id="team-members-pie" class="widget grid-stack-item-content"></div>
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
        var currentUserCtx = "current-user-context";

        framework.data.updateContext(currentUserCtx, {uid: framework.dashboard.getEnv()['user_id']});

        //Show header chart and set titles
        setTitle("Home");
        setSubtitle("Product Manager");
        showHeaderChart();

        //change Product subtitle in start chart
        framework.data.observe(['productinfo'], function (event) {

            if (event.event === 'data') {
                var productInfo = event.data['productinfo'][Object.keys(event.data['productinfo'])[0]]['data'];
                $('#radar-product-stitle-label').text(productInfo.name);
                $('#liquid1-chart-stitle-label').text(productInfo.name + " Status");
                $('#liquid2-chart-stitle-label').text(productInfo.name + " Health");
            }

        }, [productsCtx]);

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
                    event: 'mouseout'
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
        var productAnalysis = '<div><span class="toolTitle"><p>This chart shows the most significant products.</p></span></div><div><span class="toolRow"><span class="fa fa-users blue"></span><strong>Team Size</strong>. Directly proportional to the size of the circles</span></div><div><span class="toolRow"><span class="ico fa fa-heartbeat orange"></span><strong>Health</strong>. Colour. <span class="red">Red-bad</span> <span class="green">Green-good</span></span></div><div><span class="toolRow"><span class="ico fa fa-balance-scale green"></span><strong>Quality</strong>. Y axis</span></div><div><span class="toolRow"><span class="ico fa fa-hourglass-start violet"></span><strong>Time To Market</strong>. X axis</span></div>';
        addQTip($('#scatter-plot-stitle-help'), "prodAnalisisTool", productAnalysis);
        // Product Selector
        var productSelector = '<div><span class="toolTitle"><p>Most significant products.</p></span></div><div><span class="toolRow">Select one to analyze it.</span></div>';
        addQTip($('#products-table-stitle-help'), "prodTableTool", productSelector);
        // Status History
        var statusHistory = '<div><span class="toolTitle"><p>Product information.</p></span></div><div><span class="toolRow">This chart analyzes the build status history.</span></div>';
        addQTip($('#releases-chart-stitle-help'), "prodStatusTool", statusHistory);
        // Product radar
        var radarInfo = '<div><span class="toolTitle"><p>Product information.</p></span></div><div><span class="toolRow">Compare the selected product to</span></div><div><span class="toolRow">the average of all other products.</span></div>';
        addQTip($('#radar-product-stitle-help'), "prodRadarTool", radarInfo);
        // Product status
        var liquidStatus = '<div><span class="toolTitle"><p>Product status.</p></span></div><div><span class="toolRow">Average value of the builds for the product.</span></div>';
        addQTip($('#liquid2-chart-stitle-help'), "prodLiqSuccesTool", liquidStatus);
        // Product health
        var liquidHealth = '<div><span class="toolTitle"><p>Product health.</p></span></div><div><span class="toolRow">Percentage that represent the health of the product.</span></div>';
        addQTip($('#liquid1-chart-stitle-help'), "prodLiqBrokenTool", liquidHealth);
        // Managers Cytocharts
        var managerCyto = '<div><span class="toolTitle"><p>Most significant Products and Projects.</p></span></div><div><span class="toolRow">This chart analyzes the most important projects for each product.</span></div>';
        addQTip($('#products-stitle-help'), "prodManagersTool", managerCyto);
        // Positions Lines
        var positionLine = '<div><span class="toolTitle"><p>Members by positions.</p></span></div><div><span class="toolRow">This chart analyzes the distribution of members by position during the selected period of time.</span></div>';
        addQTip($('#roles-stitle-help'), "positionsLineTool", positionLine);
        // Manager Selector
        var teamSelect = '<div><span class="toolTitle"><p>Most significant Teams.</p></span></div><div><span class="toolRow">Select for comparison.</span></div>';
        addQTip($('#members-table-stitle-help'), "teamSelectTool", teamSelect);
        // Manager Comparison
        var managerComp = '<div><span class="toolTitle"><p>Manager team roles comparison.</p></span></div><div><span class="toolRow">This chart compares the number of team members per role and manager.</span></div>';
        addQTip($('#team-multibar-stitle-help'), "managerCompTool", managerComp);
        // Total Member Roles
        var memberRoles = '<div><span class="toolTitle"><p>Total members by role.</p></span></div><div><span class="toolRow">Accumulated number of team members by role.</span></div>';
        addQTip($('#team-pie-stitle-help'), "memberRolesTool", memberRoles);

        var env = framework.dashboard.getEnv();
        framework.data.updateContext(orgCtx, {oid: env['oid']});


        // --------------------------------- UPPER SELECTOR RANGENV --------------------------------------
        var rangeNv_dom = document.getElementById("fixed-chart");
        var rangeNv_metrics = [
            {
                id: 'pmanager-activity',
                //id: 'pmanager-activity', //TODO: director activity metric
                max: 101
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

        var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [orgCtx, currentUserCtx], rangeNv_configuration);
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
                id: 'pmanager-products',  //TODO: implement products metric
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
            var products = new framework.widgets.CounterBox(products_dom, products_metrics, [orgCtx, timeCtx, currentUserCtx], products_conf);

            // ------------------------------------ PROJECTS -------------------------------------------
            var team_members_dom = document.getElementById("projects-ctr");
            var team_members_metrics = [{
                id: 'pmanager-projects', //TODO: implement projects metric
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
            var team_members = new framework.widgets.CounterBox(team_members_dom, team_members_metrics, [orgCtx, timeCtx, currentUserCtx], team_members_conf);

            // ------------------------------------------ DEVELOPERS ----------------------------------------
            var developers_dom = document.getElementById("developers-ctr");
            var developers_metrics = [{
                id: 'pmanager-members',  //TODO: implement developers metric
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
            var developers = new framework.widgets.CounterBox(developers_dom, developers_metrics, [orgCtx, timeCtx, currentUserCtx], developers_conf);

            // ---------------------------------- AVERAGE DEVELOPERS PER PROJECT ---------------------------------
            var avg_developers_dom = document.getElementById("avg-developers-ctr");
            var avg_developers_metrics = [{
                id: 'pmanager-projectmembers',  //TODO: choose metric
                max: 1,
                aggr: 'avg'
            }];
            var avg_developers_conf = {
                label: 'Developers / project',
                decimal: 0,
                icon: 'octicon octicon-organization',
                iconbackground: '#EE7529',
                background: 'transparent'
            };
            var avg_developers = new framework.widgets.CounterBox(avg_developers_dom, avg_developers_metrics, [orgCtx, timeCtx, currentUserCtx], avg_developers_conf);

            // ------------------------------------------ PERSONEL COST ----------------------------------------
            var some2_dom = document.getElementById("personnel-cost-ctr");
            var some2_metrics = [{
                id: 'pmanager-costs',
                max: 1
            }];
            var some2_conf = {
                label: 'Personnel Cost',
                decimal: 0,
                icon: 'fa-eur',
                iconbackground: '#EE7529',
                background: 'transparent'
            };
            var some2 = new framework.widgets.CounterBox(some2_dom, some2_metrics, [orgCtx, timeCtx, currentUserCtx], some2_conf);

            // ----------------------------------- REPOSITORIES -------------------------------------------
            var repos_dom = document.getElementById("repositories-ctr");
            var repos_metrics = [{
                id: 'pmanager-repositories',  //TODO: choose metric
            }];
            var repos_conf = {
                label: 'Repositories',
                decimal: 0,
                icon: 'octicon octicon-repo',
                iconbackground: 'rgb(0, 75, 139)',
                background: 'transparent'
            };
            var repos = new framework.widgets.CounterBox(repos_dom, repos_metrics, [orgCtx, timeCtx, currentUserCtx], repos_conf);

            // ------------------------------- AVERAGE REPOSITORIES PER PROJECT ---------------------------------------
            var avg_repositories_dom = document.getElementById("avg-repositories-ctr");
            var avg_repositories_metrics = [{
                id: 'pmanager-projectrepositories',  //TODO: choose metric
                max: 1,
                aggr: 'avg'
            }];
            var avg_repositories_conf = {
                label: 'Repositories / project',
                decimal: 0,
                icon: 'octicon octicon-repo',
                iconbackground: '#EE7529',
                background: 'transparent'
            };
            var avg_repositories = new framework.widgets.CounterBox(avg_repositories_dom, avg_repositories_metrics, [orgCtx, timeCtx, currentUserCtx], avg_repositories_conf);

            // ------------------------------------------ SCATTER PLOT ------------------------------------------- TODO tooltips petan
            var scatter_dom = document.getElementById("scatter-plot");
            var currentCost;
            var pmanager_products_cntx = "pmanager-products-cntx";
            framework.data.observe([{id: 'pmanager-costs', max: 1 }], function(frameData) {
                if (frameData.event == "loading") {
                    return;
                }
                currentCost = frameData.data["pmanager-costs"][0].data.values[0];
                framework.data.observe(["view-pmanager-products"], function(frameData) {
                    if (frameData.event == "loading") {
                        return;
                    }

                    var pList = frameData.data["view-pmanager-products"][0].data.values;

                    var pIdList = [];
                    for (var i = 0; i < pList.length; i++) {
                        pIdList.push(pList[i].productid);
                    }

                    framework.data.updateContext(pmanager_products_cntx, {prid: pIdList});
                }, [currentUserCtx]);
            }, [currentUserCtx]);

            var scatter_metrics = [ //TODO: required metrics
                {
                    id: 'product-cost',
                    max: 1
                },
                {
                    id: 'product-health',
                    max: 1
                },
                {
                    id: 'product-quality',
                    max: 1
                },
                {
                    id: 'product-timetomarket',
                    max: 1
                }
            ];
            var scatter_conf = {
                color: function(data) {
                    var color = d3.scale.linear()
                            .domain([0, 0.5, 1])
                            .range(["red", "yellow", "green"]);
                    return color(data['product-health']['values'][0]);
                },
                size: function(data) {
                    var auxX = data['product-cost']['values'][0] / currentCost;
                    return (auxX);
                },
                shape: function(data) {
                    return 'circle';
                },
                x: function(data) {
                    return (data['product-timetomarket']['values'][0]);
                },
                xAxisLabel: "Time to market",
                y: function(data) {
                    return (data['product-quality']['values'][0]);
                },
                xAxisTicks: 3,
                yAxisTicks: 3,
                yAxisLabel: "Quality",
                height: 390,
                groupBy: 'prid',
                labelFormat: "¬_D['product-cost'].info.prid.name¬",
                showDistX: false,
                showDistY: false,
                xDomain: [0,1],
                yDomain: [0,1],
                pointDomain: [0,1],
                clipEdge: true,
                // TODO 
                tooltip: "<div class='scatterTooltip' style='text-align: center;'>" +
                "<img class='img-responsive center-block' height='60' width='60' src=\"¬_D.data['product-cost'].info.prid.avatar¬\" />" +
                "<h3>¬_D.data['product-cost'].info.prid.name¬</h3>" +
                "<div class='scattetTTLine'><i class='scatterTTIco fa fa-balance-scale green'></i><h4>Quality: ¬Math.round(_D.y * 100)/100¬</h4></div>" +
                "<div class='scattetTTLine'><i class='scatterTTIco fa fa-hourglass-start violet'></i><h4>Time to market: ¬Math.round(_D.x * 100)/100¬</h4></div>" +
                "<div class='scattetTTLine'><i class='scatterTTIco fa fa-heartbeat orange'></i><h4>Health: ¬Math.round(_D.x * 100)/100¬</h4></div>" +
                "<div class='scattetTTLine'><i class='scatterTTIco fa fa-eur red'></i><h4>Cost: ¬Math.round(_D.data.y * 100)/100¬</h4></div>" +
                "</div>",
                image: "¬_D.data['product-cost'].info.prid.avatar¬",
                xAxisGradient: ['red', 'orange', 'yellow', 'green'],
                yAxisGradient: ['green', 'yellow', 'orange', 'red'],
                showLegend: false,
                showMaxMin: false
            };

            var scatter = new framework.widgets.Scatter(scatter_dom, scatter_metrics, [orgCtx, timeCtx, pmanager_products_cntx], scatter_conf);

            //  ----------------------------------- PRODUCTS TABLE ------------------------------------------
            var table_dom = document.getElementById("products-table");
            var table_metrics = ['view-pmanager-products'];
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
                scrollButtons: true
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [orgCtx, timeCtx, currentUserCtx], table_configuration);

            var toPercentagePostAggr = function toPercentagePostAggr(responses, skel) {

                var vals = [];
                for(var i = 0; i < responses.length; ++i) {
                    var values = responses[i]['data']['values'];
                    for(var x = 0; x < values.length; x++) {
                        vals.push(Math.round(values[x] * 100));
                    }
                }

                skel['data']['values'] = vals;

                return skel;

            };

            //  ----------------------------------- RELEASES LINES WIDGET ------------------------------------------
            var releasesLines_dom = document.getElementById("releases-chart");

            var releasesLines_metrics = [{
                id: 'product-success-rate',
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

            //  ---------------------------------- PRODUCT STAR CHART ------------------------------------------ PETA mucho
            var skills_star_dom = document.getElementById("radar-product-chart");
            //Specific skills
            var skills_star_metrics1 = [
                {
                    id: 'product-activity',
                    max: 1,
                    post_aggr: toPercentagePostAggr
                },
                {
                    id: 'product-popularity-fake',
                    max: 1,
                    post_aggr: toPercentagePostAggr
                },
                {
                    id: 'product-health',
                    max: 1,
                    post_aggr: toPercentagePostAggr
                },
                {
                    id: 'product-quality',
                    max: 1,
                    post_aggr: toPercentagePostAggr
                },
                {
                    id: 'product-timetomarket',
                    max: 1,
                    post_aggr: toPercentagePostAggr
                }
            ];
            //Average skills
            var skills_star_metrics2 = [
                {
                    id: 'pmanager-activity',
                    max: 1,
                    post_aggr: toPercentagePostAggr
                },
                {
                    id: 'pmanager-popularity-fake',
                    max: 1,
                    aggr: 'avg',
                    post_aggr: toPercentagePostAggr
                },
                {
                    id: 'pmanager-health',
                    max: 1,
                    aggr: 'avg',
                    post_aggr: toPercentagePostAggr
                },
                {
                    id: 'pmanager-quality',
                    max: 1,
                    aggr: 'avg',
                    post_aggr: toPercentagePostAggr
                },
                {
                    id: 'pmanager-timetomarket',
                    max: 1,
                    aggr: 'avg',
                    post_aggr: toPercentagePostAggr
                }
            ];

            var skills_star_metrics = skills_star_metrics1.concat(skills_star_metrics2); //Merge all in one array

            var skills_star_configuration = {
                height: 200,
                radius: 180,
                labelsAssoc: [
                    {
                        'product-activity':          'Activity',
                        'product-popularity-fake':        'Popularity',
                        'product-health':            'Health',
                        'product-quality':           'Quality',
                        'product-timetomarket':      'Time To Market'
                    }, {
                        'pmanager-activity':      'Activity',
                        'pmanager-popularity-fake':    'Popularity',
                        'pmanager-health':        'Health',
                        'pmanager-quality':       'Quality',
                        'director-timetomarket':  'Time To Market'
                    }
                ],
                labels: ["Activity", "Popularity", 'Health', 'Time To Market', 'Quality' ],
                fillColor: ["rgba(30, 30, 30, 0.2)", "rgba(1, 150, 64, 0.4)"],
                strokeColor: ["rgba(30, 30, 30, 0.3)", "#019640"],
                pointLabelFontColor: "#2876B8",
                pointLabelFontSize: 14
            };
            var skills_star = new framework.widgets.RadarChart(skills_star_dom, skills_star_metrics,
                    [orgCtx, timeCtx, productsCtx, currentUserCtx], skills_star_configuration);


            //  ----------------------------------- LIQUID GAUGE 1 ------------------------------------------
            var liquid1_dom = document.getElementById("liquid-1-chart");
            var liquid1_metrics = [
                {
                    id: 'product-success-rate',
                    max: 1,
                    aggr: "sum",
                    post_aggr: toPercentagePostAggr
                }
            ];
            var liquid1_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor:'#DBF1B4',
                radius: 45
            };
            new framework.widgets.LiquidGauge(liquid1_dom, liquid1_metrics,
                    [orgCtx, timeCtx, productsCtx], liquid1_configuration);

            //  ----------------------------------- LIQUID GAUGE 2 ------------------------------------------
            var liquid2_dom = document.getElementById("liquid-2-chart");
            var liquid2_metrics = [
                {
                    id: 'product-health',
                    max: 1,
                    aggr: "sum",
                    post_aggr: toPercentagePostAggr
                }
            ];
            var liquid2_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor: '#DBF1B4',
                radius: 45
            };
            new framework.widgets.LiquidGauge(liquid2_dom, liquid2_metrics,
                    [orgCtx, timeCtx, productsCtx], liquid2_configuration);

            // ----------------------------- EXTERNAL MEMBERS LINES CHART ----------------------------------
            var external_members_lines_dom = document.getElementById("external-members-lines");
            var external_members_lines_metrics = [
                {
                    id: 'product-externals',
                    max: 60
                }
            ];
            var external_members_lines_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: '¬_D.data.info.prid.name¬',
                area: true
            };
            new framework.widgets.LinesChart(external_members_lines_dom, external_members_lines_metrics,
                    [orgCtx, timeCtx, pmanager_products_cntx], external_members_lines_configuration);


            // ----------------------------- INTERNAL MEMBERS LINES CHART ----------------------------------
            var internal_members_lines_dom = document.getElementById("internal-members-lines");
            var internal_members_lines_metrics = [
                {
                    id: 'product-developers',
                    max: 60
                }
            ];
            var internal_members_lines_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: '¬_D.data.info.prid.name¬',
                area: true
            };
            new framework.widgets.LinesChart(internal_members_lines_dom, internal_members_lines_metrics,
                    [orgCtx, timeCtx, pmanager_products_cntx], internal_members_lines_configuration);


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
                { source: 'ProductA', target: 'Project1' },
                { source: 'ProductA', target: 'Project2' },
                { source: 'ProductA', target: 'Project3' },
                { source: 'ProductA', target: 'Project4' },
                { source: 'ProductA', target: 'Project5' }
            ];
            var productsAux = {
                1:{
                    'name': "ProductA",
                    'avatar': "assets/images/logo_bg.png",
                    tooltip: "I'm the main circle on the left"
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
                    'avatar': "assets/images/logo_bg.png",
                    tooltip: "I'm the main circle on the center"
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
                    'avatar': "assets/images/logo_bg.png",
                    'tooltip': "I'm the main circle on the right"
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


            //  --------------------------- PRODUCT / PROJECTS TABLE ---------------------------------

            var superContextData = {};
            var widgetList = [];

            // This method combines different contexts into a supercontext
            var superContextHandler = function(data, changes, contextId) {

                // Get changed table id
                var contextParts = contextId.split("-");
                var dataId = contextParts[contextParts.length - 1];

                // Get the number of selected rows in the changed table
                var nSelectedInTable = $(".multitable-container > div[data-id='"+dataId+"']").find("tr.selected").length;

                // Update the number in the uper icon selector
                $("#product-projects-table .multitable-selector div[data-id='"+dataId+"'] span.number-selected").text(nSelectedInTable);

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

            framework.data.observe(['view-pmanager-products'], function (event) { //Change repos with products

                if (event.event === 'data') {

                    // Stop the observer of the context of previous widgets
                    framework.data.stopObserve(superContextHandler);

                    // Destroy previous widgets
                    var removeWidget;
                    var selectedId;
                    while((removeWidget = widgetList.pop()) != null) {
                        removeWidget.delete();
                    }

                    var multitable = $("#product-projects-table");

                    // Remove table rows
                    multitable.empty();

                    // Multitable contains a selector (with images) and a container of tables
                    var selector = $('<div class="multitable-selector"></div>');
                    var container = $('<div class="multitable-container"></div>');

                    multitable
                        .empty()
                        .append(selector)
                        .append(container);

                    // Now we can start adding things
                    var products = event.data['view-pmanager-products'][Object.keys(event.data['view-pmanager-products'])[0]]['data']['values'];
                    console.log(products);

                    // Function to change the selected table
                    var changeSelectedTable = function() {

                        if(selectedId != null) { //Previous selected
                            selector.find("div[data-id='"+selectedId+"']").removeClass("selected");
                            container.find("div[data-id='"+selectedId+"']").hide();
                        }

                        var id = $(this).data('id');

                        selector.find("div[data-id='"+id+"']").addClass("selected");
                        container.find("div[data-id='"+id+"']").show();

                        selectedId = id;
                    };

                    for(var x = 0; x < products.length; x++) {

                        var name = products[x]['name'];
                        var avatar = products[x]['avatar'];
                        var id = products[x]['productid'];
                        var context = "product-projects-table-" + id;

                        var avatarSelector = $('<div class="multitable-img-selector" data-id="'+id+'"></div>')
                                .append('<img src="'+avatar+'" alt="'+name+'"></img>')
                                .append('<span class="number-selected">0</span>')
                                .click(changeSelectedTable);

                        // Start with all the tables being hidden
                        var tableContainer = $('<div data-id="'+id+'"></div>').hide();

                        selector.append(avatarSelector);
                        container.append(tableContainer);

                        // First table is selected by default
                        if(x == 0) {
                            avatarSelector.addClass("selected");
                            avatarSelector.click();
                        }

                        // Observe the context to handle changes in any of the subtables and actualize the super-context
                        framework.data.observeContext(context, superContextHandler);


                        // Create a new table widget inside that HTML table
                        var product_projects_table_dom = tableContainer.get(0);
                        var product_projects_table_metrics = [
                            {
                                id: 'view-product-projects', //TODO: change resource
                                prid: id
                            }
                        ];
                        var product_projects_table_configuration = {
                            columns: [
                                {
                                    label: "",
                                    link: {
                                         img: "avatar", //or label
                                         href: "project",
                                         env: [
                                             {
                                                 property: "projectid",
                                                 as: "pid"
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
                                            property: "projectid", //TODO
                                            as: "pid"
                                        }
                                    ]
                                }
                            ],
                            keepSelectedByProperty: "projectid",
                            selectable: true,
                            minRowsSelected: 1,
                            maxRowsSelected: 8,
                            initialSelectedRows: 3,
                            showHeader: false,
                            filterControl: false
                        };

                        //Create the widget
                        var widget = new framework.widgets.Table(product_projects_table_dom, product_projects_table_metrics, [orgCtx, timeCtx, ], product_projects_table_configuration);

                        // Add the widget to the widget list to then be able to destroy all the widgets in case of context change
                        widgetList.push(widget);
                    }

                }
            }, [timeCtx, currentUserCtx]);

            // --------------------------ROLES MULTIBAR ------------------------------------
            var project_roles_multibar_dom = document.getElementById("projects-roles-multibar");
            var project_roles_multibar_metrics = [
                {
                    id: 'project-swdevelopers',
                    max: 1
                },
                {
                    id: 'project-swarchitects',
                    max: 1
                },
                {
                    id: 'project-pjmanagers',
                    max: 1
                },
                {
                    id: 'project-stakeholders',
                    max: 1
                }
            ];
            var roles = {
                'project-swdevelopers' : 'Software developer',
                'project-swarchitects': 'Software architect',
                'project-pjmanagers': 'Project manager',
                'project-stakeholders': 'Stakeholder'
            };
            var project_roles_multibar_conf = {
                stacked: false,
                labelFormat: "¬_D.data.info.uid.name¬",
                showControls: false,
                height: 250,
                showLegend: true,
                x: function(metric, extra) {
                    return roles[extra.resource];
                }
            };
            var project_roles_multibar = new framework.widgets.MultiBar(project_roles_multibar_dom, project_roles_multibar_metrics,
                    [orgCtx, timeCtx, productByProjectCtx], project_roles_multibar_conf);

            // TEAM MEMBERS ROLES (pie Chart)
            var team_members_pie_dom = document.getElementById("team-members-pie");
            var team_members_pie_metrics = [
                {
                    id: 'project-stakeholders',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'project-swdevelopers',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'project-pjmanagers',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'project-swarchitects',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                }
            ];
            var team_members_pie_configuration = {
                height: 250,
                showLegend: true,
                showLabels: false,
                labelFormat: "¬_D.data.info.title¬"
            };
            var team_members_pie = new framework.widgets.PieChart(team_members_pie_dom, team_members_pie_metrics,
                    [orgCtx, timeCtx, productByProjectCtx], team_members_pie_configuration);


        };

    }

@stop