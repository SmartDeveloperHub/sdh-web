@extends('layouts.template')

@section('require')
    [
    "//ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
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

        var timeCtx = "time-context";
        var projectCtx = "project-context";

        //Show header chart and set titles
        setTitle("Project");
        setSubtitle(framework.dashboard.getEnv('name'));
        showHeaderChart();
        console.log("framework.dashboard.getEnv()['pid']: " + framework.dashboard.getEnv()['pid']);
        framework.data.updateContext(projectCtx, {pid: framework.dashboard.getEnv()['pid']});

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
                            env['rid'] = repo['repositoryid'];
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
                    $scope = angular.element(".main-content").scope();

                    $scope.$apply(function () {
                        $scope.repos = repos;
                    });

                }
            },[projectCtx, timeCtx]);

        };

    }
@stop
