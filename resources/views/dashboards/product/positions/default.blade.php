@extends('layouts.template')

@section('require')
    [
    "//ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
    "css!assets/css/dashboards/organization-dashboard",
    "sdh-framework/widgets/RangeNv/rangeNv",
    "sdh-framework/widgets/Table/table",
    "sdh-framework/widgets/MultiBar/multibar",
    "sdh-framework/widgets/LinesChart/linesChart",
    "sdh-framework/widgets/PieChart/piechart",
    "sdh-framework/widgets/CounterBox/counterbox",
    "css!assets/css/info-box",
    "css!assets/css/dashboards/product-dashboard",
    "css!vendor/qtip2/jquery.qtip.min.css",
    ]
@stop

@section('html')
    <div class="row info-box">
        <div class="row">
            <div class="com-widget widget static-info-widget col-sm-12">
                <div class="row">
                    <div class="col-sm-2 avatarBox">
                        <div id="avatar" class="avatar"></div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row static-info-line">
                            <span class="theicon fa fa-pencil-square-o" style="color: #019640"></span><span class="thelabel">Created:</span><span class="theVal blurado" id="product-created">------</span>
                        </div>
                        <div class="row static-info-line">
                            <span class="theicon fa fa-user-secret" style="color: #8A1978"></span><span class="thelabel">Director:</span><span class="pAvatar" id="product-director-avatar"></span><span class="theVal blurado completName" id="product-director">--------</span>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row static-info-line">
                            <span class="theicon fa fa-cubes" style="color: #C0485E"></span><span class="thelabel">Number of projects:</span><span class="theVal blurado" id="product-projects-number">--------</span>
                        </div>
                        <div class="row static-info-line">
                            <span class="theicon fa fa-user"></span><span class="thelabel">Manager:</span><span class="pAvatar" id="product-manager-avatar"></span><span class="theVal blurado completName" id="product-manager">----</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="usersPanel" class="section" ng-controller="ProjectsController">
        <div id="develHeader" class="row row-centered">
            <span id="headIcon" class="headIcon octicon octicon-organization"></span>
            <span class="headTitle">Projects</span>
        </div>
        <div class="row row-centered search">
            <div class="searchDevForm">
                <input class="searchDevInput form-control input-lg hint" ng-model="projectQuery" placeholder="Project name">
            </div>
            <label for="filter-by" class="searchDevIcon">
                <i class="fa fa-search"></i>
            </label>
            <a href="#" class="fa fa-times searchDevClear"></a>
        </div>
        <div class="row row-centered card-list">
            <div class="col-sm-3 col-centered card" ng-repeat="project in projects | orderBy:'name'" ng-show="([project.name, project.avatar] | filter:projectQuery).length" ng-click="changeToProjectDashboard(project)">
                <span class="helper"></span>
                <img class="card-avatar" ng-src="@{{ project.avatar }}" alt="@{{ project.name }}">
                <span class="card-name">@{{ project.name }}</span>
            </div>
        </div>
    </div>


    <div class="grid-stack">
        <div class="grid-stack-item" data-gs-width="12" data-gs-height="4" data-gs-x="0" data-gs-y="1">
            <div style="color: #004C8B" class="grid-stack-item-content titleRow">
                <span id="peopleTitIco" class="titleIcon octicon octicon-dashboard"></span>
                <span id="peopleTitLabel" class="titleLabel">Analytics</span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="2" data-gs-x="0" data-gs-y="5">
            <div style="color: #ee8433" class="grid-stack-item-content subtitleRow">
                <span id="pa-chart-stitle-ico" class="subtitleIcon fa fa-tasks"></span>
                <span id="pa-chart-stitle-label" class="subtitleLabel">Workload</span>
                <span id="workload-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="6" data-gs-height="10" data-gs-x="0" data-gs-y="7">
            <div id="workload-projects" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="6" data-gs-height="10" data-gs-x="6" data-gs-y="7">
            <div id="workload-time" class="grid-stack-item-content"></div>
        </div>


        <div class="grid-stack-item" data-gs-width="12" data-gs-height="2" data-gs-x="0" data-gs-y="17">
            <div style="color: #cc05b9" class="grid-stack-item-content subtitleRow">
                <span id="pa-chart-stitle-ico" class="subtitleIcon fa fa-exclamation-circle"></span>
                <span id="pa-chart-stitle-label" class="subtitleLabel">Issues</span>
                <span id="issues-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="3" data-gs-height="16" data-gs-x="0" data-gs-y="19">
            <div id="pie-severity" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="3" data-gs-height="16" data-gs-x="3" data-gs-y="19">
            <div id="pie-status" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="6" data-gs-height="8" data-gs-x="6" data-gs-y="19">
            <div id="issues-1" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="6" data-gs-height="8" data-gs-x="6" data-gs-y="27">
            <div id="issues-2" class="grid-stack-item-content"></div>
        </div>

    </div>


    <div class="grid-stack">

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="4" data-gs-x="0" data-gs-y="1">
            <div style="color: #004C8B" class="grid-stack-item-content titleRow">
                <span id="peopleTitIco" class="titleIcon fa fa-users"></span>
                <span id="peopleTitLabel" class="titleLabel">Team Members</span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="3" data-gs-height="46" data-gs-x="0" data-gs-y="5">
            <div id="developers-table" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="7" data-gs-height="6" data-gs-x="4" data-gs-y="5">
            <div id="developer-textinfo" class="grid-stack-item-content">
                <div class="com-widget widget static-info-widget col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row static-info-line">
                                <span class="theicon fa fa-user" style="color: #0376de"></span><span class="thelabel">Name:</span><span class="theVal blurado" id="member-name">------</span>
                            </div>
                            <div class="row static-info-line">
                                <span class="theicon fa fa-pencil-square-o" style="color: #019640"></span><span class="thelabel">Contact:</span><span class="theVal blurado" id="member-contact">------</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row static-info-line">
                                <span class="theicon fa fa-user-secret"></span><span class="thelabel">Role:</span><span class="theVal blurado" id="member-role">-------</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="9" data-gs-height="7" data-gs-x="3" data-gs-y="11">
            <div id="developer-activity" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="9" data-gs-height="2" data-gs-x="3" data-gs-y="19">
            <div style="color: #6e8b00" class="grid-stack-item-content subtitleRow">
                <span id="pa-chart-stitle-ico" class="subtitleIcon fa fa-exclamation-circle"></span>
                <span id="pa-chart-stitle-label" class="subtitleLabel">Issues breakdown</span>
                <span id="issues-breakdown-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="3" data-gs-height="6" data-gs-x="3" data-gs-y="21">
            <div id="developer-counter-1" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="3" data-gs-height="6" data-gs-x="6" data-gs-y="21">
            <div id="developer-counter-2" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="3" data-gs-height="6" data-gs-x="9" data-gs-y="21">
            <div id="developer-counter-3" class="grid-stack-item-content"></div>
        </div>


        <div class="grid-stack-item" data-gs-width="9" data-gs-height="10" data-gs-x="3" data-gs-y="27">
            <div id="issues-multibar" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="16" data-gs-x="3" data-gs-y="37">
            <div id="developer-pie-status" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="4" data-gs-height="16" data-gs-x="8" data-gs-y="37">
            <div id="developer-pie-severity" class="grid-stack-item-content"></div>
        </div>


    </div>
