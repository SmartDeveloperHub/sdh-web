{{--
    User dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "sdh-framework/framework.widget.counterbox",
    "sdh-framework/framework.widget.horizontalbar",
    "sdh-framework/framework.widget.table",
    "sdh-framework/framework.widget.linesChart",
    "sdh-framework/framework.widget.rangeNv",
    "sdh-framework/framework.widget.radarchart",
    "sdh-framework/framework.widget.multibar",
    "assets/js/widget.languages",
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
            <div id="total-users" class="boxCounter col-sm-4"></div>
            <div id="total-executions" class="boxCounter col-sm-4"></div>
            
        </div>
        <div class="row">
            <div id="solved-issues" class="boxCounter col-sm-4"></div>
            <div id="avg-commits" class="boxCounter col-sm-4"></div>
            <div id="avg-time-to-fix" class="boxCounter col-sm-4"></div>
        </div>
        <div class="row">
            <div class="boxCounter col-sm-2"></div>
            <div id="avg-build-time" class="boxCounter col-sm-4"></div>
            <div id="avg-broken-time" class="boxCounter col-sm-4"></div>
            <div class="boxCounter col-sm-2"></div>
        </div>
    </div>
    <div class="row" id="devActivBox">
        <div class="row titleRow" id="devActivityTitle">
            <span id="devActIco" class="titleIcon titleIcon octicon octicon-dashboard"></span>
            <span class="titleLabel">Activity</span>
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
                            <span class="percentlabel execPercent">0</span>
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
            <span class="titleLabel">Users</span>
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
    //Show header chart and set titles
    setTitle("Repositories");
    showHeaderChart();

    //TODO: improve get env and set env. Return copies instead of the object and allow to get and set only one element.
    var repoCtx = "rid";
    var env = framework.dashboard.getEnv();
    framework.data.updateContext('rid', {rid: env['rid']});
    if(env['name'] != null) {
        setSubtitle(env['name']);
    }

    // light or dark theme?. Default is light
    var lightTheme = true;
    var setRangeChart = null;
    var rangeNv = null;
    var themebutton = $(".headbutton.mail");
    var setLightTheme = function setLightTheme() {
        if (!lightTheme) {
            lightTheme = true;
            rangeNv && rangeNv.delete();
            setRangeChart && setRangeChart();
            themebutton.removeClass("fa-sun-o");
            themebutton.addClass("fa-moon-o");
        }
        $('body').addClass('light');
    };
    var setDarkTheme = function setDarkTheme() {
        if (lightTheme) {
            lightTheme = false;
            rangeNv && rangeNv.delete();
            setRangeChart && setRangeChart();
            themebutton.removeClass("fa-moon-o");
            themebutton.addClass("fa-sun-o");
        }
        $('body').removeClass('light');
    };
    var changeTheme = function changeTheme() {
        if (lightTheme == false) {
            setLightTheme();
        } else {
            setDarkTheme();
        }
    };
    // Change theme
    setLightTheme();

    $(".headbutton.mail").click(changeTheme);

    var context4rangeChart = "context4rangeChart";

    // UPPER SELECTOR RANENV (NEEDS FIRST COMMIT)
    framework.data.observe(['repoinfo'], function(event){
        if(event.event === 'loading') {
            //TODO
        } else if(event.event === 'data') {
            var repoinfo = event.data['repoinfo'][Object.keys(event.data['repoinfo'])[0]]['data'];
            var firstCommit = repoinfo['firstCommit'];

            var rangeNv_dom = document.getElementById("fixed-chart");
            var rangeNv_metrics = [
                {
                    id: 'repocommits',
                    max: 24,
                    aggr: 'avg',
                    from: moment(firstCommit).format("YYYY-MM-DD")
                }
            ];
            var rangeNv_configuration = {
                ownContext: context4rangeChart,
                isArea: true,
                showLegend: false,
                interpolate: 'monotone',
                showFocus: false,
                height: 140,
                duration: 500,
                colors: ["#004C8B"],
                axisColor: "#004C8B"
            };
            if (!lightTheme) {
                rangeNv_configuration['axisColor'] = "#BFE5E3";
                rangeNv_configuration['colors'] = ["#FFC10E"];
            }

            var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [repoCtx], rangeNv_configuration);
            $(rangeNv).on("CONTEXT_UPDATED", function() {
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
            id: 'repocommits',
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
        var total_commits = new framework.widgets.CounterBox(total_commits_dom, total_commits_metrics, [context4rangeChart, repoCtx], total_commits_conf);

        // TOTAL DEVELOPERS
        var total_users_dom = document.getElementById("total-users");
        var total_users_metrics = [{
            id: 'repodevelopers',
            max: 1,
            aggr: 'sum'
        }];
        var total_users_conf = {
            label: 'Total developers',
            decimal: 0,
            icon: 'octicon octicon-organization',
            iconbackground: 'rgb(231, 0, 131)',
            background: 'transparent'
        };
        var total_users = new framework.widgets.CounterBox(total_users_dom, total_users_metrics, [context4rangeChart, repoCtx], total_users_conf);

        // TOTAL EXECUTIONS
        var total_executions_dom = document.getElementById("total-executions");
        var total_executions_metrics = [{
            id: 'repoexecutions',
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
        var total_executions_issues = new framework.widgets.CounterBox(total_executions_dom, total_executions_metrics, [context4rangeChart, repoCtx], total_executions_conf);


        // SUCCESSFUL EXECUTIONS
        var success_executions_dom = document.getElementById("solved-issues");
        var success_executions_metrics = [{
            id: 'repopassedexecutions',
            max: 1,
            aggr: 'sum'
        }];
        var success_executions_conf = {
            label: 'Success build executions',
            decimal: 0,
            icon: 'fa fa-thumbs-up',
            iconbackground: 'rgb(6, 151, 68)',
            background: 'transparent'
        };
        var success_executions_issues = new framework.widgets.CounterBox(success_executions_dom, success_executions_metrics, [context4rangeChart, repoCtx], success_executions_conf);

        // AVG COMMITS
        var avg_commits_dom = document.getElementById("avg-commits");
        var avg_commits_metrics = [{
            id: 'repocommits',
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
        var avg_commits = new framework.widgets.CounterBox(avg_commits_dom, avg_commits_metrics, [context4rangeChart, repoCtx], avg_commits_conf);

        // AVG TIME TO FIX
        var avg_time_to_fix_dom = document.getElementById("avg-time-to-fix");
        var avg_time_to_fix_metrics = [{
            id: 'repotimetofixtbd'
        }];
        var avg_time_to_fix_conf = {
            label: 'Average time to fix',
            decimal: 2,
            icon: 'fa fa-line-chart',
            iconbackground: 'rgb(247, 133, 60)',
            background: 'transparent',
            suffix: " h"
        };
        var avg_time_to_fix = new framework.widgets.CounterBox(avg_time_to_fix_dom, avg_time_to_fix_metrics, [context4rangeChart, repoCtx], avg_time_to_fix_conf);

        // AVG BUILD TIME
        var avg_build_time_dom = document.getElementById("avg-build-time");
        var avg_build_time_metrics = [{
            id: 'repobuildtimetbd'
        }];
        var avg_build_time_conf = {
            label: 'Build execution time',
            decimal: 2,
            icon: 'fa fa-history',
            iconbackground: 'rgb(141, 25, 123)',
            background: 'transparent',
            suffix: " h"
        };
        var avg_build_time = new framework.widgets.CounterBox(avg_build_time_dom, avg_build_time_metrics, [context4rangeChart, repoCtx], avg_build_time_conf);

        // AVG BROKEN TIME
        var avg_broken_time_dom = document.getElementById("avg-broken-time");
        var avg_broken_time_metrics = [{
            id: 'repobrokentimetbd'
        }];
        var avg_broken_time_conf = {
            label: 'Build broken time',
            decimal: 2,
            icon: 'fa fa-history',
            iconbackground: 'rgb(124, 69, 207)',
            background: 'transparent',
            suffix: " d"
        };
        var avg_broken_time = new framework.widgets.CounterBox(avg_broken_time_dom, avg_broken_time_metrics, [context4rangeChart, repoCtx], avg_broken_time_conf);

        // ACTIVITY LINE CHART
        var activity_dom = document.getElementById("activityChart");
        var activity_metrics = [
            {
                id: 'repocommits',
                max: 30
            },
            {
                id: 'repocommits',
                max: 30,
                aggr: "avg"
            }
        ];
        var activity_configuration = {
            xlabel: '',
            ylabel: '',
            interpolate: 'monotone',
            height: 200,
            labelFormat: '%data.info.title%',
            colors: ["#2876B8", "#C0485E"],
            area: true,
            _demo: true // Only for demo
        };
        var activity = new framework.widgets.LinesChart(activity_dom, activity_metrics,
                [context4rangeChart, repoCtx], activity_configuration);

        // CI LINE CHART
        var ci_dom = document.getElementById("execChart");
        var ci_metrics = [
            {
                id: 'repoexecutions',
                max: 30
            },
            {
                id: 'repofailedexecutions',
                max: 30
            },
            {
                id: 'repopassedexecutions',
                max: 30
            }
        ];
        var ci_configuration = {
            xlabel: '',
            ylabel: '',
            interpolate: 'monotone',
            height: 200,
            labelFormat: '%data.info.title%',
            colors: ["#004B8B", "#DB0013", "#0A8931"]
        };
        var ci_lines = new framework.widgets.LinesChart(ci_dom, ci_metrics,
                [context4rangeChart, repoCtx], ci_configuration);


        // REPOSITORY META INFO
        framework.data.observe(['repoinfo'], function(event){
            if(event.event === 'loading') {
                //TODO
            } else if(event.event === 'data') {
                var repoinfo = event.data['repoinfo'][Object.keys(event.data['repoinfo'])[0]]['data'];
                //Set header subtitle
                setSubtitle(repoinfo['name']);

                //Set data
                var creation = document.getElementById('repo-created');
                var rbuildstatus = document.getElementById('repo-buildstatus');
                var rfirstc = document.getElementById('repo-first');
                var rlastc = document.getElementById('repo-last');
                var repostatus = document.getElementById('repo-status');
                creation.innerHTML = moment(new Date(repoinfo['creation'])).format('MMMM Do YYYY');
                rfirstc.innerHTML = moment(new Date(repoinfo['firstCommit'])).format('MMMM Do YYYY');
                rlastc.innerHTML = moment(new Date(repoinfo['lastCommit'])).format('MMMM Do YYYY');
                rbuildstatus.innerHTML = (repoinfo['buildStatus'] ?
                        '<i class="fa fa-thumbs-up" style="color: rgb(104, 184, 40);"></i><span class="passedLabel">(Passed)</span>' :
                        '<i class="fa fa-thumbs-down" style="color: rgb(200, 104, 40);"></i><span class="errorLabel">(Error)</span>');
                repostatus.innerHTML = (repoinfo['public'] ?
                        '<i title="Public" class="fa fa-eye publicIco"></i><span class="publicLabel">(Public)</span>' :
                        '<i title="Private" class="octicon octicon-loc privateIco"></i><span class="privateLabel">(Private)</span>');

                $(creation).removeClass('blurado');
                $(rfirstc).removeClass('blurado');
                $(rlastc).removeClass('blurado');
                $(rbuildstatus).removeClass('blurado');
                $(repostatus).removeClass('blurado');
                $("#avatar").removeClass('octicon octicon-repo');

                if(repoinfo['avatar'] !== undefined && repoinfo['avatar'] !== null && repoinfo['avatar'] !== "" && repoinfo['avatar'] !== "http://avatarURL") {
                    $("#avatar").css("background-image", "url("+repoinfo['avatar']+")");
                } else {
                    $("#avatar").css("background-image", "url(../../assets/images/user-4.png)");
                }
            }
        }, [repoCtx]);

        // EXECUTIONS MULTIBAR
        var executions_dom = document.getElementById("executions-stacked");
        var executions_metrics = [
            {
                id: 'repopassedexecutions',
                max: 30
            },
            {
                id: 'repofailedexecutions',
                max: 30
            }];
        var executions_conf = {
            stacked: true,
            labelFormat: "%data.info.title%",
            showControls: false,
            height: 250,
            color: ["#DB0013", "#0A8931"]
        };
        var executions = new framework.widgets.MultiBar(executions_dom, executions_metrics,
                [context4rangeChart, repoCtx], executions_conf);


        // EXECUTIONS
        var executions_info = [
            {
                id: 'repopassedexecutions',
                aggr: 'sum',
                max: 1
            },
            {
                id: 'repofailedexecutions',
                aggr: 'sum',
                max: 1
            }
        ];
        var display_executions_info = function(event) {
            if(event.event === 'data') {
                var success = event.data['repopassedexecutions'][Object.keys(event.data['repopassedexecutions'])[0]]['data']['values'][0];
                var broken = event.data['repofailedexecutions'][Object.keys(event.data['repofailedexecutions'])[0]]['data']['values'][0];
                var total = broken + success;

                $("#successNum").text(success);
                $("#brokenNum").text(broken);
                $("#execPercent").text((total > 0 ? Math.round(broken*100/total) : 0));
                $("#totalNum").text(total);


            }
        };
        framework.data.observe(executions_info, display_executions_info, [context4rangeChart, repoCtx]);


        // REPOSITORY USERS TABLE
        var usersCtx = "user-table-context";
        var table_dom = document.getElementById("users-table");
        var table_metrics = ['repodeveloperstbd'];
        var table_configuration = {
            columns: [
                {
                    label: "Show",
                    link: {
                        img: "avatar", //or icon or label
                        href: "user-dashboard",
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
                    label: "Name",
                    property: "name"
                }

            ],
            updateContexts: [
                {
                    id: usersCtx,
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
            maxRowsSelected: 6,
            filterControl: true,
            initialSelectedRows: 5,
            keepSelectedByProperty: "userid",
            orderByColumn: [[1, 'asc']]
        };
        var table = new framework.widgets.Table(table_dom, table_metrics, [context4rangeChart, repoCtx], table_configuration);

        // HORIZONTAL CONTRIBUTION TO PROJECTS
        var multibar_projects_dom = document.getElementById("user-commits-horizontal");
        var multibar_projects_metrics = [{
            id: 'repousercommits',
            max: 1
        }];
        var multibar_projects_configuration = {
            labelFormat: "%data.info.uid.name%",
            stacked: true,
            showXAxis: false,
            showControls: false,
            yAxisTicks: 8,
            height: 155,
            total: {
                id: 'repocommits',
                max: 1
            }
        };
        var multibar_projects = new framework.widgets.HorizontalBar(multibar_projects_dom, multibar_projects_metrics,
                [context4rangeChart, usersCtx, repoCtx], multibar_projects_configuration);

        // COMMITS PER PROJECT AND USER
        var user_project_commits_dom = document.getElementById("user-commits-lines");
        var user_project_commits_metrics = [{
            id: 'repousercommits',
            max: 100
        }];
        var user_project_commits_conf = {
            xlabel: '',
            ylabel: 'Commits',
            labelFormat: '%data.info.uid.name%',
            interpolate: 'monotone'
        };
        var user_project_commits = new framework.widgets.LinesChart(user_project_commits_dom, user_project_commits_metrics,
                [context4rangeChart, usersCtx, repoCtx], user_project_commits_conf);

    };


@stop
