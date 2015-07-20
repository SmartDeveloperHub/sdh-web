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
        <div id="total-commits" class="boxCounter col-sm-3"></div>
        <div id="open-issues" class="boxCounter col-sm-3"></div>
        <div id="solved-issues" class="boxCounter col-sm-3"></div>
        <div id="total-projects" class="boxCounter col-sm-3"></div>
    </div>
    <div class="row" id="UserInfoBox">
        <div class="row titleRow" id="userInfoTitle">
            <span id="detailsIco" class="titleIcon fa fa-info-circle"></span>
            <span class="titleLabel">User Details</span>
        </div>
        <div class="row">
            <div class="com-widget widget static-info-widget col-sm-6">
                <div class="col-sm-3">
                    <img id="avatar" class="avatar img-circle" src="" alt=""></image>
                </div>
                <div class="col-sm-9">
                    <div class="row staticInfoLine">
                        <span id="emailIco" class="theicon octicon octicon-mail-read"></span><span class="thelabel">Contact:</span><span class="theVal" id="user-email"></span>
                    </div>
                    <div class="row staticInfoLine">
                        <span id="timeIco" class="theicon octicon octicon-hourglass"></span><span class="thelabel">Register: </span><span class="theVal" id="user-since"></span>
                    </div>
                    <div class="row staticInfoLine">
                        <span id="firstIco" class="theicon octicon octicon-git-branch"></span><span class="thelabel">First Commit:</span><span class="theVal" id="user-first-commit"></span>
                    </div>
                    <div class="row staticInfoLine">
                        <span id="lastIco" class="theicon octicon octicon-git-branch"></span><span class="thelabel">Last Commit:</span><span class="theVal" id="user-last-commit"></span>
                    </div>
                </div>
                    <!--div class="col-sm-8">
                        <label>Web: <span id="user-website"></span></label>
                    </div>
                    <div class="col-sm-5">
                        <label>Skype: <span id="user-skype"></span></label>
                        <label>Linkedin: <span id="user-linkedin"></span></label>
                        <label>Twitter: <span id="user-twitter"></span></label>
                    </div-->
            </div>
            <div class="col-sm-6">
                <div id="commitChart"></div>
            </div>
        </div>
    </div>
    <div class="row" id="UserSkillBox">
        <div class="row titleRow" id="userSkillTitle">
            <span id="skillsIco" class="titleIcon fa fa-heartbeat"></span>
            <span class="titleLabel">Skills</span>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div id="skills-star" class="widget"></div>
            </div>
            <div class="col-sm-7">
                <div id="skills-lines" class="widget"></div>
            </div>
        </div>
    </div>
    <div class="row" id="UserRepoBox">
        <div class="row titleRow" id="userRepoTitle">
            <span id="repoIco" class="titleIcon octicon octicon-repo"></span>
            <span class="titleLabel">Repositories</span>
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
            </div>
            <div class="col-sm-3">
                <div id="projects-table" class="widget"></div>
            </div>
        </div>
    </div>
@stop

