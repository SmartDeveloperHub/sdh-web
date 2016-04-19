{{--
    Organization dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "//cdn.rawgit.com/matthieua/WOW/master/dist/wow.js",
    "//ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
    "//cdnjs.cloudflare.com/ajax/libs/angular-moment/0.9.0/angular-moment.min.js",
    "css!assets/css/animate.css",
    "css!assets/css/dashboards/organization-dashboard",
    "vendor/sdh-framework/widgets/CounterBox/counterbox",
    "vendor/sdh-framework/widgets/LinesChart/linesChart"
    ]
@stop

@section('html')
    <div class="dashContainer" ng-app>
        <div class="section initial-section section-shadow gradient-2 white wow pulse animated" data-wow-duration="3s" data-wow-iteration="infinite" data-wow-delay="300ms">
            <div class="container">
                <div class="row">
                    <div class="center-block p4 initial-section-content">
                        <h2 class="h1 page-title">Smart Developer Hub</h2>
                        <p class="h4 page-title-content up">
                            Smart Developer Hub provides insights about the performance of
                            software development teams by generating quantitative and qualitative
                            metrics based on metadata gathered from ALM tools that are used in the
                            organization's development process.
                            </p>
                        <p class="h4 page-title-content down">
                            The Smart Developer Hub platform has been designed with extensibility 
                            and interoperability in mind in order to facilitate the integration of heterogeneous 
                            ALM tools and the provision of tool-independent metrics.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div id="arqPanel" class="section feature-section gradient-1">
            <div class="container">
                <div class="row">
                    <div class="row">
                        <div class="wow pulse animated">
                            <h2 class="h1 arqTitle">Software Developer Team Performance Analysis using Linked Data</h2>
                            <p class="h4 parr first">
                                To facilitate the consumption of the data provided by heterogeneous ALM tools, the 
                                Smart Developer Hub platform (a.k.a. SDH platform) standardizes the data access 
                                mechanism as well as the data model (a.k.a. SDH vocabulary) and format used for 
                                the exchange of the data within the platform, using the web as a platform and leveraging 
                                standards such as the <a href="http://www.w3.org/TR/ldp/">LDP</a>, <a href="http://www.w3.org/TR/2014/REC-rdf11-mt-20140225/">RDF</a>, and <a href="http://www.w3.org/TR/owl-features/">OWL</a> W3C recommendations and the 
                                <a href="http://www.w3.org/Submission/shapes/">OSLC</a> initiative from OASIS.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-sm-12 wow fadeInLeft animated" data-wow-duration="1.1s" data-wow-delay="0.2s">
                            <div class="screenshot">
                                <img class="image" height="100%" width="80%" src="/assets/images/sdh-architecture_v1.png">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 col-sm-12 wow fadeInUp animated" data-wow-duration="1.1s">
                            <p class="h4 parr">
                                In particular, the SDH platform promotes the integration of ALM tools using LDP-aware 
                                adapters that enable exposing the tools' data as Linked Data defined using a common 
                                vocabulary (i.e., the SDH vocabulary) that is exchanged using a common format (i.e., 
                                RDF serialiations).
                            </p>
                            <p class="h4 parr">
                                To facilitate the consumption of this distributed information graph the SDH platform 
                                provides the Agora, which exploits the SDH vocabulary for creating query plans that 
                                define how to traverse this Linked Data graph in order to retrieve the required data.
                            </p>
                            <p class="h4 parr">
                                The Smart Developer Hub metric services leverage the query plans provided by the 
                                Agora to retrieve and process the information required to calculate the different 
                                measurements. These measurements are then stored in the form of service-specific 
                                internal data marts.
                            </p>
                            <p class="h4 parr">
                                Finally, the SDH platform offers a set of customizable dashboards to visualize the 
                                metrics via different widgets. These dashboards,  which are adapted to the profile 
                                of user within the organization, allow selecting the time range of interest, adjusting 
                                automatically the metrics values shown according to the selected time range.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="metricsPanel" class="section">
            <div id="metricsHeader" class="row row-centered">
                <span id="headIcon" class="headIcon fa-bar-chart"></span>
                <span class="headTitle">Organization Metrics</span>
            </div>
            <div class="row row-centered">
                <div id="orgcommits" class="col-sm-4 col-centered"></div>
                <div id="orgdevelopers" class="col-sm-4 col-centered"></div>
                <div id="orgrepositories" class="col-sm-4 col-centered"></div>
            </div>
            <div class="row row-centered">
                <div id="orgbuilds" class="col-sm-4 col-centered"></div>
                <div id="orgcurrentsuccessbuilds" class="col-sm-4 col-centered"></div>
                <div id="orgcurrentbrokenbuilds" class="col-sm-4 col-centered"></div>
            </div>
            <div class="row row-centered">
                <div id="organizationexec" class="col-sm-4 col-centered"></div>
                <div id="organizationsuccessexec" class="col-sm-4 col-centered"></div>
                <div id="organizationbrokenexec" class="col-sm-4 col-centered"></div>
            </div>
            <div class="row row-centered">
                <div id="orgtimetofix" class="col-sm-4 col-centered"></div>
                <div id="orgbuildtime" class="col-sm-4 col-centered"></div>
                <div id="orgbrokentime" class="col-sm-4 col-centered"></div>
            </div>
        </div>
    </div>
    <div class="row devBox">
        <div class="boxtitle">
            <span id="devIco" class="orgSubtitleIco octicon octicon-squirrel"></span>
            <span id="devTitle" class="orgSubtitle">Developers History</span>
        </div>
        <div id="dev-lines" class="col-sm-12 col-centered"></div>
    </div>
    <div class="row executionsBox">
        <div class="boxtitle">
            <span id="execIco" class="orgSubtitleIco fa fa-history"></span>
            <span id="execTitle" class="orgSubtitle">Continuous Integration History</span>
        </div>
        <div id="execs-lines" class="col-sm-12 col-centered"></div>
    </div>
    <div id="usersPanel" class="section" ng-controller="UsersController">
        <div id="develHeader" class="row row-centered">
            <span id="headIcon" class="headIcon octicon octicon-organization"></span>
            <span class="headTitle">Developers</span>
        </div>
        <div class="row row-centered search">
             <div class="searchDevForm">
                 <input class="searchDevInput form-control input-lg hint" ng-model="userQuery" placeholder="Developer name">
             </div>
             <label for="filter-by" class="searchDevIcon">
                 <i class="fa fa-search"></i>
             </label>
             <a href="#" class="fa fa-times searchDevClear"></a>
        </div>
        <div class="row row-centered card-list">
            <div class="col-sm-3 col-centered card" ng-repeat="user in users | orderBy:'name'" ng-show="([user.name, user.avatar] | filter:userQuery).length" ng-click="changeToUserDashboard(user)">
                <span class="helper"></span>
                <img class="card-avatar" ng-src="@{{ user.avatar }}" alt="@{{ user.name }}">
                <span class="card-name">@{{ user.name }}</span>
            </div>
        </div>
    </div>
    <div id="reposPanel" class="section" ng-controller="ReposController">
        <div id="reposHeader" class="row row-centered">
            <span id="headIcon" class="headIcon octicon octicon-repo"></span>
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
            <div class="col-sm-3 col-centered card card_backg" ng-repeat="repo in repos | orderBy:'name'" ng-show="([repo.name, repo.avatar] | filter:repoQuery).length" ng-click="changeToRepoDashboard(repo)">
                <span class="helper"></span>
                <img class="card-avatar-rep" ng-src="@{{ repo.avatar }}" alt="@{{ repo.name }}">
                <span class="card-name">@{{ repo.name }}</span>
            </div>
        </div>
    </div>
@stop

@section('script')
/* <script> */
    function _() {
        //Hide header chart
        hideHeaderChart();

        // TOTAL COMMITS
        var orgcommits_dom = document.getElementById("orgcommits");
        var orgcommits_metrics = [{
            id: 'orgcommits',
            max: 1,
            aggr: 'sum'
        }];
        var orgcommits_conf = {
            label: 'Total commits',
            decimal: 0,
            icon: 'octicon octicon-git-commit',
            iconbackground: '#004B8B',
            background: 'transparent',
            labelcolor: '#000'
        };
        var orgcommits = new framework.widgets.CounterBox(orgcommits_dom, orgcommits_metrics, null, orgcommits_conf);

        // TOTAL DEVELOPERS
        var orgdevelopers_dom = document.getElementById("orgdevelopers");
        var orgdevelopers_metrics = [{
            id: 'orgdevelopers',
            max: 1,
            aggr: 'sum'
        }];
        var orgdevelopers_conf = {
            label: 'Total developers',
            decimal: 0,
            icon: 'octicon octicon-organization',
            iconbackground: '#F7853C',
            background: 'transparent',
            labelcolor: '#000'
        };
        var orgdevelopers = new framework.widgets.CounterBox(orgdevelopers_dom, orgdevelopers_metrics, null, orgdevelopers_conf);

        // TOTAL REPOSITORIES
        var orgrepositories_dom = document.getElementById("orgrepositories");
        var orgrepositories_metrics = [{
            id: 'orgrepositories',
            max: 1,
            aggr: 'sum'
        }];
        var orgrepositories_conf = {
            label: 'Total repositories',
            decimal: 0,
            icon: 'octicon octicon-repo',
            iconbackground: '#9FCE23',
            background: 'transparent',
            labelcolor: '#000'
        };
        var orgrepositories = new framework.widgets.CounterBox(orgrepositories_dom, orgrepositories_metrics, null, orgrepositories_conf);

        // TOTAL BUILDS
        var orgbuilds_dom = document.getElementById("orgbuilds");
        var orgbuilds_metrics = [{
            id: 'orgbuilds',
            max: 1,
            aggr: 'sum'
        }];
        var orgbuilds_conf = {
            label: 'Total builds',
            decimal: 0,
            icon: 'fa fa-cogs',
            iconbackground: '#2a2a2a',
            background: 'transparent',
            labelcolor: '#000'
        };
        var orgbuilds = new framework.widgets.CounterBox(orgbuilds_dom, orgbuilds_metrics, null, orgbuilds_conf);

        // TOTAL CURRENT SUCCESS BUILDS
        var currentbuilds_dom = document.getElementById("orgcurrentsuccessbuilds");
        var currentbuilds_metrics = [{
            id: 'orgpassedbuilds',
            max: 1,
            aggr: 'sum'
        }];
        var currentbuilds_conf = {
            label: 'Current Successful Builds',
            decimal: 0,
            icon: 'fa-sort-amount-asc',
            iconbackground: '#069744',
            background: 'transparent',
            labelcolor: '#000'
        };
        var currentbuilds = new framework.widgets.CounterBox(currentbuilds_dom, currentbuilds_metrics, null, currentbuilds_conf);

        // TOTAL CURRENT BROKEN BUILDS
        var currentFbuilds_dom = document.getElementById("orgcurrentbrokenbuilds");
        var currentFbuilds_metrics = [{
            id: 'orgfailedbuilds',
            max: 1,
            aggr: 'sum'
        }];
        var currentFbuilds_conf = {
            label: 'Current Broken Builds',
            decimal: 0,
            icon: 'fa-sort-amount-desc',
            iconbackground: '#e21b23',
            background: 'transparent',
            labelcolor: '#000'
        };
        var currentFbuilds = new framework.widgets.CounterBox(currentFbuilds_dom, currentFbuilds_metrics, null, currentFbuilds_conf);

        // TOTAL EXECUTIONS
        var organizationexec_dom = document.getElementById("organizationexec");
        var organizationexec_metrics = [{
            id: 'orgexecutions',
            max: 1,
            aggr: 'sum'
        }];
        var organizationexec_conf = {
            label: 'Total Executions',
            decimal: 0,
            icon: 'fa fa-terminal',
            iconbackground: '#2A2A2A',
            background: 'transparent',
            labelcolor: '#000'
        };
        var organizationexec = new framework.widgets.CounterBox(organizationexec_dom, organizationexec_metrics, null, organizationexec_conf);


        // TOTAL SUCCESSFUL EXECUTIONS
        var organizationsuccessexec_dom = document.getElementById("organizationsuccessexec");
        var organizationsuccessexec_metrics = [{
            id: 'orgpassedexecutions',
            max: 1,
            aggr: 'sum'
        }];
        var organizationsuccessexec_conf = {
            label: 'Total successful Executions',
            decimal: 0,
            icon: 'fa fa-thumbs-up',
            iconbackground: '#069744',
            background: 'transparent',
            labelcolor: '#000'
        };
        var organizationsuccessexec = new framework.widgets.CounterBox(organizationsuccessexec_dom, organizationsuccessexec_metrics, null, organizationsuccessexec_conf);


        // TOTAL BROKEN EXECUTIONS
        var organizationbrokenexec_dom = document.getElementById("organizationbrokenexec");
        var organizationbrokenexec_metrics = [{
            id: 'orgfailedexecutions',
            max: 1,
            aggr: 'sum'
        }];
        var organizationbrokenexec_conf = {
            label: 'Total broken Executions',
            decimal: 0,
            icon: 'fa fa-thumbs-down',
            iconbackground: '#e21b23',
            background: 'transparent',
            labelcolor: '#000'
        };
        var organizationbrokenexec = new framework.widgets.CounterBox(organizationbrokenexec_dom, organizationbrokenexec_metrics, null, organizationbrokenexec_conf);

        // TIME TO FIX
        var orgtimetofix_dom = document.getElementById("orgtimetofix");
        var orgtimetofix_metrics = [{
            id: 'orgtimetofixtbd'
        }];
        var orgtimetofix_conf = {
            label: 'Average time to Fix',
            decimal: 1,
            icon: 'fa fa-line-chart',
            iconbackground: '#E70083',
            background: 'transparent',
            labelcolor: '#000',
            suffix: " h"
        };
        var orgtimetofix = new framework.widgets.CounterBox(orgtimetofix_dom, orgtimetofix_metrics, null, orgtimetofix_conf);

        // BUILD EXECUTION TIME
        var organizationexectime_dom = document.getElementById("orgbuildtime");
        var organizationexectime_metrics = [{
            id: 'orgbuildtimetbd'
        }];
        var organizationexectime_conf = {
            label: 'Build execution time',
            decimal: 1,
            icon: 'fa fa-history',
            iconbackground: '#8d197b',
            background: 'transparent',
            labelcolor: '#000',
            suffix: " h"
        };
        var organizationexectime = new framework.widgets.CounterBox(organizationexectime_dom, organizationexectime_metrics, null, organizationexectime_conf);

        // BUILD BROKEN TIME
        var organizationbrokentime_dom = document.getElementById("orgbrokentime");
        var organizationbrokentime_metrics = [{
            id: 'orgbrokentimetbd'
        }];
        var organizationbrokentime_conf = {
            label: 'Build broken time',
            decimal: 1,
            icon: 'fa fa-history',
            iconbackground: '#7C45CF',
            background: 'transparent',
            labelcolor: '#000',
            suffix: " d"
        };
        var organizationbrokentime = new framework.widgets.CounterBox(organizationbrokentime_dom, organizationbrokentime_metrics, null, organizationbrokentime_conf);

        // DEVELOPERS LINES CHART
        var dev_lines_dom = document.getElementById("dev-lines");
        var dev_lines_metrics = [
            {
                id: 'orgdevelopers',
                max: 100,
                aggr: 'sum'
            }
        ];
        var dev_lines_configuration = {
            xlabel: '',
            ylabel: '',
            interpolate: 'monotone',
            height: 250,
            labelFormat: '%data.info.title%',
            colors: ["#FF7F0E"],
            area: true
        };
        var skills_lines = new framework.widgets.LinesChart(dev_lines_dom, dev_lines_metrics,
                null, dev_lines_configuration);

        // EXECUTIONS LINES CHART
        var ex_lines_dom = document.getElementById("execs-lines");
        var ex_lines_metrics = [
            {
                id: 'orgexecutions',
                max: 100,
                aggr: 'sum'
            },
            {
                id: 'orgpassedexecutions',
                max: 100,
                aggr: 'sum'
            },
            {
                id: 'orgfailedexecutions',
                max: 100,
                aggr: 'sum'
            }
        ];
        var ex_lines_configuration = {
            xlabel: '',
            ylabel: '',
            interpolate: 'monotone',
            height: 250,
            labelFormat: '%data.info.title%',
            colors: {
                orgexecutions: "#1F77B4",
                orgpassedexecutions: "#68B828",
                orgfailedexecutions: "#FF7F0E"
            }
        };
        var ex_lines = new framework.widgets.LinesChart(ex_lines_dom, ex_lines_metrics,
                null, ex_lines_configuration);

        //ANGULAR INITIALIZATION

        angular.module('Dashboard', [])
                .controller('UsersController', ['$scope', function ($scope) {
                    $scope.changeToUserDashboard = function (user) {
                        var env = framework.dashboard.getEnv();
                        env['uid'] = user['uid'];
                        env['name'] = user['name'];
                        framework.dashboard.changeTo('developer', env);
                    };
                }])
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

        //USER LIST
        framework.data.observe(['userlist'], function (event) {

            if (event.event === 'data') {
                var users = event.data['userlist'][Object.keys(event.data['userlist'])[0]]['data'];

                $scope = angular.element(".main-content").scope();

                $scope.$apply(function () {
                    $scope.users = users;
                });

            }
        }, []);

        //REPO LIST
        framework.data.observe(['repolist'], function (event) {

            if (event.event === 'data') {
                var repos = event.data['repolist'][Object.keys(event.data['repolist'])[0]]['data'];

                $scope = angular.element(".main-content").scope();

                $scope.$apply(function () {
                    $scope.repos = repos;
                });

            }
        }, []);

        // Hide the loading animation
        finishLoading();
    }

@stop
