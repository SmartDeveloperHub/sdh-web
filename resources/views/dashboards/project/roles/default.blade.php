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
                            <span id="createdIco" class="theicon fa fa-pencil-square-o" style="color: #019640"></span><span class="thelabel">Created:</span><span class="theVal blurado" id="project-created">------</span>
                        </div>
                        <div class="row static-info-line">
                            <span class="theicon fa fa-user"></span><span class="thelabel">Manager:</span><span class="theVal blurado" id="product-manager">-------</span>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row static-info-line">
                            <span id="lastIco" class="theicon fa fa-cubes" style="color: #C0485E"></span><span class="thelabel">Number of repositories:</span><span class="theVal blurado" id="project-repositories-number">--------</span>
                        </div>
                        <div class="row static-info-line">
                            <span class="theicon fa fa-user-secret" style="color: #8A1978"></span><span class="thelabel">Director:</span><span class="theVal blurado" id="project-director">--------</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="usersPanel" class="section" ng-controller="ReposController">
        <div id="develHeader" class="row row-centered">
            <span id="headIcon" class="headIcon octicon octicon-organization"></span>
            <span class="headTitle">Repositories</span>
        </div>
        <div class="row row-centered search">
            <div class="searchDevForm">
                <input class="searchDevInput form-control input-lg hint" ng-model="repoQuery" placeholder="Repository name">
            </div>
            <label for="filter-by" class="searchDevIcon">
                <i class="fa fa-search"></i>
            </label>
            <a href="#" class="fa fa-times searchDevClear"></a>
        </div>
        <div class="row row-centered card-list">
            <div class="col-sm-3 col-centered card" ng-repeat="repo in repos | orderBy:'name'" ng-show="([repo.name, repo.avatar] | filter:repoQuery).length" ng-click="changeToRepoDashboard(repo)">
                <span class="helper"></span>
                <img class="card-avatar" ng-src="@{{ repo.avatar }}" alt="@{{ repo.name }}">
                <span class="card-name">@{{ repo.name }}</span>
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

        <div class="grid-stack-item" data-gs-width="12" data-gs-height="10" data-gs-x="0" data-gs-y="7">
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
            <div id="pie-status" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="3" data-gs-height="16" data-gs-x="3" data-gs-y="19">
            <div id="pie-severity" class="grid-stack-item-content"></div>
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

        var timeCtx = "time-context";
        var projectCtx = "project-context";
        var developersCtx = "developers-context";

        //Show header chart and set titles
        setTitle("Project");
        setSubtitle(framework.dashboard.getEnv('name'));
        showHeaderChart();
        framework.data.updateContext(projectCtx, {pjid: framework.dashboard.getEnv()['pjid']});

        framework.data.observe(['projectinfo'], function (event) {

            if (event.event === 'data') {
                var projectInfo = event.data['projectinfo'][Object.keys(event.data['projectinfo'])[0]]['data'];

                var creation = document.getElementById('project-created');
                var manager = document.getElementById('project-manager');

                var project_director = document.getElementById('project-director');

                creation.innerHTML = moment(new Date(projectInfo['createdon'])).format('MMMM Do YYYY');
                //manager.innerHTML = projectInfo['manager']; //TODO: uncomment
                //project_director.innerHTML = projectInfo['director']; //TODO: uncomment

                $(creation).removeClass('blurado');
                $(manager).removeClass('blurado');
                $(project_director).removeClass('blurado');

                if (projectInfo['avatar'] != null && projectInfo['avatar'] !== "" && projectInfo['avatar'] !== "http://avatarURL") {
                    $("#avatar").css("background-image", "url(" + projectInfo['avatar'] + ")");
                }

                //TODO: fill the data in the project info

            }
        }, [projectCtx]);


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
                id: 'project-activity',
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

        var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [projectCtx], rangeNv_configuration);
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
                    .controller('ReposController', ['$scope', function ($scope) {
                        $scope.changeToRepoDashboard = function (repo) {
                            var env = framework.dashboard.getEnv();
                            env['rid'] = repo['rid'];
                            env['name'] = repo['name'];
                            framework.dashboard.changeTo('repository', env);
                        };
                    }]);

            angular.element(".main-content").ready(function () {
                angular.bootstrap(".main-content", ['Dashboard']);
            });
            //PROJECT LIST
            framework.data.observe(['view-project-repositories'], function (event) {

                if (event.event === 'data') {

                    var repos = event.data['view-project-repositories'][Object.keys(event.data['view-project-repositories'])[0]]['data']['values'];

                    var repositories_number = document.getElementById('project-repositories-number');
                    repositories_number.innerHTML = repos.length;
                    $(repositories_number).removeClass('blurado');

                    $scope = angular.element(".main-content").scope();

                    $scope.$apply(function () {
                        $scope.repos = repos;
                    });

                }
            },[projectCtx, timeCtx]);


            // -------------------------- WORKLOAD LINES  ------------------------------------
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
            var workload_dom = document.getElementById("workload-time");
            var workload_metrics = [
                {
                    id: 'project-workload',
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
                    [timeCtx, projectCtx], workload_configuration);


            // ------------------------------- ISSUES STATUS PIE -------------------------------------
            var status_pie_dom = document.getElementById("pie-status");
            var status_pie_metrics = [
                {
                    id: 'project-opened-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'project-inprogress-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'project-closed-issues',
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
                    [timeCtx, projectCtx], status_pie_configuration);


            // ------------------------------- ISSUES STATUS PIE -------------------------------------
            var severity_pie_dom = document.getElementById("pie-severity");
            var severity_pie_metrics = [
                {
                    id: 'project-trivial-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'project-normal-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'project-high-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'project-critical-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'project-blocker-issues',
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
                    [timeCtx, projectCtx], severity_pie_configuration);


            // -------------------------- ISSUES STACK BAR ------------------------------------
            var issues_multibar_dom = document.getElementById("issues-1");
            var issues_multibar_metrics = [
               {
                    id: 'project-reopen-issues',
                    max: 10
                },
                {
                    id: 'project-open-issues',
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
                    [timeCtx, projectCtx], issues_multibar_conf);



             // -------------------------- ISSUES MULTIBAR ------------------------------------

            var proj_issues_multibar_dom = document.getElementById("issues-2");
            var proj_issues_multibar_metrics = [];
            var categories = ['Blocked', 'Critical', 'Grave', 'Normal', 'Trivial'];
            var statuses = ['Other Open', 'Other In Progress', 'Improvement Open', 'Improvement In progress', 'Bug Open', 'Bug In progress' ];
            var colors = ['#ffbb78', '#ff7f0e', '#aec7e8', '#1f77b4', '#ff9896', '#d62728' ];
            var category_1 = {};
            var status_1 = {};
            for(var f = 0; f < 30; f++) {
                var metricName = 'project-issues-breakdown-' + f;
                proj_issues_multibar_metrics.push({
                    id: metricName, //product-member-...
                    max: 1
                });
                category_1[metricName] = categories[f % categories.length];
                status_1[metricName] = statuses[Math.floor(f / categories.length) % statuses.length];
            }

            var proj_issues_multibar_conf = {
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
            new framework.widgets.MultiBar(proj_issues_multibar_dom, proj_issues_multibar_metrics,
                    [timeCtx, projectCtx], proj_issues_multibar_conf);





            //PRODUCT LIST
            framework.data.observe(['view-product-projects'], function (event) {
                if (event.event === 'data') {
                    var projects = event.data['view-product-projects'][Object.keys(event.data['view-product-projects'])[0]]['data']['values'];
                    $scope = angular.element(".main-content").scope();

                    $scope.$apply(function () {
                        $scope.projects = projects;
                    });

                }
            }, [projectCtx, timeCtx]);


            //  ----------------------------------- PROJECT DEVELOPERS TABLE ------------------------------------------
            var table_dom = document.getElementById("developers-table");
            var table_metrics = ['view-project-developers'];
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
            var table = new framework.widgets.Table(table_dom, table_metrics, [timeCtx, projectCtx], table_configuration);


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
                    id: 'member-project-activity',
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
                    [timeCtx, projectCtx, developersCtx], pa_lines_configuration);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_1_dom = document.getElementById("developer-counter-1");
            var counter_1_metrics = [{
                id: 'member-project-active-issues',
                max: 1,
            }];
            var counter_1_conf = {
                label: 'Active issues',
                decimal: 0,
                icon: 'fa fa-refresh',
                iconbackground: '#EE7529',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_1_dom, counter_1_metrics, [timeCtx, projectCtx, developersCtx], counter_1_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_2_dom = document.getElementById("developer-counter-2");
            var counter_2_metrics = [{
                id: 'member-project-active-reopened-issues',
                max: 1,
            }];
            var counter_2_conf = {
                label: 'Active reopened issues',
                decimal: 0,
                icon: 'fa fa-retweet',
                iconbackground: '#F75333',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_2_dom, counter_2_metrics, [timeCtx, projectCtx, developersCtx], counter_2_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_3_dom = document.getElementById("developer-counter-3");
            var counter_3_metrics = [{
                id: 'member-project-timetofix',
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
            new framework.widgets.CounterBox(counter_3_dom, counter_3_metrics, [timeCtx, projectCtx, developersCtx], counter_3_conf);


            // ------------------------------- ISSUES STATUS PIE -------------------------------------
            var developer_status_pie_dom = document.getElementById("developer-pie-status");
            var developer_status_pie_metrics = [
                {
                    id: 'member-project-opened-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-project-inprogress-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-project-closed-issues',
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
                    [timeCtx, projectCtx, developersCtx], developer_status_pie_configuration);


            // ------------------------------- ISSUES SEVERITY PIE -------------------------------------
            var developer_severity_pie_dom = document.getElementById("developer-pie-severity");
            var developer_severity_pie_metrics = [
                {
                    id: 'member-project-trivial-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-project-normal-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-project-high-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-project-critical-issues',
                    max: 1,
                    aggr: "sum",
                    post_aggr: 'sum'
                },
                {
                    id: 'member-project-blocker-issues',
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
                    [timeCtx, projectCtx, developersCtx], developer_severity_pie_configuration);


            // -------------------------- ISSUES MULTIBAR ------------------------------------
            var proj_member_issues_multibar_dom = document.getElementById("issues-multibar");
            var proj_member_issues_multibar_metrics = [];
            var categories = ['Blocked', 'Critical', 'Grave', 'Normal', 'Trivial'];
            var statuses = ['Other Open', 'Other In Progress', 'Improvement Open', 'Improvement In progress', 'Bug Open', 'Bug In progress' ];
            var colors = ['#ffbb78', '#ff7f0e', '#aec7e8', '#1f77b4', '#ff9896', '#d62728' ];
            var category_2 = {};
            var status_2 = {};
            for(var f = 0; f < 30; f++) {
                var metricName = 'project-member-issues-breakdown-' + f;
                proj_member_issues_multibar_metrics.push({
                    id: metricName, //product-member-...
                    max: 1
                });
                category_2[metricName] = categories[f % categories.length];
                status_2[metricName] = statuses[Math.floor(f / categories.length) % statuses.length];
            }

            var proj_member_issues_multibar_conf = {
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
            new framework.widgets.MultiBar(proj_member_issues_multibar_dom, proj_member_issues_multibar_metrics,
                    [timeCtx, projectCtx, developersCtx], proj_member_issues_multibar_conf);

        };


    }
@stop
