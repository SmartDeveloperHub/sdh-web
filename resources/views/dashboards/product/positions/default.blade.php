@extends('layouts.template')

@section('require')
    [
    "https://ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
    "css!assets/css/dashboards/organization-dashboard"
    ]
@stop

@section('html')
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
@stop

@section('script')
    /*<script>*/
    function _() {

        var timeCtx = "time-context";
        var productCtx = "product-context";

        //Show header chart and set titles
        setTitle("Product");
        setSubtitle(framework.dashboard.getEnv('name'));
        showHeaderChart();

        framework.data.updateContext(productCtx, {prid: framework.dashboard.getEnv()['prid']});

        // UPPER SELECTOR RANGENV (NEEDS FIRST COMMIT)
        framework.data.observe(['productinfo'], function (event) {

            if (event.event === 'data') {
                var productinfo = event.data['productinfo'][Object.keys(event.data['productinfo'])[0]]['data'];
                var firstCommit =  productinfo['firstcommit'];

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
            }
        }, [productCtx]);


        var loadTimeDependentWidgets = function() {

            //ANGULAR INITIALIZATION
            // TODO: add to documentation... This is the manual initialization of angular, with the difference that it is done
            // with .main-content instead of document. https://docs.angularjs.org/guide/bootstrap#manual-initialization
            angular.module('Dashboard', [])
                    .controller('ProjectsController', ['$scope', function ($scope) {
                        $scope.changeToProjectDashboard = function (project) {
                            var env = framework.dashboard.getEnv();
                            env['pid'] = project['projectid'];
                            env['name'] = project['name'];
                            framework.dashboard.changeTo('project', env);
                        };
                    }]);

            angular.element(".main-content").ready(function () {
                angular.bootstrap(".main-content", ['Dashboard']);
            });

            //USER LIST
            framework.data.observe(['projectlist'], function (event) {

                if (event.event === 'data') {
                    var projects = event.data['projectlist'][Object.keys(event.data['projectlist'])[0]]['data'];

                    $scope = angular.element(".main-content").scope();

                    $scope.$apply(function () {
                        $scope.projects = projects;
                    });

                }
            }, []);


        };

    }
@stop
