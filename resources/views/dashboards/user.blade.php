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
    "sdh-framework/framework.widget.rangeNv"
    ]
@stop

@section('html')
    <div class="row">
        <div id="total-commits" class="col-sm-3"></div>
        <div id="open-issues" class="col-sm-3"></div>
        <div id="solved-issues" class="col-sm-3"></div>
        <div id="total-projects" class="col-sm-3"></div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <div class="row">
                <div class="col-sm-6">
                    <div>Email: <span id="user-email"></span></div>
                    <div>Web: <span id="user-website"></span></div>
                </div>
                <div class="col-sm-6">
                    <div>Skype: <span id="user-skype"></span></div>
                    <div>Linkedin: <span id="user-linkedin"></span></div>
                    <div>Twitter: <span id="user-twitter"></span></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div>Favourite language: <span id="user-favourite-lang"></span></div>
        </div>
        <div class="col-sm-4">
            <div>User since: <span id="user-since"></span></div>
            <div>First commit: <span id="user-first-commit"></span></div>
            <div>Last commit: <span id="user-last-commit"></span></div>
        </div>
    </div>
    <div class="row">
        <div id="skills-star" class="col-sm-5"></div>
        <div id="skills-lines" class="col-sm-7"></div>
    </div>
    <div class="row">
        <div class="col-sm-9">
            <div class="row">
                <div id="projects-horizontal" class="col"></div>
            </div>
            <div class="row">
                <div id="projects-lines" class="col"></div>
            </div>
            <div class="row">
                <div id="projects-languages" class="col"></div>
            </div>
        </div>
        <div id="projects-table" class="col-sm-3"></div>
    </div>
@stop