@stop

@section('script')
    /*<script>*/
    function _() {

        var toPercentagePostModifier = function toPercentagePostModifier(resourceData) {

            var values = resourceData['data']['values'];
            for(var x = 0; x < values.length; x++) {
                values[x] = Math.round(values[x] * 100);
            }

            return resourceData;

        };

        var timeCtx = "time-context";
        var productCtx = "product-context";
        var developersCtx = "developers-context";

        //Show header chart and set titles
        setTitle("Product");
        setSubtitle(framework.dashboard.getEnv('name'));
        showHeaderChart();

        framework.data.updateContext(productCtx, {prid: framework.dashboard.getEnv()['prid']});

        framework.data.observe(['productinfo'], function (event) {

            if (event.event === 'data') {
                var productInfo = event.data['productinfo'][Object.keys(event.data['productinfo'])[0]]['data'];

                var creation = document.getElementById('product-created');

                var manager = document.getElementById('product-manager');
                var manager_avatar = document.getElementById('product-manager-avatar');

                var product_director = document.getElementById('product-director');
                var product_director_avatar = document.getElementById('product-director-avatar');

                creation.innerHTML = moment(new Date(productInfo['createdon'])).format('MMMM Do YYYY');
                manager.innerHTML = productInfo['manager'].name;
                $(manager_avatar).css('background-image', 'url(' + productInfo['manager'].avatar + ')');

                product_director.innerHTML = productInfo['director'].name;
                $(product_director_avatar).css('background-image', 'url(' + productInfo['director'].avatar + ')');

                $(creation).removeClass('blurado');
                $(manager).removeClass('blurado');
                $(product_director).removeClass('blurado');

                if (productInfo['avatar'] != null && productInfo['avatar'] !== "" && productInfo['avatar'] !== "http://avatarURL") {
                    $("#avatar").css("background-image", "url(" + productInfo['avatar'] + ")");
                }
            }
        }, [productCtx]);

        // Load all the proejcts of this product
        var product_projects_cntx = "product-projects-cntxt";
        framework.data.observe(["view-product-projects"], function(frameData) {
            if (frameData.event === 'data') {

                var pList = frameData.data["view-product-projects"][0].data.values;

                var projects_number = document.getElementById('product-projects-number');
                projects_number.innerHTML = pList.length;
                $(projects_number).removeClass('blurado');

                var pIdList = [];
                for (var i = 0; i < pList.length; i++) {
                    pIdList.push(pList[i].pjid);
                }

                framework.data.updateContext(product_projects_cntx, {pjid: pIdList});
            }
        }, [productCtx]);


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

        var workloadHelp = '<div><span class="toolTitle"><p>Workload</p></span></div><div><span class="toolRow">The bar chart displays the workload for each of the projects in this product. The lines chart displays he workload during the life of this product.</span></div>';
        addQTip($('#workload-help'), "workloadHelp", workloadHelp);
        var issuesHelp = '<div><span class="toolTitle"><p>Issues</p></span></div><div><span class="toolRow">The two pie charts display the amount of the different issues by status and severity. The bars chart displays the amount of opened and reopened issues during the time.</span></div>';
        addQTip($('#issues-help'), "issuesHelp", issuesHelp);
        var issuesBreakdown = '<div><span class="toolTitle"><p>Issues breakdown</p></span></div><div><span class="toolRow">The two pie charts display the amount of the different issues by status and severity. The bars chart displays this information together by severity.</span></div>';
        addQTip($('#issues-breakdown-help'), "issuesBreakdown", issuesBreakdown);


        var rangeNv_dom = document.getElementById("fixed-chart");
        var rangeNv_metrics = [
            {
                id: 'product-activity',
                max: 101,
                aggr: 'sum'
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

        var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [productCtx], rangeNv_configuration);
        $(rangeNv).on("CONTEXT_UPDATED", function () {
            $(rangeNv).off("CONTEXT_UPDATED");
            loadTimeDependentWidgets();

            // Hide the loading animation
            finishLoading();
        });


        var loadTimeDependentWidgets = function() {

            //ANGULAR INITIALIZATION
            // TODO: add to documentation... This is the manual initialization of angular, with the difference that it is done
            // with .main-content instead of document. https://docs.angularjs.org/guide/bootstrap#manual-initialization
            angular.module('Dashboard', [])
                    .controller('ProjectsController', ['$scope', function ($scope) {
                        $scope.changeToProjectDashboard = function (project) {
                            var env = framework.dashboard.getEnv();
                            env['pjid'] = project['pjid'];
                            env['name'] = project['name'];
                            framework.dashboard.changeTo('project', env);
                        };
                    }]);

            angular.element(".main-content").ready(function () {
                angular.bootstrap(".main-content", ['Dashboard']);
            });


            // -------------------------- WORKLOAD MULTIBAR ------------------------------------
            var changeScalePostModifier = function toPercentagePostModifier(resourceData) {

                // Data will be [0, 200] aprox, but we want 100 to be the y axis origin. Therefore, we change it to a
                // [-100, 100] so that 100 will be 0 in our new scale, and then modify the yAxisTickFormat function of the
                // widget to restore it to the [0,200] by adding 100
                var scale = d3.scale.linear().domain([0, 200]).range([-100, 100]);

                var values = resourceData['data']['values'];
                for(var x = 0; x < values.length; x++) {
                    values[x] = scale(values[x]);
                }
                //debugger;
                return resourceData;

            };
            var products_workload_dom = document.getElementById("workload-projects");
            var products_workload_metrics = [
                {
                    id: 'project-workload',
                    max: 1,
                    post_modifier: changeScalePostModifier
                }
            ];
            var products_workload_conf = {
                stacked: false,
                labelFormat: "¬_D.data.info.pjid.name¬",
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
                    [timeCtx, product_projects_cntx], products_workload_conf);


            // -------------------------- WORKLOAD LINES  ------------------------------------
            var workload_dom = document.getElementById("workload-time");
            var workload_metrics = [
                {
                    id: 'product-workload',
                    max: 30,
                    post_modifier: changeScalePostModifier
                }
            ];
            var workload_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: 'Workload',
                colors: ["#2876B8"],
                area: true,
                yAxisTickFormat : function(d) {  return Math.round(d + 100); }
            };
            new framework.widgets.LinesChart(workload_dom, workload_metrics,
                    [timeCtx, productCtx], workload_configuration);


            // ------------------------------- ISSUES STATUS PIE -------------------------------------
            var status_pie_dom = document.getElementById("pie-status");
            var status_pie_metrics = [
                {
                    id: 'product-opened-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'product-inprogress-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'product-closed-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                }
            ];
            var status_pie_configuration = {
                height: 320,
                showLegend: true,
                showLabels: false,
                labelFormat: "¬_D.data.info.title¬",
                maxDecimals: 0
            };
            new framework.widgets.PieChart(status_pie_dom, status_pie_metrics,
                    [timeCtx, productCtx], status_pie_configuration);


            // ------------------------------- ISSUES STATUS PIE -------------------------------------
            var severity_pie_dom = document.getElementById("pie-severity");
            var severity_pie_metrics = [
                {
                    id: 'product-trivial-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'product-normal-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'product-high-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'product-critical-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'product-blocker-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                }
            ];
            var severity_pie_configuration = {
                height: 320,
                showLegend: true,
                showLabels: false,
                labelFormat: "¬_D.data.info.title¬",
                maxDecimals: 0
            };
            new framework.widgets.PieChart(severity_pie_dom, severity_pie_metrics,
                    [timeCtx, productCtx], severity_pie_configuration);


            // -------------------------- ISSUES STACK BAR ------------------------------------
            var issues_multibar_dom = document.getElementById("issues-1");
            var issues_multibar_metrics = [
                {
                    id: 'product-reopen-issues',
                    max: 10
                },
                {
                    id: 'product-open-issues',
                    max: 10
                }
            ];
            var issues_multibar_conf = {
                stacked: true,
                labelFormat: "¬_D.data.info.title¬",
                showControls: false,
                height: 160,
                showLegend: true,
                showXMaxMin: true
            };
            new framework.widgets.MultiBar(issues_multibar_dom, issues_multibar_metrics,
                    [timeCtx, productCtx], issues_multibar_conf);


            // -------------------------- ISSUES MULTIBAR ------------------------------------

            var prod_issues_multibar_dom = document.getElementById("issues-2");
            var prod_issues_multibar_metrics = [];
            var categories = ['Blocked', 'Critical', 'Grave', 'Normal', 'Trivial'];
            var statuses = ['Other Open', 'Other In Progress', 'Improvement Open', 'Improvement In progress', 'Bug Open', 'Bug In progress' ];
            var colors = ['#ffbb78', '#ff7f0e', '#aec7e8', '#1f77b4', '#ff9896', '#d62728' ];
            var category_1 = {};
            var status_1 = {};
            for(var f = 0; f < 30; f++) {
                var metricName = 'product-issues-breakdown-' + f;
                prod_issues_multibar_metrics.push({
                    id: metricName, //product-member-...
                    max: 1
                });
                category_1[metricName] = categories[f % categories.length];
                status_1[metricName] = statuses[Math.floor(f / categories.length) % statuses.length];
            }

            var prod_issues_multibar_conf = {
                stacked: true,
                color: function (d, i) {
                    return colors[statuses.indexOf(d.key)];
                },
                labelFormat: function(metric, extra) {
                    return status_1[extra.resource];
                },
                showControls: false,
                height: 200,
                showLegend: true,
                x: function(metric, extra) {
                    return category_1[extra.resource];
                }
            };
            new framework.widgets.MultiBar(prod_issues_multibar_dom, prod_issues_multibar_metrics,
                    [timeCtx, productCtx], prod_issues_multibar_conf);




            //PRODUCT LIST
            framework.data.observe(['view-product-projects'], function (event) {
                if (event.event === 'data') {
                    var projects = event.data['view-product-projects'][Object.keys(event.data['view-product-projects'])[0]]['data']['values'];
                    $scope = angular.element(".main-content").scope();

                    $scope.$apply(function () {
                        $scope.projects = projects;
                    });

                }
            }, [productCtx, timeCtx]);


            //  ----------------------------------- PRODUCT DEVELOPERS TABLE ------------------------------------------
            var table_dom = document.getElementById("developers-table");
            var table_metrics = ['view-product-developers'];
            var table_configuration = {
                columns: [
                    {
                        label: "",
                        link: {
                            img: "avatar", //or label
                            href: "developer",
                            env: [
                                {
                                    property: "uid",
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
                        id: developersCtx,
                        filter: [
                            {
                                property: "uid",
                                as: "uid"
                            }
                        ]
                    }
                ],
                keepSelectedByProperty: "uid",
                selectable: true,
                minRowsSelected: 1,
                maxRowsSelected: 1,
                filterControl: true,
                initialSelectedRows: 1,
                showHeader: false,
                alwaysOneSelected: true,
                scrollButtons: true,
                height: 920
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [timeCtx, productCtx], table_configuration);


            framework.data.observe(['userinfo'], function (event) { //TODO: userinfo for specific product

                if (event.event === 'data') {
                    var userinfo = event.data['userinfo'][Object.keys(event.data['userinfo'])[0]]['data'];

                    //Set data
                    var member_contact = document.getElementById('member-contact');
                    var member_role = document.getElementById('member-role');
                    var member_name = document.getElementById('member-name');

                    member_contact.innerHTML = userinfo['email'];
                    member_role.innerHTML = "role"; //TODO: member role in product
                    member_name.innerHTML = userinfo['name'];

                    $(member_contact).removeClass('blurado');
                    $(member_role).removeClass('blurado');
                    $(member_name).removeClass('blurado');

                    if (userinfo['avatar'] !== null && userinfo['avatar'] !== "" && userinfo['avatar'] !== "http://avatarURL") {
                        $("#row-section-1, #row-section-3").find(".avatar-rounded-icon")
                                .css("background-image", "url(" + userinfo['avatar'] + ")")
                                .css("background-size", "contain");
                    }

                }

            }, [developersCtx]);


            // ----------------------------------- PRODUCT MEMBER ACTIVITY WIDGET ----------------------------------------
            var pa_lines_dom = document.getElementById("developer-activity");
            var pa_lines_metrics = [
                {
                    id: 'member-product-activity',
                    max: 60,
                }
            ];
            var pa_lines_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 180,
                labelFormat: 'Activity',
                area: true,
                showXAxis: false,
                colors: ['#004C8B']
            };
            new framework.widgets.LinesChart(pa_lines_dom, pa_lines_metrics,
                    [timeCtx, productCtx, developersCtx], pa_lines_configuration);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_1_dom = document.getElementById("developer-counter-1");
            var counter_1_metrics = [{
                id: 'member-product-active-issues',
                max: 1,
            }];
            var counter_1_conf = {
                label: 'Active issues',
                decimal: 0,
                icon: 'fa fa-refresh',
                iconbackground: '#EE7529',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_1_dom, counter_1_metrics, [timeCtx, productCtx, developersCtx], counter_1_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_2_dom = document.getElementById("developer-counter-2");
            var counter_2_metrics = [{
                id: 'member-product-active-reopened-issues',
                max: 1,
            }];
            var counter_2_conf = {
                label: 'Active reopened issues',
                decimal: 0,
                icon: 'fa fa-retweet',
                iconbackground: '#F75333',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_2_dom, counter_2_metrics, [timeCtx, productCtx, developersCtx], counter_2_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_3_dom = document.getElementById("developer-counter-3");
            var counter_3_metrics = [{
                id: 'member-product-timetofix',
                max: 1,
                aggr: 'avg'
            }];
            var counter_3_conf = {
                label: 'Time to solve',
                decimal: 0,
                icon: 'octicon octicon-issue-closed',
                iconbackground: '#6895BA',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_3_dom, counter_3_metrics, [timeCtx, productCtx, developersCtx], counter_3_conf);


            // ------------------------------- ISSUES STATUS PIE -------------------------------------
            var developer_status_pie_dom = document.getElementById("developer-pie-status");
            var developer_status_pie_metrics = [
                {
                    id: 'member-product-opened-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-product-inprogress-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-product-closed-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                }
            ];
            var developer_status_pie_configuration = {
                height: 320,
                showLegend: true,
                showLabels: false,
                labelFormat: "¬_D.data.info.title¬",
                maxDecimals: 0
            };
            new framework.widgets.PieChart(developer_status_pie_dom, developer_status_pie_metrics,
                    [timeCtx, productCtx, developersCtx], developer_status_pie_configuration);


            // ------------------------------- ISSUES SEVERITY PIE -------------------------------------
            var developer_severity_pie_dom = document.getElementById("developer-pie-severity");
            var developer_severity_pie_metrics = [
                {
                    id: 'member-product-trivial-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-product-normal-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-product-high-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-product-critical-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-product-blocker-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                }
            ];
            var developer_severity_pie_configuration = {
                height: 320,
                showLegend: true,
                showLabels: false,
                labelFormat: "¬_D.data.info.title¬",
                maxDecimals: 0
            };
            new framework.widgets.PieChart(developer_severity_pie_dom, developer_severity_pie_metrics,
                    [timeCtx, productCtx, developersCtx], developer_severity_pie_configuration);


            // -------------------------- ISSUES MULTIBAR ------------------------------------
            var prod_member_issues_multibar_dom = document.getElementById("issues-multibar");
            var prod_member_issues_multibar_metrics = [];
            var categories = ['Blocked', 'Critical', 'Grave', 'Normal', 'Trivial'];
            var statuses = ['Other Open', 'Other In Progress', 'Improvement Open', 'Improvement In progress', 'Bug Open', 'Bug In progress' ];
            var colors = ['#ffbb78', '#ff7f0e', '#aec7e8', '#1f77b4', '#ff9896', '#d62728' ];
            var category_2 = {};
            var status_2 = {};
            for(var f = 0; f < 30; f++) {
                var metricName = 'product-member-issues-breakdown-' + f;
                prod_member_issues_multibar_metrics.push({
                    id: metricName, //product-member-...
                    max: 1
                });
                category_2[metricName] = categories[f % categories.length];
                status_2[metricName] = statuses[Math.floor(f / categories.length) % statuses.length];
            }

            var prod_member_issues_multibar_conf = {
                stacked: true,
                color: function (d, i) {
                    return colors[statuses.indexOf(d.key)];
                },
                labelFormat: function(metric, extra) {
                    return status_2[extra.resource];
                },
                showControls: false,
                height: 200,
                showLegend: true,
                x: function(metric, extra) {
                    return category_2[extra.resource];
                }
            };
            new framework.widgets.MultiBar(prod_member_issues_multibar_dom, prod_member_issues_multibar_metrics,
                    [timeCtx, productCtx, developersCtx], prod_member_issues_multibar_conf);


        };

    }
@stop
