{{--
    Organization dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "https://cdn.rawgit.com/matthieua/WOW/master/dist/wow.js",
    "https://ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
    "//cdnjs.cloudflare.com/ajax/libs/angular-moment/0.9.0/angular-moment.min.js",
    "css!https://cdn.rawgit.com/daneden/animate.css/master/animate.css",
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
        <div class="section feature-section gradient-1 blue">
            <div class="container">
                <div class="row">
                    <div class="col col-sm-6 wow fadeInLeft animated" data-wow-duration="1.1s" data-wow-delay="0.2s">
                        <div class="screenshot ss-left">
                            <img class="feature-section-img 2x radius-img" height="100%" width="100%" src="/assets/images/sdh-architecture.png">
                        </div>
                    </div>
                    <div class="col col-md-4 col-sm-6 wow fadeInUp animated" data-wow-duration="1.1s">
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
        <div class="section">
            <div class="row row-centered">
                <div id="orgcommits" class="col-sm-3 col-centered"></div>
                <div id="orgdevelopers" class="col-sm-3 col-centered"></div>
                <div id="orgrepositories" class="col-sm-3 col-centered"></div>
                <div id="orgbuilds" class="col-sm-3 col-centered"></div>
                <div id="organizationexec" class="col-sm-3 col-centered"></div>
                <div id="organizationsuccessexec" class="col-sm-3 col-centered"></div>
                <div id="organizationbrokenexec" class="col-sm-3 col-centered"></div>
                <div id="organizationexectime" class="col-sm-3 col-centered"></div>
                <div id="organizationbrokentime" class="col-sm-3 col-centered"></div>
            </div>

        </div>
    </div>
    <div class="section gradient-2" ng-controller="UsersController">
        <div class="row row-centered search">
            <h4 class="white">Search developer: </h4><input ng-model="userQuery" placeholder="Developer name" />
        </div>
        <div class="row row-centered card-list">
            <div class="col-sm-3 col-centered card" ng-repeat="user in users | orderBy:'name'" ng-show="([user.name, user.avatar] | filter:userQuery).length" ng-click="changeToUserDashboard(user)">
                <span class="helper"></span>
                <img class="card-avatar img-circle" ng-src="@{{ user.avatar }}" alt="@{{ user.name }}">
                <h2 class="card-name">@{{ user.name }}</h2>
            </div>
        </div>
    </div>
    <div class="section gradient-2" ng-controller="ReposController">
        <div class="row row-centered search">
            <h4 class="white">Search repo: </h4><input ng-model="repoQuery" placeholder="Repository name" />
        </div>
        <div class="row row-centered card-list">
            <div class="col-sm-3 col-centered card" ng-repeat="repo in repos | orderBy:'name'" ng-show="([repo.name, repo.avatar] | filter:repoQuery).length" ng-click="changeToRepoDashboard(repo)">
                <span class="helper"></span>
                <img class="card-avatar img-circle" ng-src="@{{ repo.avatar }}" alt="@{{ repo.name }}">
                <h2 class="card-name">@{{ repo.name }}</h2>
            </div>
        </div>
    </div>
@stop

@section('script')
<!--script-->
    $(".timeMidBox").hide();
    $("#timeBar").hide();

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
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
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
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
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
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
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
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
    };
    var orgbuilds = new framework.widgets.CounterBox(orgbuilds_dom, orgbuilds_metrics, null, orgbuilds_conf);

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
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
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
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
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
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
    };
    var organizationbrokenexec = new framework.widgets.CounterBox(organizationbrokenexec_dom, organizationbrokenexec_metrics, null, organizationbrokenexec_conf);


    // BUILD BROKEN TIME
    var organizationbrokentime_dom = document.getElementById("organizationbrokentime");
    var organizationbrokentime_metrics = [{
        id: 'organizationbrokentime',
        max: 1,
        aggr: 'sum'
    }];
    var organizationbrokentime_conf = {
        label: 'Build broken time',
        decimal: 0,
        icon: 'octicon octicon-history',
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
    };
    var organizationbrokentime = new framework.widgets.CounterBox(organizationbrokentime_dom, organizationbrokentime_metrics, null, organizationbrokentime_conf);

    // BUILD EXECUTION TIME
    var organizationexectime_dom = document.getElementById("organizationexectime");
    var organizationexectime_metrics = [{
        id: 'organizationexectime',
        max: 1,
        aggr: 'sum'
    }];
    var organizationexectime_conf = {
        label: 'Build execution time',
        decimal: 0,
        icon: 'octicon octicon-clock',
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
    };
    var organizationexectime = new framework.widgets.CounterBox(organizationexectime_dom, organizationexectime_metrics, null, organizationexectime_conf);


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