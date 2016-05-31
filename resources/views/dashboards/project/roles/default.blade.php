@extends('layouts.template')

@section('require')
    [
    "//ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js",
    "css!assets/css/dashboards/organization-dashboard",
    "sdh-framework/widgets/RangeNv/rangeNv",
    "sdh-framework/widgets/Table/table",
    "css!assets/css/info-box"
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
                            <span id="firstIco" class="theicon fa fa-user-secret"></span><span class="thelabel">Manager:</span><span class="theVal blurado" id="project-manager">-------</span>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row static-info-line">
                            <span id="lastIco" class="theicon fa fa-cubes" style="color: #C0485E"></span><span class="thelabel">Number of projects:</span><span class="theVal blurado" id="project-repositories-number">--------</span>
                        </div>
                        <div class="row static-info-line">
                            <span id="lastIco" class="theicon octicon octicon-git-branch" style="color: #8A1978"></span><span class="thelabel">Last commit:</span><span class="theVal blurado" id="project-last-commit">--------</span>
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
    <div class="row row-centered">
        <div class="col-sm-4">
            <div id="repositories-table" class="widget"></div>
        <div>
    </div>
@stop

@section('script')
    /*<script>*/
    function _() {

        var timeCtx = "time-context";
        var projectCtx = "project-context";
        var repositoriesCtx = "repositories-context";

        //Show header chart and set titles
        setTitle("Project");
        setSubtitle(framework.dashboard.getEnv('name'));
        showHeaderChart();
        framework.data.updateContext(projectCtx, {pjid: framework.dashboard.getEnv()['pjid']});

        framework.data.observe(['projectinfo'], function (event) {

            if (event.event === 'data') {
                var productInfo = event.data['projectinfo'][Object.keys(event.data['projectinfo'])[0]]['data'];

                var creation = document.getElementById('project-created');
                var manager = document.getElementById('project-manager');
                var repositories_number = document.getElementById('project-repositories-number');
                var last_commit = document.getElementById('project-last-commit');

                $(creation).removeClass('blurado');
                $(manager).removeClass('blurado');
                $(repositories_number).removeClass('blurado');
                $(last_commit).removeClass('blurado');

                if (productInfo['avatar'] != null && productInfo['avatar'] !== "" && productInfo['avatar'] !== "http://avatarURL") {
                    $("#avatar").css("background-image", "url(" + productInfo['avatar'] + ")");
                }

                //TODO: fill the data in the project info

            }
        }, [projectCtx]);

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
                    $scope = angular.element(".main-content").scope();

                    $scope.$apply(function () {
                        $scope.repos = repos;
                    });

                }
            },[projectCtx, timeCtx]);

        };

        //  ----------------------------------- REPOSITORIES TABLE ------------------------------------------
        var table_dom = document.getElementById("repositories-table");
        var table_metrics = ['view-project-repositories'];
        var table_configuration = {
            columns: [
                {
                    label: "",
                    link: {
                        img: "avatar", //or label
                        href: "repository",
                        env: [
                            {
                                property: "rid",
                                as: "rid"
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
                    id: repositoriesCtx,
                    filter: [
                        {
                            property: "rid",
                            as: "rid"
                        }
                    ]
                }
            ],
            keepSelectedByProperty: "rid",
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
        var table = new framework.widgets.Table(table_dom, table_metrics, [timeCtx, projectCtx], table_configuration);


    }
@stop
