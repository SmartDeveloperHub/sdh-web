@extends('layouts.template')

@section('require')
    [
    "https://ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
    "css!assets/css/dashboards/organization-dashboard"
    ]
@stop

@section('html')
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
@stop

@section('script')
    /*<script>*/
    function _() {

        //Show header chart and set titles
        setTitle("Project");
        setSubtitle(framework.dashboard.getEnv('name'));

        //Hide header chart
        hideHeaderChart();


        //ANGULAR INITIALIZATION
        // TODO: add to documentation... This is the manual initialization of angular, with the difference that it is done
        // with .main-content instead of document. https://docs.angularjs.org/guide/bootstrap#manual-initialization
        angular.module('Dashboard', [])
                .controller('ReposController', ['$scope', function ($scope) {
                    $scope.changeToRepoDashboard = function (repo) {
                        var env = framework.dashboard.getEnv();
                        env['rid'] = repo['repositoryid'];
                        env['name'] = repo['name'];
                        framework.dashboard.changeTo('repository', env);
                    };
                }]);

        angular.element(".main-content").ready(function () {
            angular.bootstrap(".main-content", ['Dashboard']);
        });

        //USER LIST
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
