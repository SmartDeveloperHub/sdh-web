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
        <div id="open-issues" class="col-sm-3"></div>
        <div id="solved-issues" class="col-sm-3"></div>
        <div id="total-projects" class="col-sm-3"></div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <div class="com-widget widget static-info-widget">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Email: <span id="user-email"></span></label>
                        <label>Web: <span id="user-website"></span></label>
                    </div>
                    <div class="col-sm-6 ">
                        <label>Skype: <span id="user-skype"></span></label>
                        <label>Linkedin: <span id="user-linkedin"></span></label>
                        <label>Twitter: <span id="user-twitter"></span></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="com-widget com-widget widget static-info-widget">
                <label>Favourite language: <span id="user-favourite-lang"></span></label>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="com-widget com-widget widget static-info-widget">
                <label>User since: <span id="user-since"></span></label>
                <label>First commit: <span id="user-first-commit"></span></label>
                <label>Last commit: <span id="user-last-commit"></span></label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <div id="skills-star" class="widget"></div>
        </div>
        <div class="col-sm-7">
            <div id="skills-lines" class="widget"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-12">
                    <div id="projects-horizontal" class="widget"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div id="projects-lines" class="widget"></div>
                </div>
            </div>
            <!--div class="row">
                <div class="col-sm-12">
                    <div id="projects-languages" class="widget"></div>
                </div>
            </div-->
        </div>
        <div class="col-sm-3">
            <div id="projects-table" class="widget"></div>
        </div>
    </div>
@stop

