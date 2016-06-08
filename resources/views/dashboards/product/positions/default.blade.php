@extends('layouts.template')

@section('require')
    [
    "//ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
    "css!assets/css/dashboards/organization-dashboard",
    "sdh-framework/widgets/RangeNv/rangeNv",
    "sdh-framework/widgets/Table/table",
    "css!assets/css/info-box",
    "css!assets/css/dashboards/product-dashboard"
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
                            <span class="theicon fa fa-user-secret"></span><span class="thelabel">Manager:</span><span class="theVal blurado" id="product-manager">-------</span>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row static-info-line">
                            <span class="theicon fa fa-cubes" style="color: #C0485E"></span><span class="thelabel">Number of projects:</span><span class="theVal blurado" id="product-projects-number">--------</span>
                        </div>
                        <div class="row static-info-line">
                            <span class="theicon octicon octicon-git-branch" style="color: #8A1978"></span><span class="thelabel">Last commit:</span><span class="theVal blurado" id="product-last-commit">--------</span>
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
                <span id="peopleTitIco" class="titleIcon fa fa-users"></span>
                <span id="peopleTitLabel" class="titleLabel">Team Members</span>
            </div>
        </div>

        <div class="grid-stack-item" data-gs-width="2" data-gs-height="46" data-gs-x="0" data-gs-y="5">
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

        <div class="grid-stack-item" data-gs-width="10" data-gs-height="7" data-gs-x="2" data-gs-y="11">
            <div id="developer-activity" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item" data-gs-width="10" data-gs-height="2" data-gs-x="2" data-gs-y="19">
            <div style="color: #6e8b00" class="grid-stack-item-content subtitleRow">
                <span id="pa-chart-stitle-ico" class="subtitleIcon fa fa-exclamation-circle"></span>
                <span id="pa-chart-stitle-label" class="subtitleLabel">Issues breakdown</span>
                <span id="pa-chart-stitle-help" class="subtitleHelp fa fa-info-circle"></span>
            </div>
        </div>


        <div class="grid-stack-item" data-gs-width="3" data-gs-height="6" data-gs-x="4" data-gs-y="21">
            <div id="developer-counter-1" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="3" data-gs-height="6" data-gs-x="7" data-gs-y="21">
            <div id="developer-counter-2" class="grid-stack-item-content"></div>
        </div>

        <div class="grid-stack-item text-center colored-row-item" data-gs-width="1" data-gs-height="6" data-gs-x="2" data-gs-y="27">
            <div class="centered-element-container">
                <div class="centered-element">
                    <div id="row-section-1" class="grid-stack-item-content">
                        <span class="row-section-icon octicon octicon-issue-opened"></span>
                        <span class="row-title text-center">Assigned issues</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-stack-item colored-row-item" data-gs-width="3" data-gs-height="6" data-gs-x="3" data-gs-y="27">
            <div id="developer-counter-3" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item colored-row-item" data-gs-width="3" data-gs-height="6" data-gs-x="6" data-gs-y="27">
            <div id="developer-counter-4" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item colored-row-item" data-gs-width="3" data-gs-height="6" data-gs-x="9" data-gs-y="27">
            <div id="developer-counter-5" class="grid-stack-item-content"></div>
        </div>


        <div class="grid-stack-item text-center" data-gs-width="1" data-gs-height="6" data-gs-x="2" data-gs-y="33">
            <div class="centered-element-container">
                <div class="centered-element">
                    <div id="row-section-2" class="grid-stack-item-content">
                        <span class="row-section-icon octicon octicon-issue-reopened"></span>
                        <span class="row-title text-center">Reassigned issues</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-stack-item" data-gs-width="3" data-gs-height="6" data-gs-x="3" data-gs-y="33">
            <div id="developer-counter-6" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="3" data-gs-height="6" data-gs-x="6" data-gs-y="33">
            <div id="developer-counter-7" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item" data-gs-width="3" data-gs-height="6" data-gs-x="9" data-gs-y="33">
            <div id="developer-counter-8" class="grid-stack-item-content"></div>
        </div>


        <div class="grid-stack-item text-center colored-row-item" data-gs-width="1" data-gs-height="6" data-gs-x="2" data-gs-y="39">
            <div class="centered-element-container">
                <div class="centered-element">
                    <div id="row-section-3" class="grid-stack-item-content">
                        <span class="row-section-icon fa fa-user"></span>
                        <span class="row-title text-center">Member percentage</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-stack-item text-center colored-row-item" data-gs-width="3" data-gs-height="6" data-gs-x="3" data-gs-y="39">
            <div id="liquid-chart-1" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item text-center colored-row-item" data-gs-width="3" data-gs-height="6" data-gs-x="6" data-gs-y="39">
            <div id="liquid-chart-2" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item text-center colored-row-item" data-gs-width="3" data-gs-height="6" data-gs-x="9" data-gs-y="39">
            <div id="liquid-chart-3" class="grid-stack-item-content"></div>
        </div>


        <div class="grid-stack-item text-center" data-gs-width="1" data-gs-height="6" data-gs-x="2" data-gs-y="45">
            <div class="centered-element-container">
                <div class="centered-element">
                    <div id="row-section-4" class="grid-stack-item-content">
                        <span class="row-section-icon fa fa-globe"></span>
                        <span class="row-title text-center">Total percentage</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-stack-item text-center" data-gs-width="3" data-gs-height="6" data-gs-x="3" data-gs-y="45">
            <div id="liquid-chart-4" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item text-center" data-gs-width="3" data-gs-height="6" data-gs-x="6" data-gs-y="45">
            <div id="liquid-chart-5" class="grid-stack-item-content"></div>
        </div>
        <div class="grid-stack-item text-center" data-gs-width="3" data-gs-height="6" data-gs-x="9" data-gs-y="45">
            <div id="liquid-chart-6" class="grid-stack-item-content"></div>
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
                var projects_number = document.getElementById('product-projects-number');
                var last_commit = document.getElementById('product-last-commit');

                $(creation).removeClass('blurado');
                $(manager).removeClass('blurado');
                $(projects_number).removeClass('blurado');
                $(last_commit).removeClass('blurado');

                if (productInfo['avatar'] != null && productInfo['avatar'] !== "" && productInfo['avatar'] !== "http://avatarURL") {
                    $("#avatar").css("background-image", "url(" + productInfo['avatar'] + ")");
                }

                //TODO: fill the data in the product info

            }
        }, [productCtx]);


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
                    id: 'repository-member-activity', //TODO: product-member-activity
                    max: 60,
                    rid: 62 //TODO: remove
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
                id: 'repository-member-commits', //TODO: ​member-product-active-issues
                max: 1,
                rid: 62 //TODO: remove
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
                id: 'repository-member-commits', //TODO: member-product-active-reopened-issues
                max: 1,
                rid: 62 //TODO: remove
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
                id: 'repository-member-commits', //TODO: member-product-assigned-open-issues
                max: 1,
                rid: 62 //TODO: remove
            }];
            var counter_3_conf = {
                label: 'Assigned open issues',
                decimal: 0,
                icon: 'octicon octicon-issue-opened',
                iconbackground: '#6895BA',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_3_dom, counter_3_metrics, [timeCtx, productCtx, developersCtx], counter_3_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_4_dom = document.getElementById("developer-counter-4");
            var counter_4_metrics = [{
                id: 'repository-member-commits', //TODO: member-product-assigned-inProgress-issues
                max: 1,
                rid: 62 //TODO: remove
            }];
            var counter_4_conf = {
                label: 'Assigned in-progress issues',
                decimal: 0,
                icon: 'octicon octicon-issue-opened',
                iconbackground: '#019640',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_4_dom, counter_4_metrics, [timeCtx, productCtx, developersCtx], counter_4_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_5_dom = document.getElementById("developer-counter-5");
            var counter_5_metrics = [{
                id: 'repository-member-commits', //TODO: member-product-assigned-closed-issues
                max: 1,
                rid: 62 //TODO: remove
            }];
            var counter_5_conf = {
                label: 'Assigned closed issues',
                decimal: 0,
                icon: 'octicon octicon-issue-opened',
                iconbackground: '#8A1978',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_5_dom, counter_5_metrics, [timeCtx, productCtx, developersCtx], counter_5_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_6_dom = document.getElementById("developer-counter-6");
            var counter_6_metrics = [{
                id: 'repository-member-commits', //TODO: member-product-reassigned-open-issues
                max: 1,
                rid: 62 //TODO: remove
            }];
            var counter_6_conf = {
                label: 'Reassigned open issues',
                decimal: 0,
                icon: 'octicon octicon-issue-reopened',
                iconbackground: '#88B5DA',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_6_dom, counter_6_metrics, [timeCtx, productCtx, developersCtx], counter_6_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_7_dom = document.getElementById("developer-counter-7");
            var counter_7_metrics = [{
                id: 'repository-member-commits', //TODO: member-product-reassigned-inProgress-issues
                max: 1,
                rid: 62 //TODO: remove
            }];
            var counter_7_conf = {
                label: 'Reassigned in-progress issues',
                decimal: 0,
                icon: 'octicon octicon-issue-reopened',
                iconbackground: '#21B660',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_7_dom, counter_7_metrics, [timeCtx, productCtx, developersCtx], counter_7_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_8_dom = document.getElementById("developer-counter-8");
            var counter_8_metrics = [{
                id: 'repository-member-commits', //TODO: member-product-reassigned-closed-issues
                max: 1,
                rid: 62 //TODO: remove
            }];
            var counter_8_conf = {
                label: 'Reassigned closed issues',
                decimal: 0,
                icon: 'octicon octicon-issue-reopened',
                iconbackground: '#AA3998',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_8_dom, counter_8_metrics, [timeCtx, productCtx, developersCtx], counter_8_conf);


            //  ----------------------------------- LIQUID GAUGE 1 -----------------------------------------

            var liquid_1_dom = document.getElementById("liquid-chart-1");
            var liquid_1_metrics = [
                {
                    id: 'product-health', //TODO
                    max: 1,
                    aggr: "sum",
                    post_modifier: toPercentagePostModifier
                }
            ];
            var liquid_1_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor:'#DBF1B4',
                radius: 45
            };
            new framework.widgets.LiquidGauge(liquid_1_dom, liquid_1_metrics,
                    [timeCtx, productCtx, developersCtx], liquid_1_configuration);

            //  ----------------------------------- LIQUID GAUGE 2 -----------------------------------------

            var liquid_2_dom = document.getElementById("liquid-chart-2");
            var liquid_2_metrics = [
                {
                    id: 'product-health', //TODO
                    max: 1,
                    aggr: "sum",
                    post_modifier: toPercentagePostModifier
                }
            ];
            var liquid_2_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor:'#DBF1B4',
                radius: 45
            };
            new framework.widgets.LiquidGauge(liquid_2_dom, liquid_2_metrics,
                    [timeCtx, productCtx, developersCtx], liquid_2_configuration);

            //  ----------------------------------- LIQUID GAUGE 3 -----------------------------------------

            var liquid_3_dom = document.getElementById("liquid-chart-3");
            var liquid_3_metrics = [
                {
                    id: 'product-health', //TODO
                    max: 1,
                    aggr: "sum",
                    post_modifier: toPercentagePostModifier
                }
            ];
            var liquid_3_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor:'#DBF1B4',
                radius: 45
            };
            new framework.widgets.LiquidGauge(liquid_3_dom, liquid_3_metrics,
                    [timeCtx, productCtx, developersCtx], liquid_3_configuration);

            //  ----------------------------------- LIQUID GAUGE 4 -----------------------------------------

            var liquid_4_dom = document.getElementById("liquid-chart-4");
            var liquid_4_metrics = [
                {
                    id: 'product-health', //TODO
                    max: 1,
                    aggr: "sum",
                    post_modifier: toPercentagePostModifier
                }
            ];
            var liquid_4_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor:'#DBF1B4',
                radius: 45
            };
            new framework.widgets.LiquidGauge(liquid_4_dom, liquid_4_metrics,
                    [timeCtx, productCtx, developersCtx], liquid_4_configuration);

            //  ----------------------------------- LIQUID GAUGE 5 -----------------------------------------

            var liquid_5_dom = document.getElementById("liquid-chart-5");
            var liquid_5_metrics = [
                {
                    id: 'product-health', //TODO
                    max: 1,
                    aggr: "sum",
                    post_modifier: toPercentagePostModifier
                }
            ];
            var liquid_5_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor:'#DBF1B4',
                radius: 45
            };
            new framework.widgets.LiquidGauge(liquid_5_dom, liquid_5_metrics,
                    [timeCtx, productCtx, developersCtx], liquid_5_configuration);

            //  ----------------------------------- LIQUID GAUGE 6 -----------------------------------------

            var liquid_6_dom = document.getElementById("liquid-chart-6");
            var liquid_6_metrics = [
                {
                    id: 'product-health', //TODO
                    max: 1,
                    aggr: "sum",
                    post_modifier: toPercentagePostModifier
                }
            ];
            var liquid_6_configuration = {
                height: 110,
                minValue: 0,
                maxValue: 100,
                waveColor: ['#E65538', '#8ACA17'],
                textColor: ['#E65538', '#DBF1B4'],
                circleColor: ['#8C1700', '#4BAD06'],
                waveTextColor:'#DBF1B4',
                radius: 45
            };
            new framework.widgets.LiquidGauge(liquid_6_dom, liquid_6_metrics,
                    [timeCtx, productCtx, developersCtx], liquid_6_configuration);





        };

    }
@stop
