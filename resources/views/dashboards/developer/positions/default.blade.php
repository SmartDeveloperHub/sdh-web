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
    "css!assets/css/dashboards/developer-dashboard"
    ]
@stop

@section('html')
    <div class="row" id="UserInfoBox">
        <div class="row">
            <div class="com-widget widget static-info-widget col-sm-12">
                <div class="col-sm-2 avatarBox">
                    <div id="avatar" class="avatar img-circle fa-user-secret"></div>
                </div>
                <div class="col-sm-5">
                    <div class="row leftStaticInfoLine">
                        <span id="emailIco" class="theicon octicon octicon-mail-read"></span><span class="thelabel">Contact:</span><span class="theVal blurado" id="user-email">email@emaildom.com</span>
                    </div>
                    <div class="row leftStaticInfoLine">
                        <span id="timeIco" class="theicon fa fa-pencil-square-o"></span><span class="thelabel">Registered:</span><span class="theVal blurado" id="user-since">July 3rd 2012</span>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="row staticInfoLine">
                        <span id="firstIco" class="theicon octicon octicon-git-branch"></span><span class="thelabel">First Commit:</span><span class="theVal blurado" id="user-first-commit">July 3rd 2012</span>
                    </div>
                    <div class="row staticInfoLine">
                        <span id="lastIco" class="theicon octicon octicon-git-branch"></span><span class="thelabel">Last Commit:</span><span class="theVal blurado" id="user-last-commit">Mar 5rd 2015</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="widgetsRow">
        <div id="total-commits" class="boxCounter col-sm-3"></div>
        <div id="avg-commitsday" class="boxCounter col-sm-3"></div>
        <div id="longest-streak" class="boxCounter col-sm-3"></div>
        <div id="total-repositories" class="boxCounter col-sm-3"></div>
    </div>
    <div class="row" id="devActivBox">
        <div class="row titleRow" id="devActivityTitle">
            <span id="devActIco" class="titleIcon titleIcon octicon octicon-dashboard"></span>
            <span class="titleLabel">Activity</span>
        </div>
        <div class="row" id="commits-lines"></div>
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
                        <div id="repositories-commits-horizontal" class="widget"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div id="repositories-commits-lines" class="widget"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div id="repositories-table" class="widget"></div>
            </div>
        </div>
    </div>
@stop

