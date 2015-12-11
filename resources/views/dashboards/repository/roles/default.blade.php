{{--
    User dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "vendor/sdh-framework/framework.widget.counterbox",
    "vendor/sdh-framework/framework.widget.horizontalbar",
    "vendor/sdh-framework/framework.widget.table",
    "vendor/sdh-framework/framework.widget.linesChart",
    "vendor/sdh-framework/framework.widget.rangeNv",
    "vendor/sdh-framework/framework.widget.radarchart",
    "vendor/sdh-framework/framework.widget.multibar",
    "css!assets/css/dashboards/repository-dashboard"
    ]
@stop

@section('html')
    <div class="row" id="RepoInfoBox">
        <!--div class="row titleRow" id="repoInfoTitle">
            <span id="detailsIco" class="titleIcon fa fa-info-circle"></span>
            <span class="titleLabel">Repository Details</span>
        </div-->
        <div class="row">
            <div class="com-widget widget static-info-widget col-sm-12">
                <div class="row">
                    <div class="col-sm-2 avatarBox">
                        <div id="avatar" class="avatar octicon octicon-repo"></div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row staticInfoLine">
                            <span id="createdIco" class="theicon fa fa-pencil-square-o"></span><span class="thelabel">Created:</span><span class="theVal blurado" id="repo-created">July 3rd 2012</span>
                        </div>
                        <div class="row staticInfoLine">
                            <span id="firstIco" class="theicon octicon octicon-git-branch"></span><span class="thelabel">First commit:</span><span class="theVal blurado" id="repo-first">July 3rd 2012</span>
                        </div>
                        <div class="row staticInfoLine"> 
                            <span id="lastIco" class="theicon octicon octicon-git-branch"></span><span class="thelabel">Last commit:</span><span class="theVal blurado" id="repo-last">Jan 7rd 2015</span>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row staticInfoLine buildStatusRow"> 
                            <span id="buildStatusIco" class="theicon fa fa-history"></span><span class="thelabel">Last Build:</span><span class="theVal blurado" id="repo-buildstatus"> TheBuild OK</span>
                        </div>
                        <div class="row staticInfoLine buildStatusRow"> 
                            <span id="repStatusIco" class="theicon octicon octicon-globe"></span><span class="thelabel">Status:</span><span class="theVal blurado" id="repo-status"> repository OK</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="widgetsRow">
        <div class="row">
            <div id="total-commits" class="boxCounter col-sm-4"></div>
            <div id="total-developers" class="boxCounter col-sm-4"></div>
            <div id="total-executions" class="boxCounter col-sm-4"></div>
            
        </div>
        <div class="row">
            <div id="passed-build-exec" class="boxCounter col-sm-4"></div>
            <div id="broken-build-exec" class="boxCounter col-sm-4"></div>
            <div id="avg-commits" class="boxCounter col-sm-4"></div>
        </div>
        <div class="row">
            <div id="avg-time-to-fix" class="boxCounter col-sm-4"></div>
            <div id="avg-build-time" class="boxCounter col-sm-4"></div>
            <div id="avg-broken-time" class="boxCounter col-sm-4"></div>
        </div>
    </div>
    <div class="row" id="devActivBox">
        <div class="row titleRow" id="devActivityTitle">
            <span id="devActIco" class="titleIcon titleIcon octicon octicon-dashboard"></span>
            <span class="titleLabel">Contribution</span>
        </div>
        <div class="row" id="activityChart"></div>
    </div>
    <div class="row" id="RepoCIBox">
        <div class="row titleRow" id="RepoCITitle">
            <span id="ciTitIco" class="titleIcon fa fa-history"></span>
            <span class="titleLabel">Continuous Integration</span>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div id="executions-info" class="widget">
                    <div id="executions-info-titleBox">
                        <span id="executions-info-icon" class="octicon octicon-chevron-right"></span>
                        <span id="executions-info-title">Executions</span>
                    </div>
                    <div id="executions-info-compare">
                        <span id="successNum">0</span><span> successful / </span><span id="brokenNum">0</span><span> broken</span> 
                    </div>
                    <div id="executions-info-percent">
                        <span id="percentBall">
                            <span class="percentlabel" id="execPercent">0</span>
                            <span class="percentlabel">%</span>
                        </span>
                    </div>
                    <div id="executions-info-total">Total executions: <span id="totalNum">0</span></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div id="executions-stacked" class="widget"></div>
            </div>
        </div>
        <div class="row" id="execChartBox">
            <div class="row" id="execChart"></div>
        </div>
    </div>
    <div class="row" id="UserRepoBox">
        <div class="row titleRow" id="userRepoTitle">
            <span id="repoIco" class="titleIcon octicon octicon-organization"></span>
            <span class="titleLabel">Developers</span>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="user-commits-horizontal" class="widget"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div id="user-commits-lines" class="widget"></div>
                    </div>
                </div>
                <!--div class="row">
                    <div class="col-sm-12">
                        <div id="projects-languages" class="widget"></div>
                    </div>
                </div-->
            </div>
            <div class="col-sm-3">
                <div id="users-table" class="widget"></div>
            </div>
        </div>
    </div>
@stop

@section('script')
/* <script> */

    function _() {
        //Contexts used in this dashboard
        var repoCtx = "repository-context";
        var timeCtx = "time-context";
        var usersTableCtx = "user-table-context";

        //Show header chart and set titles
        setTitle("Repositories");
        showHeaderChart();

        //TODO: improve get env and set env. Return copies instead of the object and allow to get and set only one element.
        var env = framework.dashboard.getEnv();
        framework.data.updateContext(repoCtx, {rid: env['rid']});
        if (env['name'] != null) {
            setSubtitle(env['name']);
        }

        // UPPER SELECTOR RANGENV (NEEDS FIRST COMMIT)
        framework.data.observe(['repoinfo'], function (event) {

            if (event.event === 'data') {
                var repoinfo = event.data['repoinfo'][Object.keys(event.data['repoinfo'])[0]]['data'];
                var firstCommit =  repoinfo['firstcommit'];

                var rangeNv_dom = document.getElementById("fixed-chart");
                var rangeNv_metrics = [
                    {
                        id: 'repository-activity',
                        max: 101,
                        aggr: 'sum',
                        from: moment(firstCommit).format("YYYY-MM-DD")
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

                var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [repoCtx], rangeNv_configuration);
                $(rangeNv).on("CONTEXT_UPDATED", function () {
                    $(rangeNv).off("CONTEXT_UPDATED");
                    loadTimeDependentWidgets();

                    // Hide the loading animation
                    finishLoading();
                });
            }
        }, [repoCtx]);

        var loadTimeDependentWidgets = function loadTimeDependentWidgets() {

            // TOTAL COMMITS
            var total_commits_dom = document.getElementById("total-commits");
            var total_commits_metrics = [{
                id: 'repository-commits',
                max: 1,
                aggr: 'sum'
            }];
            var total_commits_conf = {
                label: 'Total commits',
                decimal: 0,
                icon: 'octicon octicon-git-commit',
                iconbackground: 'rgb(0, 75, 139)',
                background: 'transparent'
            };
            var total_commits = new framework.widgets.CounterBox(total_commits_dom, total_commits_metrics, [timeCtx, repoCtx], total_commits_conf);

            // TOTAL DEVELOPERS
            var total_users_dom = document.getElementById("total-developers");
            var total_users_metrics = [{
                id: 'repository-developers',
                max: 1,
                aggr: 'sum'
            }];
            var total_users_conf = {
                label: 'Total developers',
                decimal: 0,
                icon: 'octicon octicon-organization',
                iconbackground: 'rgb(247, 133, 60)',
                background: 'transparent'
            };
            var total_users = new framework.widgets.CounterBox(total_users_dom, total_users_metrics, [timeCtx, repoCtx], total_users_conf);

            // TOTAL EXECUTIONS
            var total_executions_dom = document.getElementById("total-executions");
            var total_executions_metrics = [{
                id: 'repository-executions',
                max: 1,
                aggr: 'sum'
            }];
            var total_executions_conf = {
                label: 'Total build executions',
                decimal: 0,
                icon: 'fa fa-terminal',
                iconbackground: 'rgb(42, 42, 42)',
                background: 'transparent'
            };
            var total_executions_issues = new framework.widgets.CounterBox(total_executions_dom, total_executions_metrics, [timeCtx, repoCtx], total_executions_conf);

            // TOTAL EXECUTIONS
            var broken_exec_dom = document.getElementById("broken-build-exec");
            var broken_exec_metrics = [{
                id: 'failed-repository-executions',
                max: 1,
                aggr: 'sum'
            }];
            var broken_exec_conf = {
                label: 'Broken build executions',
                decimal: 0,
                icon: 'fa fa-thumbs-down',
                iconbackground: '#e21b23',
                background: 'transparent'
            };
            var broken_exec = new framework.widgets.CounterBox(broken_exec_dom, broken_exec_metrics, [timeCtx, repoCtx], broken_exec_conf);

            // SUCCESSFUL EXECUTIONS
            var success_executions_dom = document.getElementById("passed-build-exec");
            var success_executions_metrics = [{
                id: 'passed-repository-executions',
                max: 1,
                aggr: 'sum'
            }];
            var success_executions_conf = {
                label: 'Successful build executions',
                decimal: 0,
                icon: 'fa fa-thumbs-up',
                iconbackground: 'rgb(6, 151, 68)',
                background: 'transparent'
            };
            var success_executions_issues = new framework.widgets.CounterBox(success_executions_dom, success_executions_metrics, [timeCtx, repoCtx], success_executions_conf);

            // AVG COMMITS
            var avg_commits_dom = document.getElementById("avg-commits");
            var avg_commits_metrics = [{
                id: 'repository-commits',
                max: 1,
                aggr: 'avg'
            }];
            var avg_commits_conf = {
                label: 'Average commits per day',
                decimal: 2,
                icon: 'octicon octicon-git-merge',
                iconbackground: 'rgb(192, 72, 94)',
                background: 'transparent'
            };
            var avg_commits = new framework.widgets.CounterBox(avg_commits_dom, avg_commits_metrics, [timeCtx, repoCtx], avg_commits_conf);

            // AVG TIME TO FIX
            var avg_time_to_fix_dom = document.getElementById("avg-time-to-fix");
            var avg_time_to_fix_metrics = [{
                id: 'repository-timetofix',
                max: 1,
                aggr: 'avg'
            }];
            var avg_time_to_fix_conf = {
                label: 'Average time to fix',
                decimal: 2,
                icon: 'fa fa-line-chart',
                iconbackground: 'rgb(231, 0, 131)',
                background: 'transparent',
                suffix: " h"
            };
            var avg_time_to_fix = new framework.widgets.CounterBox(avg_time_to_fix_dom, avg_time_to_fix_metrics, [timeCtx, repoCtx], avg_time_to_fix_conf);

            // BUILD TIME
            var avg_build_time_dom = document.getElementById("avg-build-time");
            var avg_build_time_metrics = [{
                id: 'repository-buildtime',
                max: 1,
                aggr: 'sum'
            }];
            var avg_build_time_conf = {
                label: 'Build execution time',
                decimal: 2,
                icon: 'fa fa-history',
                iconbackground: 'rgb(141, 25, 123)',
                background: 'transparent',
                suffix: " h"
            };
            var avg_build_time = new framework.widgets.CounterBox(avg_build_time_dom, avg_build_time_metrics, [timeCtx, repoCtx], avg_build_time_conf);

            // BROKEN TIME
            var avg_broken_time_dom = document.getElementById("avg-broken-time");
            var avg_broken_time_metrics = [{
                id: 'repository-brokentime',
                max: 1,
                aggr: 'sum'
            }];
            var avg_broken_time_conf = {
                label: 'Build broken time',
                decimal: 2,
                icon: 'fa fa-history',
                iconbackground: 'rgb(124, 69, 207)',
                background: 'transparent',
                suffix: " d"
            };
            var avg_broken_time = new framework.widgets.CounterBox(avg_broken_time_dom, avg_broken_time_metrics, [timeCtx, repoCtx], avg_broken_time_conf);

            // ACTIVITY LINE CHART
            var activity_dom = document.getElementById("activityChart");
            var activity_metrics = [
                {
                    id: 'repository-commits',
                    max: 30
                },
                {
                    id: 'repository-commits',
                    max: 30,
                    aggr: "avg"
                }
            ];
            var activity_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: '¬_D.data.info.title¬',
                colors: ["#8D197B", "#2876B8"],
                area: true,
                _demo: true // Only for demo
            };
            var activity = new framework.widgets.LinesChart(activity_dom, activity_metrics,
                    [timeCtx, repoCtx], activity_configuration);

            // CI LINE CHART
            var ci_dom = document.getElementById("execChart");
            var ci_metrics = [
                {
                    id: 'repository-executions',
                    max: 30
                },
                {
                    id: 'failed-repository-executions',
                    max: 30
                },
                {
                    id: 'passed-repository-executions',
                    max: 30
                }
            ];
            var ci_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: '¬_D.data.info.title¬',
                colors: {
                    'repository-executions': "#004B8B",
                    'failed-repository-executions': "#DB0013",
                    'passed-repository-executions': "#0A8931"
                }
            };
            var ci_lines = new framework.widgets.LinesChart(ci_dom, ci_metrics,
                    [timeCtx, repoCtx], ci_configuration);


            // REPOSITORY META INFO
            framework.data.observe(['repoinfo'], function (event) {
                if (event.event === 'loading') {
                    //TODO
                } else if (event.event === 'data') {
                    var repoinfo = event.data['repoinfo'][Object.keys(event.data['repoinfo'])[0]]['data'];
                    //Set header subtitle
                    setSubtitle(repoinfo['name']);

                    //Set data
                    var creation = document.getElementById('repo-created');
                    var rbuildstatus = document.getElementById('repo-buildstatus');
                    var rfirstc = document.getElementById('repo-first');
                    var rlastc = document.getElementById('repo-last');
                    var repostatus = document.getElementById('repo-status');
                    creation.innerHTML = moment(new Date(repoinfo['createdon'])).format('MMMM Do YYYY');
                    rfirstc.innerHTML = moment(new Date(repoinfo['firstcommit'])).format('MMMM Do YYYY');
                    rlastc.innerHTML = moment(new Date(repoinfo['lastcommit'])).format('MMMM Do YYYY');
                    rbuildstatus.innerHTML = (repoinfo['buildStatus'] ?
                            '<i class="fa fa-thumbs-up" style="color: rgb(104, 184, 40);"></i><span class="passedLabel">(Passed)</span>' :
                            '<i class="fa fa-thumbs-down" style="color: rgb(200, 104, 40);"></i><span class="errorLabel">(Error)</span>');
                    repostatus.innerHTML = (repoinfo['ispublic'] ?
                            '<i title="Public" class="fa fa-eye publicIco"></i><span class="publicLabel">(Public)</span>' :
                            '<i title="Private" class="octicon octicon-loc privateIco"></i><span class="privateLabel">(Private)</span>');

                    $(creation).removeClass('blurado');
                    $(rfirstc).removeClass('blurado');
                    $(rlastc).removeClass('blurado');
                    $(rbuildstatus).removeClass('blurado');
                    $(repostatus).removeClass('blurado');
                    $("#avatar").removeClass('octicon octicon-repo');

                    if (repoinfo['avatar'] !== undefined && repoinfo['avatar'] !== null && repoinfo['avatar'] !== "" && repoinfo['avatar'] !== "http://avatarURL") {
                        $("#avatar").css("background-image", "url(" + repoinfo['avatar'] + ")");
                    } else {
                        $("#avatar").css("background-image", "url(../../assets/images/user-4.png)");
                    }
                }
            }, [repoCtx]);

            // EXECUTIONS MULTIBAR
            var executions_dom = document.getElementById("executions-stacked");
            var executions_metrics = [
                {
                    id: 'passed-repository-executions',
                    max: 30
                },
                {
                    id: 'failed-repository-executions',
                    max: 30
                }];
            var executions_conf = {
                stacked: true,
                labelFormat: "¬_D.data.info.title¬",
                showControls: false,
                height: 250,
                color: {
                    'passed-repository-executions': "#0A8931",
                    'failed-repository-executions': "#DB0013"
                }
            };
            var executions = new framework.widgets.MultiBar(executions_dom, executions_metrics,
                    [timeCtx, repoCtx], executions_conf);


            // EXECUTIONS
            var executions_info = [
                {
                    id: 'passed-repository-executions',
                    aggr: 'sum',
                    max: 1
                },
                {
                    id: 'failed-repository-executions',
                    aggr: 'sum',
                    max: 1
                }
            ];
            var display_executions_info = function (event) {
                if (event.event === 'data') {
                    var success = event.data['passed-repository-executions'][Object.keys(event.data['passed-repository-executions'])[0]]['data']['values'][0];
                    var broken = event.data['failed-repository-executions'][Object.keys(event.data['failed-repository-executions'])[0]]['data']['values'][0];
                    var total = broken + success;

                    $("#successNum").text(success);
                    $("#brokenNum").text(broken);
                    $("#execPercent").text(total > 0 ? Math.round(success * 100 / total) : 0);
                    $("#totalNum").text(total);


                }
            };
            framework.data.observe(executions_info, display_executions_info, [timeCtx, repoCtx]);


            // REPOSITORY USERS TABLE
            var table_dom = document.getElementById("users-table");
            var table_metrics = ['view-repository-developers'];
            var table_configuration = {
                columns: [
                    {
                        label: "",
                        link: {
                            img: "avatar", //or icon or label
                            href: "developer",
                            env: [
                                {
                                    property: "userid",
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
                        id: usersTableCtx,
                        filter: [
                            {
                                property: "userid",
                                as: "uid"
                            }
                        ]
                    }
                ],
                selectable: true,
                minRowsSelected: 1,
                maxRowsSelected: 8,
                filterControl: true,
                initialSelectedRows: 5,
                keepSelectedByProperty: "userid",
                orderByColumn: [[1, 'asc']],
                showHeader: false
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [timeCtx, repoCtx], table_configuration);

            // HORIZONTAL CONTRIBUTION TO PROJECTS
            var multibar_projects_dom = document.getElementById("user-commits-horizontal");
            var multibar_projects_metrics = [{
                id: 'repository-member-commits',
                max: 1
            }];
            var multibar_projects_configuration = {
                labelFormat: "¬(_D.data.info.uid != null ? _D.data.info.uid.name : '')¬",
                stacked: true,
                showXAxis: false,
                showControls: false,
                yAxisTicks: 8,
                height: 155,
                total: {
                    id: 'repository-commits',
                    max: 1
                }
            };
            var multibar_projects = new framework.widgets.HorizontalBar(multibar_projects_dom, multibar_projects_metrics,
                    [timeCtx, usersTableCtx, repoCtx], multibar_projects_configuration);

            // COMMITS PER PROJECT AND USER
            var user_project_commits_dom = document.getElementById("user-commits-lines");
            var user_project_commits_metrics = [{
                id: 'repository-member-commits',
                max: 100
            }];
            var user_project_commits_conf = {
                xlabel: '',
                ylabel: 'Commits',
                labelFormat: '¬_D.data.info.uid.name¬',
                interpolate: 'monotone'
            };
            var user_project_commits = new framework.widgets.LinesChart(user_project_commits_dom, user_project_commits_metrics,
                    [timeCtx, usersTableCtx, repoCtx], user_project_commits_conf);

        };
    }

@stop