@section('script')

    context4rangeChart = "context4rangeChart";

    //TODO: improve get env and set env. Return copies instead of the object and allow to get and set only one element.
    var userCtx = "uid";
    framework.data.updateContext('uid', {uid: 'u1'}/*framework.dashboard.getEnv()['uid']*/);

    // UPPER SELECTOR RANENV
    var rangeNv_dom = document.getElementById("fixed-chart");
    var rangeNv_metrics = [
        {
            id: 'usercommits',
            uid: 'u1', //TODO
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
    var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, null, rangeNv_configuration);


    // TOTAL COMMITS
    var total_commits_dom = document.getElementById("total-commits");
    var total_commits_metrics = [{
        id: 'usercommits',
        max: 1,
        aggr: 'sum'
    }];
    var total_commits_conf = {
        label: 'Total commits',
        decimal: 0,
        icon: 'octicon octicon-git-commit',
        iconbackground: 'rgb(40, 118, 184)'
    };
    var total_commits = new framework.widgets.CounterBox(total_commits_dom, total_commits_metrics, [context4rangeChart, userCtx], total_commits_conf);


    // OPEN ISSUES
    var open_issues_dom = document.getElementById("open-issues");
    var open_issues_metrics = [{
        id: 'usercommits', //TODO: not implemented in API
        max: 1,
        aggr: 'sum'
    }];
    var open_issues_conf = {
        label: 'Open issues',
        decimal: 0,
        icon: 'octicon octicon-issue-opened',
        iconbackground: 'rgb(205, 195, 10)'
    };
    var open_issues = new framework.widgets.CounterBox(open_issues_dom, open_issues_metrics, [context4rangeChart, userCtx], open_issues_conf);


    // SOLVED ISSUES
    var solved_issues_dom = document.getElementById("solved-issues");
    var solved_issues_metrics = [{
        id: 'usercommits', //TODO: not implemented in API
        max: 1,
        aggr: 'sum'
    }];
    var solved_issues_conf = {
        label: 'Solved issues',
        decimal: 0,
        icon: 'octicon octicon-issue-closed',
        iconbackground: 'rgb(104, 184, 40)'
    };
    var solved_issues = new framework.widgets.CounterBox(solved_issues_dom, solved_issues_metrics, [context4rangeChart, userCtx], solved_issues_conf);


    // TOTAL PROJECTS
    var total_projects_dom = document.getElementById("total-projects");
    var total_projects_metrics = [{
        id: 'userrepositories',
        max: 1,
        aggr: 'sum'
    }];
    var total_projects_conf = {
        label: 'Total projects',
        decimal: 0,
        icon: 'octicon octicon-repo',
        iconbackground: 'rgb(184, 40, 40)'
    };
    var total_projects = new framework.widgets.CounterBox(total_projects_dom, total_projects_metrics, [context4rangeChart, userCtx], total_projects_conf);


    // USER META INFO
    framework.data.observe(['userinfo'], function(event){
        if(event.event === 'loading') {
            //TODO
        } else if(event.event === 'data') {
            var userinfo = event.data['userinfo'][0];

            //Set data
            document.getElementById('user-email').innerText = userinfo['email'];
            document.getElementById('user-linkedin').innerText = userinfo['linkedin'];
            document.getElementById('user-skype').innerText = userinfo['skype'];
            document.getElementById('user-twitter').innerText = userinfo['twitter'];
            document.getElementById('user-website').innerText = userinfo['website'];

        }
    }, [userCtx]);

    //TEST PIECHART
    /*var piechart_dom = document.getElementById("piechart");
    var piechart_metrics = [
        {
            id: 'usercommits',
            uid: 'u1',
            max: 1,
            aggr: 'avg'
        },
        {
            id: 'usercommits',
            uid: 'u2',
            max: 1,
            aggr: 'avg'
        },
        {
            id: 'usercommits',
            uid: 'u3',
            max: 1,
            aggr: 'avg'
        },
    ];
    var piechart_configuration = {
        labelFormat: "User: %uid%"
    };
    var piechart = new framework.widgets.PieChart(piechart_dom, piechart_metrics, [context4rangeChart], piechart_configuration);
    */

    // USER PROJECTS TABLE
    var repoCtx = "repository-table-context";
    var table_dom = document.getElementById("projects-table");
    var table_metrics = ['userrangedrepolist'];
    var table_configuration = {
        columns: [
            {
                label: "Name",
                property: "name"
            },
            {
                label: "Show",
                link: {
                    icon: "fa fa-share-square-o", //or label
                    href: "repo-dashboard",
                    env: [
                        {
                            property: "repositoryid",
                            as: "rid"
                        }
                    ]
                },
                width: "40px"
            }
        ],
        updateContexts: [
            {
                id: repoCtx,
                filter: [
                    {
                        property: "repositoryid",
                        as: "rid"
                    }
                ]
            }
        ],
        selectable: true,
        maxRowsSelected: 6,
        filterControl: true,
        initialSelectedRows: 5
    };

    var table = new framework.widgets.Table(table_dom, table_metrics, [context4rangeChart, userCtx], table_configuration);

    // HORIZONTAL CONTRIBUTION TO PROJECTS
    var multibar_projects_dom = document.getElementById("projects-horizontal");
    var multibar_projects_metrics = [{
        id: 'usercommits',
        max: 1
    }];
    var multibar_projects_configuration = {
        labelFormat: "Commits for %rid%",
        stacked: true,
        showXAxis: false,
        showControls: false,
        yAxisTicks: 8
    };
    var multibar_projects = new framework.widgets.HorizontalBar(multibar_projects_dom, multibar_projects_metrics,
            [context4rangeChart, userCtx, repoCtx], multibar_projects_configuration);

    // COMMITS PER PROJECT AND USER
    var user_project_commits_dom = document.getElementById("projects-lines");
    var user_project_commits_metrics = [{
        id: 'usercommits',
        max: 0
    }];
    var user_project_commits_conf = {
        xlabel: 'Date',
        ylabel: 'Commits',
        labelFormat: 'Commits %rid%'
    };
    var user_project_commits = new framework.widgets.LinesChart(user_project_commits_dom, user_project_commits_metrics,
            [context4rangeChart, userCtx, repoCtx], user_project_commits_conf);


@stop