@section('script')
/* <script> */
    function _() {
        //Contexts used in this dashboard
        var userCtx = "user-context";
        var timeCtx = "time-context";
        var reposTableCtx = "repository-table-context";

        //Show header chart and set titles
        setTitle("Developers");
        showHeaderChart();

        //TODO: improve get env and set env. Return copies instead of the object and allow to get and set only one element.
        var env = framework.dashboard.getEnv();
        framework.data.updateContext(userCtx, {uid: (env['uid'] != null ? env['uid'] : USER_ID)}); //TODO: get the USER_ID from the env
        if (env['name'] != null) {
            setSubtitle(env['name']);
        }

        // UPPER SELECTOR RANENV (NEEDS FIRST COMMIT)
        framework.data.observe(['userinfo'], function (event) {

            if (event.event === 'data') {
                var userinfo = event.data['userinfo'][Object.keys(event.data['userinfo'])[0]]['data'];
                var firstCommit = userinfo['firstCommit'];

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

                var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, [userCtx], rangeNv_configuration);

                // Wait for the event of context updated to load the rest of the widgets
                $(rangeNv).on("CONTEXT_UPDATED", function () {
                    $(rangeNv).off("CONTEXT_UPDATED");
                    loadTimeDependentWidgets();

                    // Hide the loading animation
                    finishLoading();
                });

            }

        }, [userCtx]);

        var loadTimeDependentWidgets = function loadTimeDependentWidgets() {

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
                iconbackground: 'rgb(0, 75, 139)',
                background: 'transparent'
            };
            var total_commits = new framework.widgets.CounterBox(total_commits_dom, total_commits_metrics, [timeCtx, userCtx], total_commits_conf);

            // AVG COMMITS PER DAY
            var avg_commits_dom = document.getElementById("avg-commitsday");
            var avg_commits_metrics = [{
                id: 'usercommits',
                max: 1,
                aggr: 'avg'
            }];
            var avg_commits_conf = {
                label: 'Average commits per day',
                decimal: 3,
                icon: 'octicon octicon-git-merge',
                iconbackground: 'rgb(192, 72, 94)',
                background: 'transparent'
            };
            var total_commits = new framework.widgets.CounterBox(avg_commits_dom, avg_commits_metrics, [timeCtx, userCtx], avg_commits_conf);

            // LONGEST STREAK
            var streak_dom = document.getElementById("longest-streak");
            var streak_metrics = [{
                id: 'userstreak',
                max: 1,
                aggr: 'sum'
            }];
            var streak_conf = {
                label: 'Longest streak',
                decimal: 0,
                icon: 'octicon octicon-flame',
                iconbackground: 'rgb(247, 83, 51)',
                background: 'transparent',
                suffix: " days"
            };
            var streak = new framework.widgets.CounterBox(streak_dom, streak_metrics, [timeCtx, userCtx], streak_conf);

            // TOTAL PROJECTS
            var total_projects_dom = document.getElementById("total-repositories");
            var total_projects_metrics = [{
                id: 'userrepositories',
                max: 1,
                aggr: 'sum'
            }];
            var total_projects_conf = {
                label: 'Total repositories',
                decimal: 0,
                icon: 'octicon octicon-repo',
                iconbackground: 'rgb(159, 206, 35)',
                background: 'transparent'
            };
            var total_projects = new framework.widgets.CounterBox(total_projects_dom, total_projects_metrics, [timeCtx, userCtx], total_projects_conf);


            // USER META INFO
            framework.data.observe(['userinfo'], function (event) {
                if (event.event === 'data') {
                    var userinfo = event.data['userinfo'][Object.keys(event.data['userinfo'])[0]]['data'];
                    //Set header subtitle
                    setSubtitle(userinfo['name']);

                    //Set data
                    var uemail = document.getElementById('user-email');
                    var usince = document.getElementById('user-since');
                    var ufirstc = document.getElementById('user-first-commit');
                    var ulastc = document.getElementById('user-last-commit');

                    uemail.innerHTML = userinfo['email'];
                    usince.innerHTML = moment(new Date(userinfo['register'])).format('MMMM Do YYYY');
                    ufirstc.innerHTML = moment(new Date(userinfo['firstCommit'])).format('MMMM Do YYYY');
                    ulastc.innerHTML = moment(new Date(userinfo['lastCommit'])).format('MMMM Do YYYY');

                    $(uemail).removeClass('blurado');
                    $(usince).removeClass('blurado');
                    $(ufirstc).removeClass('blurado');
                    $(ulastc).removeClass('blurado');
                    $("#avatar").removeClass('fa-user-secret');

                    if (userinfo['avatar'] !== undefined && userinfo['avatar'] !== null && userinfo['avatar'] !== "" && userinfo['avatar'] !== "http://avatarURL") {
                        $("#avatar").css("background-image", "url(" + userinfo['avatar'] + ")");
                    } else {
                        $("#avatar").css("background-image", "url(../../assets/images/user-4.png)");
                    }

                }
            }, [userCtx]);


            // USER COMMITS LINE CHART
            var userCC_dom = document.getElementById("commits-lines");
            var userCC_metrics = [
                {
                    id: 'usercommits',
                    max: 30
                },
                {
                    id: 'usercommits',
                    max: 30,
                    aggr: "avg"
                }
            ];
            var userCC_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: '¬_D.data.info.title¬',
                colors: ["#2876B8", "#C0485E"],
                area: true,
                _demo: true // Only for demo
            };
            var skills_lines = new framework.widgets.LinesChart(userCC_dom, userCC_metrics,
                    [timeCtx, userCtx], userCC_configuration);

            // SKILLS STAR CHART
            var skills_star_dom = document.getElementById("skills-star");
            var skills_star_metrics = [
                {
                    id: 'userspeed',
                    max: 1,
                    aggr: "sum"
                },
                {
                    id: 'usercollaboration',
                    max: 1,
                    aggr: "sum"
                },
                {
                    id: 'userquality',
                    max: 1,
                    aggr: "sum"
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
                    [timeCtx, userCtx], skills_star_configuration);


            // SKILLS LINES CHART
            var skills_lines_dom = document.getElementById("skills-lines");
            var skills_lines_metrics = [
                {
                    id: 'userspeed',
                    max: 20,
                    aggr: 'sum'
                },
                {
                    id: 'usercollaboration',
                    max: 20,
                    aggr: 'sum'
                },
                {
                    id: 'userquality',
                    max: 20,
                    aggr: 'sum'
                }
            ];
            var skills_lines_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 205,
                labelFormat: '¬_D.data.info.title¬',
                colors: ["#FF7F0E", "#1F77B4", "#68B828"]
            };
            var skills_lines = new framework.widgets.LinesChart(skills_lines_dom, skills_lines_metrics,
                    [timeCtx, userCtx], skills_lines_configuration);


            // USER REPOSITORIES TABLE
            var table_dom = document.getElementById("repositories-table");
            var table_metrics = ['userrepositoriestbd'];
            var table_configuration = {
                columns: [
                    {
                        label: "",
                        link: {
                            img: "avatar", //or label
                            href: "repository",
                            env: [
                                {
                                    property: "repositoryid",
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
                        id: reposTableCtx,
                        filter: [
                            {
                                property: "repositoryid",
                                as: "rid"
                            }
                        ]
                    }
                ],
                keepSelectedByProperty: "repositoryid",
                selectable: true,
                minRowsSelected: 1,
                maxRowsSelected: 6,
                filterControl: true,
                initialSelectedRows: 5,
                showHeader: false
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [timeCtx, userCtx], table_configuration);

            // HORIZONTAL CONTRIBUTION TO REPOSITORIES
            var multibar_repositories_dom = document.getElementById("repositories-commits-horizontal");
            var multibar_repositories_metrics = [{
                id: 'repousercommits',
                max: 1
            }];
            var multibar_repositories_configuration = {
                labelFormat: "¬(_D.data.info.rid != null ? _D.data.info.rid.name : '')¬",
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
            var multibar_projects = new framework.widgets.HorizontalBar(multibar_repositories_dom, multibar_repositories_metrics,
                    [timeCtx, userCtx, reposTableCtx], multibar_repositories_configuration);

            // COMMITS PER REPOSITORY AND USER
            var user_repositories_commits_dom = document.getElementById("repositories-commits-lines");
            var user_repositories_commits_metrics = [{
                id: 'repousercommits',
                max: 100
            }];
            var user_repositories_commits_conf = {
                xlabel: '',
                ylabel: '',
                labelFormat: "¬_D.data.info.rid.name¬",
                interpolate: 'monotone',
                area: true
            };
            var user_project_commits = new framework.widgets.LinesChart(user_repositories_commits_dom, user_repositories_commits_metrics,
                    [timeCtx, userCtx, reposTableCtx], user_repositories_commits_conf);


        };
    }

@stop
