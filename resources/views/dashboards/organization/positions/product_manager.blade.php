{{--
    Product manager dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "sdh-framework/widgets/RangeNv/rangeNv",
    "sdh-framework/widgets/CounterBox/counterbox",
    "sdh-framework/widgets/Scatter/scatter",
    "sdh-framework/widgets/Table/table",
    "css!vendor/qtip2/jquery.qtip.min.css",
    "sdh-framework/widgets/LinesChart/linesChart",
    "sdh-framework/widgets/RadarChart/radarchart",
    "sdh-framework/widgets/LiquidGauge/liquidgauge",
    "sdh-framework/widgets/PieChart/piechart",
    "sdh-framework/widgets/TimeBar/timebar",
    "sdh-framework/widgets/CytoChart2/cytoChart2",
    "sdh-framework/widgets/MultiBar/multibar",
    "css!assets/css/dashboards/product_manager-dashboard",
    "//cdnjs.cloudflare.com/ajax/libs/jcarousel/0.3.4/jquery.jcarousel.js"
    ]
@stop

@section('html')
    <div id="metricsSect" class="row">
        <div id="metricBoxes" class="grid-stack">
            <div class="grid-stack-item" data-gs-width="12" data-gs-height="4" data-gs-x="0" data-gs-y="1">
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

            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="2" data-gs-y="13">
                <div id="personnel-cost-ctr" class="grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="5" data-gs-y="13">
                <div id="avg-developers-ctr" class="boxCounter grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="8" data-gs-y="13">
                <div id="avg-repositories-ctr" class="boxCounter grid-stack-item-content"></div>
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

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="2" data-gs-x="0" data-gs-y="26">
            <div id="scatter-plot-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="scatter-plot-stitle-ico" class="subtitleIcon fa fa-tasks"></span>
                <span id="scatter-plot-stitle-label" class="subtitleLabel">Products workload</span>
                <span id="workload-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="10" data-gs-height="12" data-gs-x="1" data-gs-y="28">
            <div id="products-workload" class="widget grid-stack-item-content"></div>
        </div>

        <!-- Left table - Product selector -->
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="3" data-gs-x="0" data-gs-y="40">
            <div id="products-table-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="products-table-stitle-ico" class="subtitleIcon fa fa-hand-pointer-o"></span>
                <span id="products-table-stitle-label" class="subtitleLabel">Product Selector</span>
                <span id="products-table-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="32" data-gs-x="0" data-gs-y="43">
            <div class="grid-stack-item-content">
                <div id="products-table" class="widget"></div>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="3" data-gs-x="4" data-gs-y="40">
            <div id="releases-chart-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="releases-chart-stitle-ico" class="subtitleIcon fa fa-hourglass-half"></span>
                <span id="releases-chart-stitle-label" class="subtitleLabel">History</span>
                <span id="releases-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="7" data-gs-x="4" data-gs-y="43">
            <div id="product-activity-Lines" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="3" data-gs-x="4" data-gs-y="50">
            <div id="releases-chart" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="2" data-gs-x="4" data-gs-y="53">
            <div id="radar-product-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="radar-product-stitle-ico" class="subtitleIcon fa fa-line-chart"></span>
                <span id="radar-product-stitle-label" class="subtitleLabel">Product Profile</span>
                <span id="radar-product-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="18" data-gs-x="4" data-gs-y="55">
            <div id="radar-product-chart" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="2" data-gs-x="8" data-gs-y="53">
            <div id="liquid1-chart-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="liquid1-chart-stitle-ico" class="subtitleIcon fa fa-check-circle"></span>
                <span id="liquid1-chart-stitle-label" class="subtitleLabel">Product Status</span>
                <span id="liquid1-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="2" data-gs-height="7" data-gs-x="8" data-gs-y="55">
            <div id="liquid-1-chart" class="widget grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="2" data-gs-height="7" data-gs-x="10" data-gs-y="55">
            <div id="liquid-11-chart" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="2" data-gs-x="8" data-gs-y="63">
            <div id="liquid2-chart-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="liquid2-chart-stitle-ico" class="subtitleIcon fa fa-heartbeat"></span>
                <span id="liquid2-chart-stitle-label" class="subtitleLabel">Product Health</span>
                <span id="liquid2-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="2" data-gs-height="8" data-gs-x="8" data-gs-y="65">
            <div id="liquid-2-chart" class="widget grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="2" data-gs-height="8" data-gs-x="10" data-gs-y="65">
            <div id="liquid-22-chart" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="2" data-gs-x="0" data-gs-y="76">
            <div class="grid-stack-item-content subtitleRow">
                <span id="products-stitle-ico" class="subtitleIcon fa fa-sitemap"></span>
                <span id="products-stitle-label" class="subtitleLabel">Products</span>
                <span id="products-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="0" data-gs-y="79">
            <div id="cytograph1" class="widget grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="4" data-gs-y="79">
            <div id="cytograph2" class="widget grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="8" data-gs-y="79">
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
        <div class="grid-stack-item" data-gs-width="12" data-gs-height="3" data-gs-x="0" data-gs-y="5">
            <div id="external-members-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="external-members-stitle-ico" class="subtitleIcon fa fa-user-secret"></span>
                <span id="external-members-stitle-label" class="subtitleLabel">External developers</span>
                <span id="external-members-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>
        <div class="grid-stack-item" data-gs-width="12" data-gs-height="10" data-gs-x="0" data-gs-y="8">
            <div id="external-members-lines" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="3" data-gs-x="6" data-gs-y="19">
            <div id="internal-members-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="internal-members-stitle-ico" class="subtitleIcon fa fa-user"></span>
                <span id="internal-members-stitle-label" class="subtitleLabel">Internal developers</span>
                <span id="internal-members-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>
        <div class="grid-stack-item" data-gs-width="12" data-gs-height="10" data-gs-x="6" data-gs-y="22">
            <div id="internal-members-lines" class="widget grid-stack-item-content"></div>
        </div>

        <!-- Subsection: Roles -->

        <!-- Subsection: Project Selector -->

        <!-- Column -->
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="3" data-gs-x="0" data-gs-y="34">
            <div class="grid-stack-item-content subtitleRow">
                <span id="members-table-stitle-ico" class="subtitleIcon fa fa-hand-pointer-o"></span>
                <span id="members-table-stitle-label" class="subtitleLabel">Project Selector</span>
                <span id="members-table-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="28" data-gs-x="0" data-gs-y="37">
            <div class="grid-stack-item-content">
                <div id="product-projects-table" class="widget grid-stack-item-content"></div>
            </div>
        </div>

        <!-- Column -->
        <div class="grid-stack-item" data-gs-width="8" data-gs-height="2" data-gs-x="4" data-gs-y="34">
            <div id="team-multibar-subtitle" class="row subtitleRow">
                <span id="team-multibar-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                <span id="team-multibar-stitle-label" class="subtitleLabel">Teams Comparison</span>
                <span id="team-multibar-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="13" data-gs-x="4" data-gs-y="36">
            <div id="projects-roles-multibar" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="2" data-gs-x="4" data-gs-y="49">
            <div id="team-pie-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="team-pie-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                <span id="team-pie-stitle-label" class="subtitleLabel">Roles Summary</span>
                <span id="team-pie-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="13" data-gs-x="4" data-gs-y="51">
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

        // Load all the products of this product manager
        var pmanager_products_cntx = "pmanager-products-cntx";
        framework.data.observe(["view-pmanager-products"], function(frameData) {
            if (frameData.event != "data") {
                return;
            }

            var pList = frameData.data["view-pmanager-products"][0].data.values;

            var pIdList = [];
            for (var i = 0; i < pList.length; i++) {
                pIdList.push(pList[i].prid);
            }

            framework.data.updateContext(pmanager_products_cntx, {prid: pIdList});
        }, [currentUserCtx]);


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
        var teamSelect = '<div><span class="toolTitle"><p>Most significant Projects.</p></span></div><div><span class="toolRow">Select for comparison.</span></div>';
        addQTip($('#members-table-stitle-help'), "teamSelectTool", teamSelect);
        // Manager Comparison
        var managerComp = '<div><span class="toolTitle"><p>Manager team roles comparison.</p></span></div><div><span class="toolRow">This chart compares the number of team members per role and manager.</span></div>';
        addQTip($('#team-multibar-stitle-help'), "managerCompTool", managerComp);
        // Total Member Roles
        var memberRoles = '<div><span class="toolTitle"><p>Total members by role.</p></span></div><div><span class="toolRow">Accumulated number of team members by role.</span></div>';
        addQTip($('#team-pie-stitle-help'), "memberRolesTool", memberRoles);
        //External members
        var externalMembers = '<div><span class="toolTitle"><p>External developers</p></span></div><div><span class="toolRow">Number of external developers per product</span></div>';
        addQTip($('#external-members-stitle-help'), "externalMembers", externalMembers);
        //Internal members
        var internalMembers = '<div><span class="toolTitle"><p>Internal developers</p></span></div><div><span class="toolRow">Number of internal developers per product</span></div>';
        addQTip($('#internal-members-stitle-help'), "internalMembers", internalMembers);
        var workloadHelp = '<div><span class="toolTitle"><p>Products workload</p></span></div><div><span class="toolRow">Workload per product. The optimal value in 100%.</span></div>';
        addQTip($('#workload-help'), "workloadHelp", workloadHelp);


        var env = framework.dashboard.getEnv();
        framework.data.updateContext(orgCtx, {oid: env['oid']});


        // --------------------------------- UPPER SELECTOR RANGENV --------------------------------------
        var rangeNv_dom = document.getElementById("fixed-chart");
        var rangeNv_metrics = [
            {
                id: 'pmanager-activity',
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
                id: 'pmanager-products',
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
                id: 'pmanager-projects',
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
                id: 'pmanager-members',
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
                id: 'pmanager-projectmembers',
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
                id: 'pmanager-repositories'
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
                id: 'pmanager-projectrepositories',
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

            framework.data.observe([{id: 'pmanager-costs', max: 1 }], function(frameData) {
                if (frameData.event != "data") {
                    return;
                }
                currentCost = frameData.data["pmanager-costs"][0].data.values[0];
                loadProductsScatter();

            }, [currentUserCtx]);

            var loadProductsScatter = function() {

                var scatter_metrics = [
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
                                .range(["#DD0B14", "#FFEF00", "#009640"]);
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
                    "<div class='scattetTTLine'><i class='scatterTTIco fa fa-heartbeat orange'></i><h4>Health: ¬Math.round(_D.data['product-health'].values[0] * 100)/100¬</h4></div>" +
                    "<div class='scattetTTLine'><i class='scatterTTIco fa fa-eur red'></i><h4>Cost: ¬Math.round(_D.data['product-cost'].values[0] * 100)/100¬</h4></div>" +
                    "</div>",
                    image: "¬_D.data['product-cost'].info.prid.avatar¬",
                    xAxisGradient: ['red', 'orange', 'yellow', 'green'],
                    yAxisGradient: ['green', 'yellow', 'orange', 'red'],
                    showLegend: false,
                    showMaxMin: false,
                    onclick: function(data) {
                        //Extract prid
                        var prid, name;
                        for(var mid in data.data) {
                            if(data.data[mid].info.prid) {
                                prid = data.data[mid].info.prid.prid;
                                name = data.data[mid].info.prid.name;
                                break;
                            }
                        }
                        framework.dashboard.changeTo('product', {prid: prid, name: name});
                    }
                };

                var scatter = new framework.widgets.Scatter(scatter_dom, scatter_metrics, [orgCtx, timeCtx, pmanager_products_cntx], scatter_conf);

            };

            // -------------------------- WORKLOAD MULTIBAR ------------------------------------
            var changeScalePostModifier = function toPercentagePostModifier(resourceData) {

                // Data will be [0, 200] aprox, but we want 100 to be the y axis origin. Therefore, we change it to a
                // [-100, 100] so that 100 will be 0 in our new scale, and then modify the yAxisTickFormat function of the
                // widget to restore it to the [0,200] by adding 100
                var scale = d3.scale.linear().domain([0, 200]).range([-100, 100]);

                var values = resourceData['data']['values'];
                for(var x = 0; x < values.length; x++) {
                    values[x] = Math.random() * 200; //TODO: Remove: Just to generate random numbers until the metric is ready
                    values[x] = scale(values[x]);
                }
                //debugger;
                return resourceData;

            };
            var products_workload_dom = document.getElementById("products-workload");
            var products_workload_metrics = [
                {
                    id: 'product-quality', //TODO: product-workload
                    max: 1,
                    post_modifier: changeScalePostModifier
                }
            ];
            var products_workload_conf = {
                stacked: false,
                labelFormat: "¬_D.data.info.prid.name¬",
                showControls: false,
                height: 250,
                showLegend: true,
                showXAxis: false,
                yAxisTickFormat : function(d) {  return Math.round(d + 100); },
                x: function(metric, extra) {
                    return "Workload";
                },
                sort: 'asc'
            };
            new framework.widgets.MultiBar(products_workload_dom, products_workload_metrics,
                    [orgCtx, timeCtx, pmanager_products_cntx], products_workload_conf);

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
                                    property: "prid",
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
                                property: "prid",
                                as: "prid"
                            }
                        ]
                    }
                ],
                keepSelectedByProperty: "prid",
                selectable: true,
                minRowsSelected: 1,
                maxRowsSelected: 1,
                filterControl: true,
                initialSelectedRows: 1,
                showHeader: false,
                alwaysOneSelected: true,
                scrollButtons: true,
                height: 568
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [orgCtx, timeCtx, currentUserCtx], table_configuration);

            var toPercentagePostModifier = function toPercentagePostModifier(resourceData) {

                var values = resourceData['data']['values'];
                for(var x = 0; x < values.length; x++) {
                    values[x] = Math.round(values[x] * 100);
                }

                return resourceData;

            };

            // ----------------------------------- PRODUCT ACTIVITY WIDGET ----------------------------------------
            var pa_lines_dom = document.getElementById("product-activity-Lines");
            var pa_lines_metrics = [
                {
                    id: 'product-activity',
                    max: 60
                }
            ];
            var pa_lines_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 180,
                labelFormat: '¬_D.data.info.prid.name¬',
                area: true,
                showXAxis: false,
                colors: ['#004C8B']
            };
            new framework.widgets.LinesChart(pa_lines_dom, pa_lines_metrics,
                    [orgCtx, timeCtx, productsCtx], pa_lines_configuration);

            //  ----------------------------------- RELEASES TIMEBAR WIDGET ------------------------------------------
            var releasesLines_dom = document.getElementById("releases-chart");

            var releasesLines_metrics = [{
                id: 'product-success-rate',
                max: 20
            }];

            var releasesLines_configuration = {
                height: 50,
                color: function(val) {
                    var color = d3.scale.linear()
                            .domain([0, 0.5, 1])
                            .range(["#DD0B14", "#FFEF00", "#009640"]);
                    return color(val);
                },
                tooltip: '<p>Success Rate: ¬Math.round(_E.value * 100)¬%</p>' +
                         '<p>¬Widget.format.date(_E.time)¬ </p>',
                showAxis: true,
                showMaxMin: true,
                leyend: []
                //legend: ['Success', 'Broken']
            };
            var releasesLines = new framework.widgets.TimeBar(releasesLines_dom, releasesLines_metrics, [orgCtx, timeCtx, productsCtx], releasesLines_configuration);

            //  ---------------------------------- PRODUCT STAR CHART ------------------------------------------ PETA mucho
            var skills_star_dom = document.getElementById("radar-product-chart");
            //Specific skills
            var skills_star_metrics1 = [
                {
                    id: 'product-activity',
                    max: 1,
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'product-popularity-fake',
                    max: 1,
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'product-health',
                    max: 1,
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'product-quality',
                    max: 1,
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'product-timetomarket',
                    max: 1,
                    post_modifier: toPercentagePostModifier
                }
            ];
            //Average skills
            var skills_star_metrics2 = [
                {
                    id: 'pmanager-activity',
                    max: 1,
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'pmanager-popularity-fake',
                    max: 1,
                    aggr: 'avg',
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'pmanager-health',
                    max: 1,
                    aggr: 'avg',
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'pmanager-quality',
                    max: 1,
                    aggr: 'avg',
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'pmanager-timetomarket',
                    max: 1,
                    aggr: 'avg',
                    post_modifier: toPercentagePostModifier
                }
            ];

            var skills_star_metrics = skills_star_metrics1.concat(skills_star_metrics2); //Merge all in one array

            var skills_star_configuration = {
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
                        'pmanager-timetomarket':  'Time To Market'
                    }
                ],
                labels: ["Popularity", 'Health', "Activity", 'Quality', 'Time To Market' ],
                fillColor: ["rgba(30, 30, 30, 0.2)", "rgba(1, 150, 64, 0.4)"],
                pointColor: ["rgba(30, 30, 30, 0.4)", "rgba(1, 150, 64, 0.6)"],
                strokeColor: ["rgba(30, 30, 30, 0.3)", "#019640"],
                pointLabelFontColor: "#2876B8",
                pointLabelFontSize: 14
            };
            var skills_star = new framework.widgets.RadarChart(skills_star_dom, skills_star_metrics,
                    [orgCtx, timeCtx, productsCtx, currentUserCtx], skills_star_configuration);


            //  ----------------------------------- LIQUID GAUGE 1 (STATUS)-----------------------------------------

            var liquid1_dom = document.getElementById("liquid-1-chart");
            var liquid1_metrics = [
                {
                    id: 'product-success-rate',
                    max: 1,
                    aggr: "sum",
                    post_modifier: toPercentagePostModifier
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

            //  ----------------------------------- LIQUID GAUGE 11 (AVG STATUS)-----------------------------------------

            var liquid11_dom = document.getElementById("liquid-11-chart");
            var liquid11_metrics = [
                {
                    id: 'product-success-rate',
                    max: 1,
                    aggr: "sum",
                    //aggr: "avg"
                    post_modifier: toPercentagePostModifier
                }
            ];
            var liquid11_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor:'#DBF1B4',
                radius: 45,
                suffix: "% avg"
            };
            new framework.widgets.LiquidGauge(liquid11_dom, liquid11_metrics,
                    [orgCtx, timeCtx, productsCtx], liquid11_configuration);

            //  ----------------------------------- LIQUID GAUGE 2 (HEALTH)------------------------------------------
            var liquid2_dom = document.getElementById("liquid-2-chart");
            var liquid2_metrics = [
                {
                    id: 'product-health',
                    max: 1,
                    aggr: "sum",
                    post_modifier: toPercentagePostModifier
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

            //  ----------------------------------- LIQUID GAUGE 22 (AVG HEALTH)------------------------------------------
            var liquid22_dom = document.getElementById("liquid-22-chart");
            var liquid22_metrics = [
                {
                    //id: 'pmanager-health',
                    //id: 'product-health-fake',
                    id: 'product-health',
                    max: 1,
                    //aggr: "avg",
                    post_modifier: toPercentagePostModifier
                }
            ];
            var liquid22_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor: '#DBF1B4',
                radius: 45,
                suffix: "% avg"
            };
            new framework.widgets.LiquidGauge(liquid22_dom, liquid22_metrics,
                    [orgCtx, timeCtx, productsCtx], liquid22_configuration);

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


            // CYTOCHART CONFIG FOR DIRECTOR
            function configDirectorCytoChart(productsAux, theProduct, edges) {
                var cytograph1_metrics = [];
                // Add edges
                var cytograph1_configuration = {
                    'nodes': [],
                    'edges': edges
                };
                for (var id in productsAux) {
                    // Add Metric
                    var aux;
                    var productMetricId;
                    var tooltip;
                    var volume = null;

                    if (id == theProduct) {
                        volume = '_static_';
                        aux = {
                            max: 1,
                            aggr: 'sum',
                            prid: id,
                            from: framework.data.getContextData(timeCtx)['from'],
                            to: framework.data.getContextData(timeCtx)['to']
                        };
                        productMetricId = framework.utils.resourceHash('product-developers', aux);
                        aux['id'] = 'product-developers';
                        tooltip = "Product \"¬_D.data.info.prid.name¬\"<br/> Staff: ¬_D.data.values[0]¬";

                    } else {
                        aux = {
                            max: 1,
                            aggr: 'sum',
                            pjid: id,
                            from: framework.data.getContextData(timeCtx)['from'],
                            to: framework.data.getContextData(timeCtx)['to']
                        };
                        productMetricId = framework.utils.resourceHash('project-developers', aux);
                        aux['id'] = 'project-developers';
                        tooltip = "Project \"¬_D.data.info.pjid.name¬\"<br/> Staff: ¬_D.data.values[0]¬";
                    }


                    if (productMetricId == null) {
                        return null;
                    }
                    cytograph1_metrics.push(aux);

                    // Add Node
                    cytograph1_configuration.nodes.push(
                            {
                                id: productsAux[id].name,
                                avatar:productsAux[id].avatar,
                                shape:"ellipse",
                                volume: volume,
                                metric: productMetricId,
                                tooltip: tooltip || productsAux[id].tooltip || ""
                            }
                    )
                }

                return {'config': cytograph1_configuration, 'metrics': cytograph1_metrics};
            }

            var cytocharts = [];
            framework.data.observe(["view-pmanager-products"], function(framework_data) {

                if (framework_data.event != "data") {
                    return;
                }

                var frameData = framework_data['data']['view-pmanager-products'][0]['data'];

                // Remove previous cytocharts
                var toRemove = null;
                while( (toRemove = cytocharts.pop()) != null ) {
                    toRemove.delete();
                }
                // 3 cytochart in the same line or less
                var gridstackWidth = 4;
                if (frameData.values.length < 3){
                    gridstackWidth = 12 / frameData.values.length;
                }

                for(var i = 0; i < frameData.values.length && i < 3; i++) {

                    var data = frameData.values[i];
                    var cytograph_dom = document.getElementById("cytograph" + (i+1));
                    cytograph_dom.parentElement.setAttribute("data-gs-width", gridstackWidth);
                    cytograph_dom.parentElement.setAttribute("data-gs-x", gridstackWidth*i);
                    $(cytograph_dom.parentElement).get(0).style['display'] = 'inherit';

                    var theProductId = data['prid'];

                    var productsAux = {};
                    productsAux[data['prid']] = {
                        "id": data['prid'],
                        "name": data['prid'],
                        "avatar": data['avatar']
                    };

                    var project_metric = [{
                        id: "view-product-projects",
                        prid: theProductId,
                        from: framework.data.getContextData(timeCtx)['from'],
                        to: framework.data.getContextData(timeCtx)['to']
                    }];

                    //Request product manager products
                    framework.data.observe(project_metric, function(cytograph_dom, theProductId, productsAux, framework_data) {

                        if (framework_data.event != "data") {
                            return;
                        }

                        var frameData = framework_data['data']['view-product-projects'][0]['data'];

                        var edges = [];

                        for(var j = 0; j < frameData.values.length; j++) {

                            var project_data = frameData.values[j];

                            edges.push({
                                source: theProductId,
                                target: project_data['pjid']
                            });

                            productsAux[project_data['pjid']] = {
                                "id": project_data['pjid'],
                                "name": project_data['pjid'],
                                "avatar": project_data['avatar']
                            }

                        }

                        var configPM = configDirectorCytoChart(productsAux, theProductId, edges);

                        if (configPM == null){
                            console.log("error loading cytoChart1");
                        } else {
                            var cytograph_metrics = configPM.metrics;
                            var cytograph_configuration = configPM.config;

                            var cytograph = new framework.widgets.CytoChart2(cytograph_dom, cytograph_metrics,
                                    [], cytograph_configuration);

                            cytocharts.push(cytograph);
                        }


                    }.bind(null, cytograph_dom, theProductId, productsAux));

                }
                for(var i = frameData.values.length; i < 3; i++) {
                    var cytograph_dom = document.getElementById("cytograph" + (i+1));
                    $(cytograph_dom.parentElement).get(0).style['display'] = 'none';
                }


            }, [timeCtx, currentUserCtx]);


            //  --------------------------- PRODUCT / PROJECTS TABLE ---------------------------------

            var superContextData = {};
            var widgetList = [];

            // This method combines different contexts into a supercontext
            var superContextHandler = function(data, changes, contextId) {

                // Get changed table id
                var contextParts = contextId.split("~");
                var dataId = contextParts[contextParts.length - 1];

                // Get the number of selected rows in the changed table
                var nSelectedInTable = $(".multitable-container > div[data-id='"+dataId+"']").find("tr.selected").length;

                // Update the number in the uper icon selector
                $("#product-projects-table .multitable-selector li[data-id='"+dataId+"'] span.number-selected").text(nSelectedInTable);

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
                        } else if(superContextData[context][prop] instanceof Array) {
                            mergedData[prop] = superContextData[context][prop].slice(0); //Clone the array
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
                    var selectorContainer = $('<div class="multitable-selector jcarousel"></div>');
                    var selector = $('<ul></ul>');
                    var container = $('<div class="multitable-container"></div>');

                    selectorContainer
                            .append(selector);

                    multitable
                        .empty()
                        .append('<a class="jcarousel-prev" href="#">' +
                                    '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>' +
                                    '<span class="sr-only">Previous</span>' +
                                '</a>' +
                                '<a class="jcarousel-next" href="#">' +
                                    '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>' +
                                    '<span class="sr-only">Next</span>' +
                                '</a>')
                        .append(selectorContainer)
                        .append(container);


                    // Now we can start adding things
                    var products = event.data['view-pmanager-products'][Object.keys(event.data['view-pmanager-products'])[0]]['data']['values'];

                    // Function to change the selected table
                    var changeSelectedTable = function() {

                        if(selectedId != null) { //Previous selected
                            selector.find("li[data-id='"+selectedId+"']").removeClass("selected");
                            container.find("div[data-id='"+selectedId+"']").hide();
                        }

                        var id = $(this).data('id');

                        selector.find("li[data-id='"+id+"']").addClass("selected");
                        container.find("div[data-id='"+id+"']").show();

                        selectedId = id;
                    };

                    for(var x = 0; x < products.length; x++) {

                        var name = products[x]['name'];
                        var avatar = products[x]['avatar'];
                        var id = products[x]['prid'];
                        var context = "productprojectstable~" + id;

                        var avatarSelector = $('<li class="multitable-img-selector" data-id="'+id+'"></li>')
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
                                                 property: "pjid",
                                                 as: "pjid"
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
                                            property: "pjid", //TODO
                                            as: "pjid"
                                        }
                                    ]
                                }
                            ],
                            keepSelectedByProperty: "pjid",
                            selectable: true,
                            minRowsSelected: 0,
                            maxRowsSelected: 8,
                            initialSelectedRows: (x === 0 ? 3 : 0), //Only the first table has elements selected
                            showHeader: false,
                            filterControl: false,
                            height: 493
                        };

                        //Create the widget
                        var widget = new framework.widgets.Table(product_projects_table_dom, product_projects_table_metrics, [orgCtx, timeCtx, ], product_projects_table_configuration);

                        // Add the widget to the widget list to then be able to destroy all the widgets in case of context change
                        widgetList.push(widget);
                    }

                    // Uses jcarousel library (http://sorgalla.com/jcarousel)
                    var carousel = $(multitable).find('.jcarousel').jcarousel({
                        // Core configuration goes here
                    });

                    $(multitable).find('.jcarousel-prev').jcarouselControl({
                        target: '-=2',
                        carousel: carousel
                    });

                    $(multitable).find('.jcarousel-next').jcarouselControl({
                        target: '+=2',
                        carousel: carousel
                    });

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
                labelFormat: "¬_D.data.info.pjid.name¬",
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