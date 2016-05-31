@extends('layouts.template')

@section('require')
    [
    "//ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
    "css!assets/css/dashboards/organization-dashboard",
    "sdh-framework/widgets/RangeNv/rangeNv"
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
        <div class="row row-centered">
            <div class="col-sm-4">
                <div id="projects-table" class="widget"></div>
            <div>
        </div>
    </div>
@stop

@section('script')
    /*<script>*/
    function _() {

        var timeCtx = "time-context";
        var productCtx = "product-context";
        var projectsCtx = "projects-context";

        //Show header chart and set titles
        setTitle("Product");
        setSubtitle(framework.dashboard.getEnv('name'));
        showHeaderChart();

        framework.data.updateContext(productCtx, {prid: framework.dashboard.getEnv()['prid']});


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


            //  ----------------------------------- PROJECTS TABLE ------------------------------------------
            var table_dom = document.getElementById("projects-table");
            var table_metrics = ['view-product-projects'];
            var table_configuration = {
                columns: [
                    {
                        label: "",
                        link: {
                            img: "avatar", //or label
                            href: "project",
                            env: [
                                {
                                    property: "pjid",
                                    as: "pjid"
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
                        id: projectsCtx,
                        filter: [
                            {
                                property: "pjid",
                                as: "pjid"
                            }
                        ]
                    }
                ],
                keepSelectedByProperty: "pjid",
                selectable: true,
                minRowsSelected: 1,
                maxRowsSelected: 1,
                filterControl: true,
                initialSelectedRows: 1,
                showHeader: false,
                alwaysOneSelected: true,
                scrollButtons: true,
                height: 568
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [timeCtx, productCtx], table_configuration);


        };

    }
@stop
