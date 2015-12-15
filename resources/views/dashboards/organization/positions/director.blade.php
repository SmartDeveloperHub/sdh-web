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

        <div id="metricBoxes" class="grid-stack">
            <div class="grid-stack-item" data-gs-width="11" data-gs-height="4" data-gs-x="0" data-gs-y="1">
                <div id="metTitRow" class="titleRow grid-stack-item-content">
                    <span id="metricsTitIco" class="titleIcon octicon octicon-dashboard"></span>
                    <span id="metricsTitLabel" class="titleLabel">Metrics</span>
                </div>
            </div>

            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="0" data-gs-y="5">
                <div id="products-ctr" class="grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="3" data-gs-y="5">
                <div id="team-members-ctr" class="grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="6" data-gs-y="5">
                <div id="personnel-cost-ctr" class="grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="9" data-gs-y="5">
                <div id="releases-ctr" class="grid-stack-item-content"></div>
            </div>

            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="0" data-gs-y="13">
                <div id="avg-health-ctr" class="grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="3" data-gs-y="13">
                <div id="avg-team-ctr" class="grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="6" data-gs-y="13">
                <div id="contributors-ctr" class="grid-stack-item-content"></div>
            </div>
            <div class="grid-stack-item" data-gs-width="3" data-gs-height="8" data-gs-x="9" data-gs-y="13">
                <div id="companies-ctr" class="grid-stack-item-content"></div>
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

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="3" data-gs-x="4" data-gs-y="26">
            <div id="releases-chart-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="releases-chart-stitle-ico" class="subtitleIcon fa fa-hourglass-half"></span>
                <span id="releases-chart-stitle-label" class="subtitleLabel">Status History</span>
                <span id="releases-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="7" data-gs-x="4" data-gs-y="29">
            <div id="releases-chart" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="2" data-gs-height="2" data-gs-x="5" data-gs-y="36">
            <div id="radar-product-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="radar-product-stitle-ico" class="subtitleIcon fa fa-line-chart"></span>
                <span id="radar-product-stitle-label" class="subtitleLabel">Product Profile</span>
                <span id="radar-product-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="18" data-gs-x="4" data-gs-y="38">
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

    </div>

    <!-- Section: Team Members -->
    <div class="grid-stack">

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="4" data-gs-x="0" data-gs-y="0">
            <div id="peplTitRow" class="grid-stack-item-content titleRow">
                <span id="peopleTitIco" class="titleIcon fa fa-users"></span>
                <span id="peopleTitLabel" class="titleLabel">Team Members</span>
            </div>
        </div>

        <!-- Subsection: Managers -->

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="2" data-gs-x="0" data-gs-y="4">
            <div id="managers-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="managers-stitle-ico" class="subtitleIcon fa fa-sitemap"></span>
                <span id="managers-stitle-label" class="subtitleLabel">Managers</span>
                <span id="managers-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="0" data-gs-y="6">
            <div id="cytograph1" class="widget grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="4" data-gs-y="6">
            <div id="cytograph2" class="widget grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="15" data-gs-x="8" data-gs-y="6">
            <div id="cytograph3" class="widget grid-stack-item-content"></div>
        </div>

        <!-- Subsection: Positions -->
        <div class="grid-stack-item" data-gs-width="12" data-gs-height="2" data-gs-x="0" data-gs-y="21">
            <div id="positions-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="positions-stitle-ico" class="subtitleIcon fa fa-graduation-cap"></span>
                <span id="positions-stitle-label" class="subtitleLabel">Positions</span>
                <span id="positions-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="10" data-gs-x="0" data-gs-y="23">
            <div id="position-members-lines" class="widget grid-stack-item-content"></div>
        </div>

        <!-- Subsection: Manager Selector -->

        <!-- Column -->
        <div class="grid-stack-item" data-gs-width="4" data-gs-height="3" data-gs-x="0" data-gs-y="33">
            <div id="members-table-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="members-table-stitle-ico" class="subtitleIcon fa fa-hand-pointer-o"></span>
                <span id="members-table-stitle-label" class="subtitleLabel"> Manager Selector</span>
                <span id="members-table-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="30" data-gs-x="0" data-gs-y="36">
            <div class="grid-stack-item-content">
                <div id="team-members-table" class="widget"></div>
            </div>
        </div>

        <!-- Column -->
        <div class="grid-stack-item" data-gs-width="8" data-gs-height="2" data-gs-x="4" data-gs-y="33">
            <div id="team-multibar-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="team-multibar-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                <span id="team-multibar-stitle-label" class="subtitleLabel">Roles Breakdown</span>
                <span id="team-multibar-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="13" data-gs-x="4" data-gs-y="35">
            <div id="projects-roles-multibar" class="widget grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="2" data-gs-x="4" data-gs-y="48">
            <div id="team-pie-subtitle" class="grid-stack-item-content subtitleRow">
                <span id="team-pie-stitle-ico" class="subtitleIcon fa fa-chain-broken"></span>
                <span id="team-pie-stitle-label" class="subtitleLabel">Roles Summary</span>
                <span id="team-pie-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="8" data-gs-height="13" data-gs-x="4" data-gs-y="50">
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
        var teamMembersCtx = "team-members-context";
        var currentUserCtx = "current-user-context";

        framework.data.updateContext(currentUserCtx, {uid: framework.dashboard.getEnv()['user_id']});

        //Show header chart and set titles
        setTitle("Home");
        setSubtitle("Director");
        showHeaderChart();

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
        var productAnalysis = '<div><span class="toolTitle"><p>This chart shows the most significant products.</p></span></div><div><span class="toolRow"><span class="ico fa fa-eur red"></span><strong>Cost</strong>. Directly proportional to the size of the circles</span></div><div><span class="toolRow"><span class="ico fa fa-heartbeat orange"></span><strong>Health</strong>. Colour. <span class="red">Red-bad</span> <span class="green">Green-good</span></span></div><div><span class="toolRow"><span class="ico fa fa-balance-scale green"></span><strong>Quality</strong>. Y axis. Up is better.</span></div><div><span class="toolRow"><span class="ico fa fa-hourglass-start violet"></span><strong>Time To Market</strong>. X axis. Right is better.</span></div>';
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
        addQTip($('#liquid1-chart-stitle-help'), "prodLiqSuccesTool", liquidStatus);
        // Product health
        var liquidHealth = '<div><span class="toolTitle"><p>Product health.</p></span></div><div><span class="toolRow">Percentage that represent the health of the product.</span></div>';
        addQTip($('#liquid2-chart-stitle-help'), "prodLiqBrokenTool", liquidHealth);
        // Managers Cytocharts
        var managerCyto = '<div><span class="toolTitle"><p>Most significant managers.</p></span></div><div><span class="toolRow">This chart analyzes the most important products for each manager.</span></div><div><span class="toolRow">The center bubble represents a manager and he bubbles attached to it represent a product. The size of the product bubble depends on the number of members of the staff of that product.</span></div></div>';
        addQTip($('#managers-stitle-help'), "prodManagersTool", managerCyto);
        // Positions Lines
        var positionLine = '<div><span class="toolTitle"><p>Members by positions.</p></span></div><div><span class="toolRow">This chart analyzes the distribution of members by position during the selected period of time.</span></div>';
        addQTip($('#positions-stitle-help'), "positionsLineTool", positionLine);
        // Manager Selector
        var managerSelect = '<div><span class="toolTitle"><p>List of Managers.</p></span></div><div><span class="toolRow">Select for comparison.</span></div>';
        addQTip($('#members-table-stitle-help'), "managerSelectTool", managerSelect);
        // Manager Comparison
        var managerComp = '<div><span class="toolTitle"><p>Manager team roles comparison.</p></span></div><div><span class="toolRow">This chart compares the number of team members per role and manager.</span></div>';
        addQTip($('#team-multibar-stitle-help'), "managerCompTool", managerComp);
        // Total Member Roles
        var memberRoles = '<div><span class="toolTitle"><p>Total members by role.</p></span></div><div><span class="toolRow">Accumulated number of team members by role.</span></div>';
        addQTip($('#team-pie-stitle-help'), "memberRolesTool", memberRoles);

        var env = framework.dashboard.getEnv();
        //console.log(env);
        framework.data.updateContext(orgCtx, {oid: env['oid']});


        // --------------------------------- UPPER SELECTOR RANGENV --------------------------------------
        var rangeNv_dom = document.getElementById("fixed-chart");
        var rangeNv_metrics = [
            {
                id: 'director-activity', //TODO: director activity metric
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
                id: 'director-products',
                max: 1
            }];
            var products_conf = {
                label: 'Products',
                decimal: 2,
                icon: 'fa fa-industry',
                iconbackground: '#F75333',
                background: 'transparent'
            };
            var products = new framework.widgets.CounterBox(products_dom, products_metrics, [orgCtx, timeCtx, currentUserCtx], products_conf);

            // ------------------------------------ TEAM MEMBERS -------------------------------------------
            var team_members_dom = document.getElementById("team-members-ctr");
            var team_members_metrics = [{
                id: 'director-members', //TODO member-members? :O
                max: 1
            }];
            var team_members_conf = {
                label: 'Team members',
                decimal: 0,
                icon: 'octicon octicon-organization',
                iconbackground: '#019640',
                background: 'transparent'
            };
            var team_members = new framework.widgets.CounterBox(team_members_dom, team_members_metrics, [orgCtx, timeCtx, currentUserCtx], team_members_conf);

            // ---------------------------------------- RELEASES -------------------------------------------
            var some1_dom = document.getElementById("releases-ctr");
            var some1_metrics = [{
                id: 'passed-builds',  //TODO: Nº Releases: total builds passed in master branch. userReleases o userPassedBuilds o algo así
                max: 1
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
                id: 'director-costs',  //TODO: Ad hoc? o userTeamCost?. Total de coste por team member 25*nºmembers * (dias del rango seleccionado)
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

            // ------------------------------------------ CONTRIBUTORS ----------------------------------------
            var some2_dom = document.getElementById("contributors-ctr");
            var some2_metrics = [{
                id: 'director-externals',
                max: 1
            }];
            var some2_conf = {
                label: 'External Contributors',
                decimal: 0,
                icon: 'fa-user',
                iconbackground: '#737373',
                background: 'transparent'
            };
            var some2 = new framework.widgets.CounterBox(some2_dom, some2_metrics, [orgCtx, timeCtx, currentUserCtx], some2_conf);

            // --------------------------------- EXTERNAL COMPANIES --------------------------------
            var some2_dom = document.getElementById("companies-ctr");
            var some2_metrics = [{
                id: 'director-externalcompanies-fake',  //TODO: dummy? AdHoc? userExternalContributorCompanies? básicamente sacar del dominio del mail el nombre de  la empresa exerna.
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
            var some2 = new framework.widgets.CounterBox(some2_dom, some2_metrics, [orgCtx, timeCtx, currentUserCtx], some2_conf);

            // ------------------------------- AVG TEAM MEMBERS PER PRODUCT-------------------------------------
            var avgteam_dom = document.getElementById("avg-team-ctr");
            var avgteam_metrics = [{
                id: 'director-productmembers',  //TODO: userProductsMembers AVG
                max: 1,
                aggr: 'avg'
            }];
            var avgteam_conf = {
                label: 'Team Members Per Product',
                decimal: 0,
                icon: 'fa-users',
                iconbackground: '#6895BA',
                background: 'transparent'
            };
            var avgTeam = new framework.widgets.CounterBox(avgteam_dom, avgteam_metrics, [orgCtx, timeCtx, currentUserCtx], avgteam_conf);

            // ------------------------------------ AVG HEALTH PER PRODUCT -------------------------------------------
            var avghealth_dom = document.getElementById("avg-health-ctr");
            var avghealth_metrics = [{
                id: 'director-health',
                max: 1,
                aggr: 'avg'
            }];
            var avghealth_conf = {
                label: 'Health Per Product',
                decimal: 0,
                icon: 'fa-heart',
                iconbackground: '#29BB67',
                background: 'transparent'
            };
            var avgHealth = new framework.widgets.CounterBox(avghealth_dom, avghealth_metrics, [orgCtx, timeCtx, currentUserCtx], avghealth_conf);



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
                for (var id in productsAux) {
                    // Add Metric
                    var aux = {
                        max: 1,
                        aggr: 'sum',
                        //from: '', // esto no influye en el resourceHash TODO??
                        //to: '',
                        prid: id
                    };
                    var aux2 = {
                        max: 1,
                        aggr: 'sum',
                        uid: id
                    };
                    var productMetricId;
                    if (id == theProductManagerId) {
                        productMetricId = "_static_";
                        //productMetricId = framework.utils.resourceHash('pmanager-products', aux2); // Hay un problema, parece que si los dos widgets cytocharts escuchan pmanager-products, el segundo no machea con el hash que viene del framework... TODO??
                        aux2['id']= 'pmanager-products';
                        aux = aux2;
                    } else {
                        productMetricId = framework.utils.resourceHash('product-developers', aux);
                        aux['id']= 'product-developers';
                    }
                    if (productMetricId == null) {
                        return null;
                    };
                    cytograph1_metrics.push(aux);

                    cytograph1_configuration.tooltip = "Staff: ¬_D.data.values[0]¬";

                    // Add Node
                    cytograph1_configuration.nodes.push(
                        {
                            id: productsAux[id].name,
                            avatar:productsAux[id].avatar,
                            shape:"ellipse",
                            volume: productMetricId,
                            tooltip: productsAux[id].tooltip || ""
                        }
                    )
                }
                console.log(JSON.stringify(cytograph1_metrics));
                console.log(JSON.stringify(cytograph1_configuration))
                return {'config': cytograph1_configuration, 'metrics': cytograph1_metrics};
            };

            // CYTOCHART1 INITIALIZATION
            // TODO get
            // product managers del director
            // mejores products de los  3 mejores P.Managers
            // Info de cada uno de los productos
            var cytograph1_dom = document.getElementById("cytograph1");
            var theProductManagerId = '1011';
            var edges = [
                { source: '1011', target: 'product-sdh' },
                { source: '1011', target: 'product-phoenix' }
            ];
            var productsAux = {
                '1011':{
                  "name": '1011',
                  "tooltip": "Maria Jose Gonzalez",
                  "nick": "mgonzper",
                  "avatar": "https://image.freepik.com/iconos-gratis/avatar-mujer_318-81274.jpg",
                  "email": [
                    "mgonzper@isban.es"
                  ],
                  "positionsByOrgId": {
                    "1": [
                      2
                    ]
                  }
                },
                "product-sdh": {
                  "name": "product-sdh",
                  "tooltip": "Software Development Support Product",
                  "avatar": "https://avatars1.githubusercontent.com/u/12413297?v=3&s=200"
                },
                "product-phoenix": {
                  "name": "product-phoenix",
                  "tooltip": "phoenix",
                  "avatar": "https://cdn1.iconfinder.com/data/icons/business-bicolor-4/512/presentation-512.png"
                }
            };

            var configPM = configDirectorCytoChart(productsAux, theProductManagerId, edges);
            //console.log(configPM)

            if (configPM == null){
                console.log("error loading cytoChart1");
            } else {
                var cytograph1_metrics = configPM.metrics;
                var cytograph1_configuration = configPM.config;
                var cytograph1 = new framework.widgets.CytoChart2(cytograph1_dom, cytograph1_metrics,
                        [orgCtx, timeCtx], cytograph1_configuration);
            }

            // CYTOCHART2 INITIALIZATION
            var cytograph2_dom = document.getElementById("cytograph2");
            var theProductManagerId = '1013';
            var edges = [
                { source: '1013', target: 'product-jenkins' }
            ];
            var productsAux = {
                '1013':{
                  "name": '1013',
                  "tooltip": "Julian García",
                  "nick": "jgarcia",
                  "avatar": "https://image.freepik.com/iconos-gratis/hombre-avatar-oscuro_318-9118.jpg",
                  "email": [
                    "juliangarcia@gmail.com"
                  ],
                  "positionsByOrgId": {
                    "1": [
                      2
                    ]
                  }
                },
                "product-jenkins":{
                    "name": "product-jenkins",
                    "tooltip": "Jenkins",
                    "avatar": "https://cdn1.iconfinder.com/data/icons/business-bicolor-4/512/presentation-512.png"
                }
            };

            var configPM = configDirectorCytoChart(productsAux, theProductManagerId, edges);

            if (configPM == null){
                console.log("error loading cytoChart2");
            } else {
                var cytograph2_metrics = configPM.metrics;
                var cytograph2_configuration = configPM.config;

                var cytograph2 = new framework.widgets.CytoChart2(cytograph2_dom, cytograph2_metrics,
                        [orgCtx, timeCtx], cytograph2_configuration);
            }

            // CYTOCHART3 INITIALIZATION
            /*var cytograph3_dom = document.getElementById("cytograph3");
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
                    'avatar': "https://pbs.twimg.com/profile_images/1554448422/asun_oeg_400x400.png",
                    tooltip: "Asunción Gómez"
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
            if (configPM == null){
                console.log("error loading cytoChart3");
            } else {
                var cytograph3_metrics = configPM.metrics;
                var cytograph3_configuration = configPM.config;

                var cytograph3 = new framework.widgets.CytoChart2(cytograph3_dom, cytograph3_metrics,
                        [orgCtx, timeCtx], cytograph3_configuration);
            }*/

            // ------------------------------------------ SCATTER PLOT -------------------------------------------
            var scatter_dom = document.getElementById("scatter-plot");
            var director_products_cntx = "director-products-cntxt";
            framework.data.observe(["view-director-products"], function(frameData) {
                if (frameData.event == "loading") {
                    return;
                }
                //console.log("getting view-director-products");
                var pList = frameData.data["view-director-products"][0].data.values;
                //console.log("pList: " + JSON.stringify(pList));
                var pIdList = [];
                for (var i = 0; i < pList.length; i++) {
                    pIdList.push(pList[i].productid);
                }
                //console.log("pList: " + JSON.stringify(pIdList));
                framework.data.updateContext(director_products_cntx, {prid: pIdList});
            }, [currentUserCtx]);
            //framework.data.updateContext(director_products_cntx, {prid: ['product-jenkins','product-sdh']}); //TODO: this wont be needed
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
                    return color(Math.random());
                },
                size: function(data) {
                    return Math.random();
                },
                shape: function(data) {
                    return 'circle';
                },
                x: function(data) {
                    //return (data['product-timetomarket']['values'][0] > 100 ? data['repodevelopers']['values'][0]/10 : data['repodevelopers']['values'][0]);
                    return (data['product-timetomarket']['values'][0]);
                    //return Math.random();
                },
                xAxisLabel: "Time to market",
                y: function(data) {
                    //return (data['product-quality']['values'][0] > data['repopassedexecutions']['values'][0]/10 ? 100 : data['repopassedexecutions']['values'][0]);
                    return (data['product-quality']['values'][0]);
                    //return Math.random();
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
                // TODO try to add blur somewhere
                tooltip: "<div class='scatterTooltip' style='text-align: center;'>" +
                "<img class='img-responsive center-block' height='60' width='60' src=\"¬_D.data['product-cost'].info.prid.avatar¬\" />" +
                "<h3>¬_D.data['product-cost'].info.prid.name¬</h3>" +
                "<div class='scattetTTLine'><i class='scatterTTIco fa fa-balance-scale green'></i><h4>Quality: ¬Math.round(_D.y * 100)/100¬</h4></div>" +
                "<div class='scattetTTLine'><i class='scatterTTIco fa fa-hourglass-start violet'></i><h4>Time to market: ¬Math.round(_D.x * 100)/100¬</h4></div>" +
                "<div class='scattetTTLine'><i class='scatterTTIco fa fa-heartbeat orange'></i><h4>Health: ¬Math.round(_D.y * 100)/100¬</h4></div>" +
                "<div class='scattetTTLine'><i class='scatterTTIco fa fa-eur red'></i><h4>Cost: ¬Math.round(_D.x * 100)/100¬</h4></div>" +
                "</div>",
                image: "¬_D.data['product-cost'].info.prid.avatar¬",
                xAxisGradient: ['red', 'orange', 'yellow', 'green'],
                yAxisGradient: ['green', 'yellow', 'orange', 'red'],
                showLegend: false,
                showMaxMin: false
            };

            var scatter = new framework.widgets.Scatter(scatter_dom, scatter_metrics, [orgCtx, timeCtx, director_products_cntx], scatter_conf);

            //  ----------------------------------- PRODUCTS TABLE ------------------------------------------
            var table_dom = document.getElementById("products-table");
            var table_metrics = ['view-director-products']; //TODO: choose resource
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

            //  ---------------------------------- PRODUCT STAR CHART ------------------------------------------
            var skills_star_dom = document.getElementById("radar-product-chart");
            //Specific skills
            var skills_star_metrics1 = [
                {
                    id: 'product-activity',
                    max: 1
                },
                {
                    id: 'product-popularity-fake',
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
            //Average skills
            var skills_star_metrics2 = [
                {
                    id: 'director-activity',
                    max: 1
                },
                {
                    id: 'director-popularity-fake',
                    max: 1,
                    aggr: 'avg'
                },
                {
                    id: 'director-health',
                    max: 1,
                    aggr: 'avg'
                },
                {
                    id: 'director-quality',
                    max: 1,
                    aggr: 'avg'
                },
                {
                    id: 'director-timetomarket',
                    max: 1,
                    aggr: 'avg'
                }
            ];

            var skills_star_metrics = skills_star_metrics1.concat(skills_star_metrics2); //Merge all in one array

            var skills_star_configuration = {
                height: 200,
                radius: 180,
                labelsAssoc: [{
                    'product-activity':      'Activity',
                    'product-popularity-fake':    'Popularity',
                    'product-health':        'Health',
                    'product-quality':       'Quality',
                    'product-timetomarket':  'Time To Market'
                },
                {
                    'director-activity':          'Activity',
                    'director-popularity-fake':        'Popularity',
                    'director-health':            'Health',
                    'director-quality':           'Quality',
                    'director-timetomarket':      'Time To Market'
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
            var test_dom = document.getElementById("liquid-1-chart");
            var test_metrics = [
                {
                    id: 'product-success-rate',
                    max: 1
                }
            ];
            var test_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 1,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor:'#DBF1B4',
                radius: 45,
                displayPercent: false
            };
            var test = new framework.widgets.LiquidGauge(test_dom, test_metrics,
                    [orgCtx, timeCtx, productsCtx], test_configuration);

            //  ----------------------------------- LIQUID GAUGE 2 ------------------------------------------
            var test_dom = document.getElementById("liquid-2-chart");
            var test_metrics = [
                {
                    id: 'product-health',
                    max: 1
                }
            ];
            var test_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 1,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: '#333333',
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor: '#333333',
                radius: 45
            };
            var test = new framework.widgets.LiquidGauge(test_dom, test_metrics,
                    [orgCtx, timeCtx, productsCtx], test_configuration);

            // ----------------------------- TEAM MEMBERS LINES CHART ----------------------------------
            // TODO 2 gráficas externals y developers total a lo largo del tiempo line interpolate or area. 1 linea por product en cada una de las line charts
            var team_members_lines_dom = document.getElementById("position-members-lines");
            var team_members_lines_metrics = [
                {
                    id: 'product-externals', // TODO por cada product
                    max: 40,
                    uid: 200
                },
                {
                    id: 'product-developers',
                    max: 40,
                    uid: 200
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

            // ------------------------------- TEAM MEMBERS ROLES (pie Chart) -------------------------------------
            var team_members_pie_dom = document.getElementById("team-members-pie");
            var team_members_pie_metrics = [
                {
                    id: 'pmanager-stakeholders',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'pmanager-swdevelopers',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'pmanager-pjmanagers',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'pmanager-swarchitects',
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
                    [orgCtx, timeCtx, teamMembersCtx], team_members_pie_configuration);

            //  ------------------------------ PRODUCT MANAGERS TABLE --------------------------------------
            var team_members_table_dom = document.getElementById("team-members-table");
            var team_members_table_metrics = ['view-director-productmanagers']; //TODO: choose resource
            var team_members_table_configuration = {
                columns: [
                    {
                        label: "",
                        link: {
                         img: "avatar", //or label
                         href: "developer",
                         env: [
                             {
                                 property: "userid",
                                 as: "uid"
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
                                property: "userid", //TODO
                                as: "uid"
                            }
                        ]
                    }
                ],
                keepSelectedByProperty: "userid",
                selectable: true,
                minRowsSelected: 1,
                maxRowsSelected: 8,
                initialSelectedRows: 3,
                showHeader: false,
                filterControl: false,
                scrollButtons: true,
                height: 620
            };
            var team_members_table = new framework.widgets.Table(team_members_table_dom, team_members_table_metrics, [orgCtx, timeCtx, currentUserCtx], team_members_table_configuration);

            // --------------------------ROLES MULTIBAR ------------------------------------
            var project_roles_multibar_dom = document.getElementById("projects-roles-multibar");
            var project_roles_multibar_metrics = [
                {
                    id: 'pmanager-swdevelopers',
                    max: 1
                },
                {
                    id: 'pmanager-swarchitects',
                    max: 1
                },
                {
                    id: 'pmanager-pjmanagers',
                    max: 1
                },
                {
                    id: 'pmanager-stakeholders',
                    max: 1
                }
            ];
            var roles = {
                'pmanager-swdevelopers' : 'Software developer',
                'pmanager-swarchitects': 'Software architect',
                'pmanager-pjmanagers': 'Project manager',
                'pmanager-stakeholders': 'Stakeholder'
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
                    [orgCtx, timeCtx, teamMembersCtx], project_roles_multibar_conf);
        };
    }

@stop
