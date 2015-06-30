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
    "assets/js/widget.languages",
    "css!assets/css/dashboards/user-dashboard"
    ]
@stop

@section('html')
    <div class="row">
        <div id="total-commits" class="col-sm-3"></div>
        <div id="total-users" class="col-sm-3"></div>
        <div id="total-executions" class="col-sm-3"></div>
        <div id="solved-issues" class="col-sm-3"></div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <div class="com-widget widget static-info-widget">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Name: <span id="name"></span></label>
                        <label>Description: <span id="description"></span></label>
                    </div>
                    <div class="col-sm-6 ">
                        <label>SCM link: <span id="scm-link"></span></label>
                        <label>Status: <span id="repo-status"></span></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="com-widget com-widget widget static-info-widget">
                <label>Last build: <span id="last-build"></span></label>
                <label>Build status: <span id="build-status"></span></label>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="com-widget com-widget widget static-info-widget">
                <label>Created: <span id="since"></span></label>
                <label>First commit: <span id="first-commit"></span></label>
                <label>Last commit: <span id="last-commit"></span></label>
            </div>
        </div>
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
@stop

@section('script')

    context4rangeChart = "context4rangeChart";

    //Set header title
    $("#htitle").text("Repositories");

    //TODO: improve get env and set env. Return copies instead of the object and allow to get and set only one element.
    var repoCtx = "rid";
    framework.data.updateContext('rid', {rid: framework.dashboard.getEnv()['rid']});

    // UPPER SELECTOR RANENV
    var rangeNv_dom = document.getElementById("fixed-chart");
    var rangeNv_metrics = [
        {
            id: 'repositorycommits',
            max: 24,
            aggr: 'avg'
        }
    ];
    var rangeNv_configuration = {
        ownContext: context4rangeChart,
        labelFormat: "Total Commits",
        isArea: true,
        showLegend: false,
        interpolate: 'monotone',
        showFocus: false,
        height : 140,
        duration: 500,
        axisColor: "#BFE5E3",
        background: "rgba(25, 48, 63, 0.92)",
        colors: ["#FFC10E"]
    };
    var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [repoCtx], rangeNv_configuration);


    // TOTAL COMMITS
    var total_commits_dom = document.getElementById("total-commits");
    var total_commits_metrics = [{
        id: 'repositorycommits',
        max: 1,
        aggr: 'sum'
    }];
    var total_commits_conf = {
        label: 'Total commits',
        decimal: 0,
        icon: 'octicon octicon-git-commit',
        iconbackground: 'rgb(40, 118, 184)',
        background: '#EDEDED'
    };
    var total_commits = new framework.widgets.CounterBox(total_commits_dom, total_commits_metrics, [context4rangeChart, repoCtx], total_commits_conf);

    // TOTAL DEVELOPERS
    var total_users_dom = document.getElementById("total-users");
    var total_users_metrics = [{
        id: 'repositorydevelopers',
        max: 1,
        aggr: 'sum'
    }];
    var total_users_conf = {
        label: 'Total developers',
        decimal: 0,
        icon: 'octicon octicon-organization',
        iconbackground: 'rgb(40, 184, 179)',
        background: '#EDEDED'
    };
    var total_users = new framework.widgets.CounterBox(total_users_dom, total_users_metrics, [context4rangeChart, repoCtx], total_users_conf);

    // TOTAL EXECUTIONS
    var total_executions_dom = document.getElementById("total-executions");
    var total_executions_metrics = [{
        id: 'repositoryexec',
        max: 1,
        aggr: 'sum'
    }];
    var total_executions_conf = {
        label: 'Total executions',
        decimal: 0,
        icon: 'fa fa-cogs',
        iconbackground: 'rgb(205, 195, 10)',
        background: '#EDEDED'
    };
    var total_executions_issues = new framework.widgets.CounterBox(total_executions_dom, total_executions_metrics, [context4rangeChart, repoCtx], total_executions_conf);


    // SUCCESSFUL EXECUTIONS
    var success_executions_dom = document.getElementById("solved-issues");
    var success_executions_metrics = [{
        id: 'repositoriesuccessexec',
        max: 1,
        aggr: 'sum'
    }];
    var success_executions_conf = {
        label: 'Successful executions',
        decimal: 0,
        icon: 'octicon octicon-thumbsup',
        iconbackground: 'rgb(104, 184, 40)',
        background: '#EDEDED'
    };
    var success_executions_issues = new framework.widgets.CounterBox(success_executions_dom, success_executions_metrics, [context4rangeChart, repoCtx], success_executions_conf);


    // REPOSITORY META INFO
    framework.data.observe(['repoinfo'], function(event){
        if(event.event === 'loading') {
            //TODO
        } else if(event.event === 'data') {
            var repoinfo = event.data['repoinfo'][Object.keys(event.data['repoinfo'])[0]]['data'];

            //Set header subtitle
            $("#hsubtitle").text(repoinfo['name']);

            //Set data
            document.getElementById('name').innerText = repoinfo['name'];
            document.getElementById('description').innerText = repoinfo['description'];
            document.getElementById('scm-link').innerText = repoinfo['scmlink'];
            document.getElementById('since').innerText = new Date(repoinfo['creation']);
            document.getElementById('first-commit').innerText = new Date(repoinfo['firstcommit']);
            document.getElementById('last-commit').innerText = new Date(repoinfo['lastcommit']);
            document.getElementById('last-build').innerText = new Date(repoinfo['builddate']);
            document.getElementById('build-status').innerHTML = (repoinfo['buildstatus'] ?
                            '<i class="fa fa-thumbs-up" style="color: rgb(104, 184, 40);"></i> (Passed)' :
                            '<i class="fa fa-thumbs-down" style="color: rgb(200, 104, 40);"></i> (Error)');
            document.getElementById('repo-status').innerHTML = (repoinfo['public'] ? '<i title="Public" class="fa fa-eye"></i> (Public)' : '<i title="Private" class="fa fa-eye-slash"></i> (Private)');




        }
    }, [repoCtx]);

    // REPOSITORY USERS TABLE
    var usersCtx = "user-table-context";
    var table_dom = document.getElementById("users-table");
    var table_metrics = ['reporangeduserlist'];
    var table_configuration = {
        columns: [
            {
                label: "Name",
                property: "name"
            },
            {
                label: "Show",
                link: {
                    icon: "fa fa-share", //or label
                    href: "user-dashboard",
                    env: [
                        {
                            property: "userid",
                            as: "uid"
                        }
                    ]
                },
                width: "40px"
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
        maxRowsSelected: 6,
        filterControl: true,
        initialSelectedRows: 5
    };
    var table = new framework.widgets.Table(table_dom, table_metrics, [context4rangeChart, repoCtx], table_configuration);

    // HORIZONTAL CONTRIBUTION TO PROJECTS
    var multibar_projects_dom = document.getElementById("user-commits-horizontal");
    var multibar_projects_metrics = [{
        id: 'userrepositorycommits',
        max: 1
    }];
    var multibar_projects_configuration = {
        labelFormat: "Commits for %data.info.uid.name%",
        stacked: true,
        showXAxis: false,
        showControls: false,
        yAxisTicks: 8,
        total: {
            id: 'repositorycommits',
            max: 1
        }
    };
    var multibar_projects = new framework.widgets.HorizontalBar(multibar_projects_dom, multibar_projects_metrics,
            [context4rangeChart, usersCtx, repoCtx], multibar_projects_configuration);

    // COMMITS PER PROJECT AND USER
    var user_project_commits_dom = document.getElementById("user-commits-lines");
    var user_project_commits_metrics = [{
        id: 'userrepositorycommits',
        max: 0
    }];
    var user_project_commits_conf = {
        xlabel: 'Date',
        ylabel: 'Commits',
        labelFormat: 'Commits for %data.info.uid.name%'
    };
    var user_project_commits = new framework.widgets.LinesChart(user_project_commits_dom, user_project_commits_metrics,
            [context4rangeChart, usersCtx, repoCtx], user_project_commits_conf);

    //LANGUAGES WIDGET
    /*var user_project_languages_dom = document.getElementById("projects-languages");
    var user_project_languages_conf = {
        horiz: {
            stacked: true,
            showControls: false,
            showXAxis: false
        },
        pie: {}
    };
    var user_project_languages = new framework.widgets.Languages(user_project_languages_dom,
            [context4rangeChart, usersCtx, repoCtx], user_project_languages_conf);*/



@stop