@section('script')

    context4rangeChart = "context4rangeChart";

    //Header Titles
    setTitle("Developers");
    //setSubtitle("Javier Hernandez");

    // light or dark theme?. Default is light
    var lightTheme = true;
    var themebutton = $(".headbutton.mail");
    var setLightTheme = function setLightTheme() {
        if (!lightTheme) {
            lightTheme = true;
            rangeNv.delete();
            setRangeChart();
            themebutton.removeClass("fa-sun-o");
            themebutton.addClass("fa-moon-o");
        }
        $('body').addClass('light');
    };
    var setDarkTheme = function setDarkTheme() {
        if (lightTheme) {
            lightTheme = false;
            rangeNv.delete();
            setRangeChart();
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

    //TODO: improve get env and set env. Return copies instead of the object and allow to get and set only one element.
    var userCtx = "uid";
    var env = framework.dashboard.getEnv();
    framework.data.updateContext('uid', {uid: (env['uid'] != null ? env['uid'] : USER_ID)}); //TODO: get the USER_ID from the env

    var setRangeChart = function() {
        // UPPER SELECTOR RANENV
        var rangeNv_dom = document.getElementById("fixed-chart");
        var rangeNv_metrics = [
            {
                id: 'usercommits',
                max: 24,
                aggr: 'avg'
            }
        ];
        var rangeNv_configuration = {
            ownContext: context4rangeChart,
            isArea: true,
            showLegend: false,
            interpolate: 'monotone',
            showFocus: false,
            height : 140,
            duration: 500,
            colors: ["#004C8B"],
            axisColor: "#004C8B"
        };
        if (!lightTheme) {
            rangeNv_configuration['axisColor'] = "#BFE5E3";
            rangeNv_configuration['colors'] = ["#FFC10E"];
        }
        var _rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [userCtx], rangeNv_configuration);
        return _rangeNv;
    }.bind(this);
    var rangeNv = setRangeChart();

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
        iconbackground: 'rgb(40, 118, 184)',
        background: '#E0E0E0'
    };
    var total_commits = new framework.widgets.CounterBox(total_commits_dom, total_commits_metrics, [context4rangeChart, userCtx], total_commits_conf);


    // OPEN ISSUES
    var open_issues_dom = document.getElementById("open-issues");
    var open_issues_metrics = [{
        id: 'useropenissue',
        max: 1,
        aggr: 'sum'
    }];
    var open_issues_conf = {
        label: 'Open issues',
        decimal: 0,
        icon: 'octicon octicon-issue-opened',
        iconbackground: 'rgb(205, 195, 10)',
        background: '#E0E0E0'
    };
    var open_issues = new framework.widgets.CounterBox(open_issues_dom, open_issues_metrics, [context4rangeChart, userCtx], open_issues_conf);


    // SOLVED ISSUES
    var solved_issues_dom = document.getElementById("solved-issues");
    var solved_issues_metrics = [{
        id: 'usersolvedissue',
        max: 1,
        aggr: 'sum'
    }];
    var solved_issues_conf = {
        label: 'Solved issues',
        decimal: 0,
        icon: 'octicon octicon-issue-closed',
        iconbackground: 'rgb(104, 184, 40)',
        background: '#E0E0E0'
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
        iconbackground: 'rgb(184, 40, 40)',
        background: '#E0E0E0'
    };
    var total_projects = new framework.widgets.CounterBox(total_projects_dom, total_projects_metrics, [context4rangeChart, userCtx], total_projects_conf);


    // USER META INFO
    framework.data.observe(['userinfo'], function(event){
        if(event.event === 'loading') {
            //TODO
        } else if(event.event === 'data') {
            var userinfo = event.data['userinfo'][Object.keys(event.data['userinfo'])[0]]['data'];

            //Set header subtitle
            setSubtitle(userinfo['name']);

            //Set data
            document.getElementById('user-email').innerHTML = userinfo['email'];
            document.getElementById('user-linkedin').innerHTML = userinfo['linkedin'];
            document.getElementById('user-skype').innerHTML = userinfo['skype'];
            document.getElementById('user-twitter').innerHTML = userinfo['twitter'];
            document.getElementById('user-website').innerHTML = userinfo['website'];
            document.getElementById('user-since').innerHTML = moment(new Date(userinfo['register'])).format('LLLL');
            document.getElementById('user-first-commit').innerHTML = moment(new Date(userinfo['firstcommit'])).format('LLLL');
            document.getElementById('user-last-commit').innerHTML = moment(new Date(userinfo['lastcommit'])).format('LLLL');


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

    // SKILLS STAR CHART
    var skills_star_dom = document.getElementById("skills-star");
    var skills_star_metrics = [
        {
            id: 'userspeed',
            max: 1
        },
        {
            id: 'usercollaboration',
            max: 1
        },
        {
            id: 'userquality',
            max: 1
        }];
    var skills_star_configuration = {
        labels: ["Speed", "Collaboration", "Quality"]
    };
    var skills_star = new framework.widgets.RadarChart(skills_star_dom, skills_star_metrics,
            [context4rangeChart, userCtx], skills_star_configuration);

    // SKILLS LINES CHART
    var skills_lines_dom = document.getElementById("skills-lines");
    var skills_lines_metrics = [
        {
            id: 'userspeed',
            max: 0
        },
        {
            id: 'usercollaboration',
            max: 0
        },
        {
            id: 'userquality',
            max: 0
        }];
    var skills_lines_configuration = {
        xlabel: 'Date',
        ylabel: 'Score',
        interpolation: true,
        labelFormat: '%data.info.description%' //TODO
    };
    var skills_lines = new framework.widgets.LinesChart(skills_lines_dom, skills_lines_metrics,
            [context4rangeChart, userCtx], skills_lines_configuration);


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
                    icon: "fa fa-share", //or label
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
        id: 'userrepositorycommits',
        max: 1
    }];
    var multibar_projects_configuration = {
        labelFormat: "Commits for %data.info.rid.name%",
        stacked: true,
        showXAxis: false,
        showControls: false,
        yAxisTicks: 8,
        total: {
            id: 'usercommits',
            max: 1
        }
    };
    var multibar_projects = new framework.widgets.HorizontalBar(multibar_projects_dom, multibar_projects_metrics,
            [context4rangeChart, userCtx, repoCtx], multibar_projects_configuration);

    // COMMITS PER PROJECT AND USER
    var user_project_commits_dom = document.getElementById("projects-lines");
    var user_project_commits_metrics = [{
        id: 'userrepositorycommits',
        max: 0
    }];
    var user_project_commits_conf = {
        xlabel: 'Date',
        ylabel: 'Commits',
        labelFormat: 'Commits for %data.info.rid.name%'
    };
    var user_project_commits = new framework.widgets.LinesChart(user_project_commits_dom, user_project_commits_metrics,
            [context4rangeChart, userCtx, repoCtx], user_project_commits_conf);

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
            [context4rangeChart, userCtx, repoCtx], user_project_languages_conf);*/



@stop