@section('script')
/* <script> */
    //Show header chart and set titles
    setTitle("Developers");
    showHeaderChart();

    //TODO: improve get env and set env. Return copies instead of the object and allow to get and set only one element.
    var userCtx = "uid";
    var env = framework.dashboard.getEnv();
    framework.data.updateContext('uid', {uid: (env['uid'] != null ? env['uid'] : USER_ID)}); //TODO: get the USER_ID from the env

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
    framework.data.observe(['userinfo'], function(event){
        if(event.event === 'loading') {
            //TODO
        } else if(event.event === 'data') {
            var userinfo = event.data['userinfo'][Object.keys(event.data['userinfo'])[0]]['data'];
            var firstCommit = userinfo.firstCommit;

            var setRangeChart = function() {
                var rangeNv_dom = document.getElementById("fixed-chart");
                var rangeNv_metrics = [
                    {
                        id: 'usercommits',
                        aggr: 'avg',
                        from: moment(firstCommit).format("YYYY-MM-DD"),
                        max: 101
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
                return new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [userCtx], rangeNv_configuration);
            };

            rangeNv = setRangeChart();

        }
    }, [userCtx]);

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
        background: 'transparent'
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
        background: 'transparent'
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
        background: 'transparent'
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
        background: 'transparent'
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
            /*document.getElementById('user-linkedin').innerHTML = userinfo['linkedin'];
            document.getElementById('user-skype').innerHTML = userinfo['skype'];
            document.getElementById('user-twitter').innerHTML = userinfo['twitter'];
            document.getElementById('user-website').innerHTML = userinfo['website'];*/
            document.getElementById('user-since').innerHTML = moment(new Date(userinfo['register'])).format('MMMM Do YYYY');
            document.getElementById('user-first-commit').innerHTML = moment(new Date(userinfo['firstCommit'])).format('MMMM Do YYYY');
            document.getElementById('user-last-commit').innerHTML = moment(new Date(userinfo['lastCommit'])).format('MMMM Do YYYY');
            if(userinfo['avatar'] !== undefined && userinfo['avatar'] !== null && userinfo['avatar'] !== "" && userinfo['avatar'] !== "http://avatarURL") {
                $("#avatar").attr('src', userinfo['avatar']);
            } else {
                $("#avatar").attr('src', "../../assets/images/user-4.png");
            }        

        }
    }, [userCtx]);


    // USER COMMITS LINE CHART
    var userCC_dom = document.getElementById("commitChart");
    var userCC_metrics = [
        {
            id: 'usercommits',
            max: 25
        }
    ];
    var userCC_configuration = {
        xlabel: '',
        ylabel: '',
        interpolate: 'monotone',
        height: 180,
        labelFormat: 'Commits', //TODO add title in metrics
        colors: ["#2876B8"],
        area: true
    };
    var skills_lines = new framework.widgets.LinesChart(userCC_dom, userCC_metrics,
            [context4rangeChart, userCtx], userCC_configuration);

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
        height: 300,
        labels: ["Speed", "Collaboration", "Quality"],
        fillColor: "rgba(1, 150, 64, 0.4)",
        strokeColor: "#019640",
        pointLabelFontColor: "#2876B8",
        pointLabelFontSize: 12
    };
    var skills_star = new framework.widgets.RadarChart(skills_star_dom, skills_star_metrics,
            [context4rangeChart, userCtx], skills_star_configuration);


    // SKILLS LINES CHART
    var skills_lines_dom = document.getElementById("skills-lines");
    var skills_lines_metrics = [
        {
            id: 'userspeed',
            max: 20,
            aggr: 'avg'
        },
        {
            id: 'usercollaboration',
            max: 20,
            aggr: 'avg'
        },
        {
            id: 'userquality',
            max: 20,
            aggr: 'avg'
        }
    ];
    var skills_lines_configuration = {
        xlabel: '',
        ylabel: '',
        interpolate: 'monotone',
        height: 205,
        labelFormat: '%data.info.id%', //TODO add title in metrics
        colors: ["#FF7F0E", "#1F77B4", "#68B828"]
    };
    var skills_lines = new framework.widgets.LinesChart(skills_lines_dom, skills_lines_metrics,
            [context4rangeChart, userCtx], skills_lines_configuration);


    // USER PROJECTS TABLE
    var repoCtx = "repository-table-context";
    var table_dom = document.getElementById("projects-table");
    var table_metrics = ['userrepositoriestbd'];
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
        minRowsSelected: 1,
        maxRowsSelected: 6,
        filterControl: true,
        initialSelectedRows: 5
    };
    var table = new framework.widgets.Table(table_dom, table_metrics, [context4rangeChart, userCtx], table_configuration);

    // HORIZONTAL CONTRIBUTION TO PROJECTS
    var multibar_projects_dom = document.getElementById("projects-horizontal");
    var multibar_projects_metrics = [{
        id: 'repousercommits',
        max: 1
    }];
    var multibar_projects_configuration = {
        labelFormat: "%data.info.rid.name%",
        stacked: true,
        showXAxis: false,
        showControls: false,
        yAxisTicks: 8,
        height: 155,
        total: {
            id: 'usercommits',
            max: 1,
            aggr: 'sum'
        }
    };
    var multibar_projects = new framework.widgets.HorizontalBar(multibar_projects_dom, multibar_projects_metrics,
            [context4rangeChart, userCtx, repoCtx], multibar_projects_configuration);

    // COMMITS PER PROJECT AND USER
    var user_project_commits_dom = document.getElementById("projects-lines");
    var user_project_commits_metrics = [{
        id: 'repousercommits',
        max: 0
    }];
    var user_project_commits_conf = {
        xlabel: '',
        ylabel: '',
        labelFormat: 'Commits',
        interpolate: 'monotone'
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