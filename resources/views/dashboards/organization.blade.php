{{--
    Organization dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "https://cdn.rawgit.com/matthieua/WOW/master/dist/wow.js",
    "https://ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
    "//cdnjs.cloudflare.com/ajax/libs/angular-moment/0.9.0/angular-moment.min.js",
    "css!assets/css/animate.css",
    "css!assets/css/dashboards/organization-dashboard",
    "sdh-framework/framework.widget.counterbox"
    ]
@stop

@section('html')
    <div class="dashContainer" ng-app>
        <div class="section initial-section section-shadow gradient-2 white wow pulse animated" data-wow-duration="3s" data-wow-iteration="infinite" data-wow-delay="300ms">
            <div class="container">
                <div class="row">
                    <div class="center-block p4 initial-section-content">
                        <h2 class="h1 thin h-has-icon roboto page-title">Smart Developer Hub</h2>
                        <p class="h4 page-title-content">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div id="arqPanel" class="section feature-section gradient-1">
            <div class="container">
                <div class="row">
                    <div class="col col-sm-6 wow fadeInLeft animated" data-wow-duration="1.1s" data-wow-delay="0.2s">
                        <div class="screenshot ss-left">
                            <img class="feature-section-img 2x radius-img" height="100%" width="100%" src="/assets/images/sdh-architecture.png">
                        </div>
                    </div>
                    <div class="col col-md-6 col-sm-6 wow fadeInUp animated" data-wow-duration="1.1s">
                        <div class="left p4 feature">
                            <h2 class="h1 thin  h-has-icon roboto">A Linked Data Platform</h2>
                            <p class="lighter-gray h4">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
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
                <div id="orgexectime" class="col-sm-4 col-centered"></div>
                <div id="orgbrokentime" class="col-sm-4 col-centered"></div>
            </div>
        </div>
    </div>
    <div id="usersPanel" class="section" ng-controller="UsersController">
        <div id="develHeader" class="row row-centered">
            <span id="headIcon" class="headIcon octicon octicon-organization"></span>
            <span class="headTitle">Developers</span>
        </div>
        <div class="row row-centered search">
            <span id="searchDevLabel" >Search developer: </span><input ng-model="userQuery" placeholder="Developer name" />
        </div>
        <div class="row row-centered card-list">
            <div class="col-sm-3 col-centered card" ng-repeat="user in users | orderBy:'name'" ng-show="([user.name, user.avatar] | filter:userQuery).length" ng-click="changeToUserDashboard(user)">
                <span class="helper"></span>
                <img class="card-avatar img-circle" ng-src="@{{ user.avatar }}" alt="@{{ user.name }}">
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
            <span id="searchRepLabel" >Search repository: </span><input ng-model="repoQuery" placeholder="Repository name" />
        </div>
        <div class="row row-centered card-list">
            <div class="col-sm-3 col-centered card" ng-repeat="repo in repos | orderBy:'name'" ng-show="([repo.name, repo.avatar] | filter:repoQuery).length" ng-click="changeToRepoDashboard(repo)">
                <span class="helper"></span>
                <img class="card-avatar img-circle" ng-src="@{{ repo.avatar }}" alt="@{{ repo.name }}">
                <span class="card-name">@{{ repo.name }}</span>
            </div>
        </div>
    </div>
@stop

@section('script')

    //Show header chart and set titles
    setTitle("");
    setSubtitle("");
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
        iconbackground: '#E70083',
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
        iconbackground: '#F7853C',
        background: 'transparent',
        labelcolor: '#000'
    };
    var orgbuilds = new framework.widgets.CounterBox(orgbuilds_dom, orgbuilds_metrics, null, orgbuilds_conf);

   // TOTAL CURRENT SUCCESS BUILDS
    var currentbuilds_dom = document.getElementById("orgcurrentsuccessbuilds");
    var currentbuilds_metrics = [{
        id: 'orgsuccessbuilds',
        max: 1,
        aggr: 'sum'
    }];
    var currentbuilds_conf = {
        label: 'Current Success Builds',
        decimal: 0,
        icon: 'fa fa-cogs',
        iconbackground: '#F7853C',
        background: 'transparent',
        labelcolor: '#000'
    };
    var currentbuilds = new framework.widgets.CounterBox(currentbuilds_dom, currentbuilds_metrics, null, currentbuilds_conf);

   // TOTAL CURRENT BROKEN BUILDS
    var currentFbuilds_dom = document.getElementById("orgcurrentbrokenbuilds");
    var currentFbuilds_metrics = [{
        id: 'orgbrokenbuilds',
        max: 1,
        aggr: 'sum'
    }];
    var currentFbuilds_conf = {
        label: 'Current Broken Builds',
        decimal: 0,
        icon: 'fa fa-cogs',
        iconbackground: '#F7853C',
        background: 'transparent',
        labelcolor: '#000'
    };
    var currentFbuilds = new framework.widgets.CounterBox(currentFbuilds_dom, currentFbuilds_metrics, null, currentFbuilds_conf);

    // TOTAL EXECUTIONS
    var organizationexec_dom = document.getElementById("organizationexec");
    var organizationexec_metrics = [{
        id: 'organizationexec',
        max: 1,
        aggr: 'sum'
    }];
    var organizationexec_conf = {
        label: 'Total executions',
        decimal: 0,
        icon: 'octicon octicon-flame',
        iconbackground: '#FFAC00',
        background: 'transparent',
        labelcolor: '#000'
    };
    var organizationexec = new framework.widgets.CounterBox(organizationexec_dom, organizationexec_metrics, null, organizationexec_conf);


    // TOTAL SUCCESSFUL EXECUTIONS
    var organizationsuccessexec_dom = document.getElementById("organizationsuccessexec");
    var organizationsuccessexec_metrics = [{
        id: 'organizationsuccessexec',
        max: 1,
        aggr: 'sum'
    }];
    var organizationsuccessexec_conf = {
        label: 'Total successful executions',
        decimal: 0,
        icon: 'octicon octicon-thumbsup',
        iconbackground: '#069744',
        background: 'transparent',
        labelcolor: '#000'
    };
    var organizationsuccessexec = new framework.widgets.CounterBox(organizationsuccessexec_dom, organizationsuccessexec_metrics, null, organizationsuccessexec_conf);


    // TOTAL BROKEN EXECUTIONS
    var organizationbrokenexec_dom = document.getElementById("organizationbrokenexec");
    var organizationbrokenexec_metrics = [{
        id: 'organizationbrokenexec',
        max: 1,
        aggr: 'sum'
    }];
    var organizationbrokenexec_conf = {
        label: 'Total broken executions',
        decimal: 0,
        icon: 'octicon octicon-thumbsdown',
        iconbackground: '#E21B23',
        background: 'transparent',
        labelcolor: '#000'
    };
    var organizationbrokenexec = new framework.widgets.CounterBox(organizationbrokenexec_dom, organizationbrokenexec_metrics, null, organizationbrokenexec_conf);

    // TIME TO FIX
    var orgtimetofix_dom = document.getElementById("orgtimetofix");
    var orgtimetofix_metrics = [{
        id: 'organizationexectime',
        max: 1,
        aggr: 'sum'
    }];
    var orgtimetofix_conf = {
        label: 'Average time to Fix broken builds',
        decimal: 0,
        icon: 'octicon octicon-clock',
        iconbackground: '#8D197B',
        background: 'transparent',
        labelcolor: '#000',
        suffix: " days"
    };
    var orgtimetofix = new framework.widgets.CounterBox(orgtimetofix_dom, orgtimetofix_metrics, null, orgtimetofix_conf);

    // BUILD EXECUTION TIME
    var organizationexectime_dom = document.getElementById("orgexectime");
    var organizationexectime_metrics = [{
        id: 'organizationexectime',
        max: 1,
        aggr: 'sum'
    }];
    var organizationexectime_conf = {
        label: 'Build execution time',
        decimal: 0,
        icon: 'octicon octicon-clock',
        iconbackground: '#8D197B',
        background: 'transparent',
        labelcolor: '#000',
        suffix: " days"
    };
    var organizationexectime = new framework.widgets.CounterBox(organizationexectime_dom, organizationexectime_metrics, null, organizationexectime_conf);

    // BUILD BROKEN TIME
    var organizationbrokentime_dom = document.getElementById("orgbrokentime");
    var organizationbrokentime_metrics = [{
        id: 'organizationbrokentime',
        max: 1,
        aggr: 'sum'
    }];
    var organizationbrokentime_conf = {
        label: 'Build broken time',
        decimal: 0,
        icon: 'octicon octicon-history',
        iconbackground: '#5F65D7',
        background: 'transparent',
        labelcolor: '#000',
        suffix: " days"
    };
    var organizationbrokentime = new framework.widgets.CounterBox(organizationbrokentime_dom, organizationbrokentime_metrics, null, organizationbrokentime_conf);

    //ANGULAR INITIALIZATION
    try {
        angular.module('OrganizationDashboard');
        angular.element(document).injector().invoke(function($compile) {
            var content = $(".main-content");
            var scope = angular.element(content).scope();
            $compile(content)(scope);
        });

    } catch(e) { //Module not initialized

        angular.module('OrganizationDashboard', [])
                .controller('UsersController', ['$scope', function ($scope) {
                    $scope.changeToUserDashboard = function(user) {
                        var env = framework.dashboard.getEnv();
                        env['uid'] = user['userid'];
                        framework.dashboard.changeTo('user-dashboard', env);
                    };
                }])
                .controller('ReposController', ['$scope', function ($scope) {
                    $scope.changeToRepoDashboard = function(repo) {
                        var env = framework.dashboard.getEnv();
                        env['rid'] = repo['repositoryid'];
                        framework.dashboard.changeTo('repo-dashboard', env);
                    };
                }]);

        angular.element(document).ready(function() {
            angular.bootstrap(document, ['OrganizationDashboard']);
        });
    }

    //USER LIST
    framework.data.observe(['userlist'], function(event){
        if(event.event === 'loading') {
            //TODO
        } else if(event.event === 'data') {
            var users = event.data['userlist'][Object.keys(event.data['userlist'])[0]]['data'];

            $scope = angular.element($(".main-content")).scope();

            $scope.$apply(function () {
                $scope.users = users;
            });

        }
    }, []);

    //REPO LIST
    framework.data.observe(['repolist'], function(event){
        if(event.event === 'loading') {
            //TODO
        } else if(event.event === 'data') {
            var repos = event.data['repolist'][Object.keys(event.data['repolist'])[0]]['data'];

            $scope = angular.element($(".main-content")).scope();

            $scope.$apply(function () {
                $scope.repos = repos;
            });

        }
    }, []);


@stop