{{--
    User dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "sdh-framework/widgets/CounterBox/counterbox",
    "sdh-framework/widgets/HorizontalBar/horizontalbar",
    "sdh-framework/widgets/Table/table",
    "sdh-framework/widgets/LinesChart/linesChart",
    "sdh-framework/widgets/RangeNv/rangeNv",
    "sdh-framework/widgets/RadarChart/radarchart",
    "sdh-framework/widgets/MultiBar/multiBar",
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
    <div id="widgetsRow">
        <div class="row">
            <div id="total-commits" class="boxCounter col-sm-3"></div>
            <div id="avg-commitsday" class="boxCounter col-sm-3"></div>
            <div id="longest-streak" class="boxCounter col-sm-3"></div>
            <div id="total-repositories" class="boxCounter col-sm-3"></div>
        </div>
        <div class="row">
            <div id="issues-open" class="boxCounter col-sm-2  col-md-offset-1"></div>
            <div id="issues-in-progress" class="boxCounter col-sm-2"></div>
            <div id="issues-close" class="boxCounter col-sm-2"></div>
            <div id="issues-active" class="boxCounter col-sm-2"></div>
            <div id="issues-reopened" class="boxCounter col-sm-2"></div>
        </div>
    </div>
    <div class="row" id="devActivBox">
        <div class="row titleRow top-separator" id="devActivityTitle">
            <span id="devActIco" class="titleIcon octicon octicon-dashboard"></span>
            <span class="titleLabel">Activity</span>
        </div>
        <div class="row" id="commits-lines"></div>
    </div>
    <div class="row" id="devActivBox">
        <div class="row titleRow top-separator">
            <span id="devActIco" class="titleIcon fa fa-tasks" style="color: #ee8433"></span>
            <span class="titleLabel">Issue Tracking</span>
        </div>
        <div class="row" id="workload-lines"></div>
        <div class="row" id="issues-multibar"></div>
        <div class="row">
            <div id="developer-pie-status" class="col-sm-5 col-md-offset-1"></div>
            <div id="developer-pie-severity" class="col-sm-5"></div>
        </div>

    </div>
    <div class="row top-separator" id="UserSkillBox">
        <div class="row titleRow" id="userSkillTitle">
            <span id="skillsIco" class="titleIcon fa fa-heartbeat"></span>
            <span class="titleLabel">Skills</span>
        </div>
        <div class="row skillsSection">
            <div class="col-sm-4 starDiv">
                <div id="skills-star" class="widget"></div>
            </div>
            <div class="col-sm-8 skillLineDiv">
                <div id="skills-lines" class="widget"></div>
            </div>
        </div>
    </div>
    <div class="row top-separator" id="UserRepoBox">
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

        // UPPER SELECTOR RANENV
        var rangeNv_dom = document.getElementById("fixed-chart");
        var rangeNv_metrics = [
            {
                id: 'member-activity',
                aggr: 'sum',
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
                ufirstc.innerHTML = (userinfo['firstcommit'] != 0 ? moment(new Date(userinfo['firstcommit'])).format('MMMM Do YYYY') : 'Never');
                ulastc.innerHTML = (userinfo['lastcommit'] != 0 ? moment(new Date(userinfo['lastcommit'])).format('MMMM Do YYYY') : 'Never');

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

        var loadTimeDependentWidgets = function loadTimeDependentWidgets() {

            var toPercentagePostModifier = function toPercentagePostModifier(resourceData) {

                var values = resourceData['data']['values'];
                for(var x = 0; x < values.length; x++) {
                    values[x] = Math.round(values[x] * 100);
                }

                return resourceData;

            };

            // TOTAL COMMITS
            var total_commits_dom = document.getElementById("total-commits");
            var total_commits_metrics = [{
                id: 'member-commits',
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
                id: 'member-commits',
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
                id: 'member-longest-streak',
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

            var memberReposPostMod = function memberReposPostMod(resourceData) {
                resourceData['data']['values'] = [resourceData['data']['values'].length];
                return resourceData;

            };
            // TOTAL PROJECTS
            var total_projects_dom = document.getElementById("total-repositories");
            var total_projects_metrics = [{
                id: 'view-member-repositories',
                post_modifier: memberReposPostMod
            }];
            var total_projects_conf = {
                label: 'Total repositories',
                decimal: 0,
                icon: 'octicon octicon-repo',
                iconbackground: 'rgb(159, 206, 35)',
                background: 'transparent'
            };
            var total_projects = new framework.widgets.CounterBox(total_projects_dom, total_projects_metrics, [timeCtx, userCtx], total_projects_conf);

            // ISSUES OPEN
            var issues_open_dom = document.getElementById("issues-open");
            var issues_open_metrics = [{
                id: 'member-longest-streak' //TODO: fill with real metric
            }];
            var issues_open_conf = {
                label: 'Issues opened',
                decimal: 0,
                icon: 'octicon octicon-issue-opened',
                iconbackground: '#88B5DA',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(issues_open_dom, issues_open_metrics, [timeCtx, userCtx], issues_open_conf);

            // ISSUES OPEN
            var issues_in_progress_dom = document.getElementById("issues-in-progress");
            var issues_in_progress_metrics = [{
                id: 'member-longest-streak' //TODO: fill with real metric
            }];
            var issues_in_progress_conf = {
                label: 'Issues in progress',
                decimal: 0,
                icon: 'octicon octicon-issue-opened',
                iconbackground: '#21B660',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(issues_in_progress_dom, issues_in_progress_metrics, [timeCtx, userCtx], issues_in_progress_conf);

            // ISSUES OPEN
            var issues_close_dom = document.getElementById("issues-close");
            var issues_close_metrics = [{
                id: 'member-longest-streak' //TODO: fill with real metric
            }];
            var issues_close_conf = {
                label: 'Issues closed',
                decimal: 0,
                icon: 'octicon octicon-issue-opened',
                iconbackground: '#AA3998',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(issues_close_dom, issues_close_metrics, [timeCtx, userCtx], issues_close_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_1_dom = document.getElementById("issues-active");
            var counter_1_metrics = [{
                id: 'member-commits', //TODO: ​member-product-active-issues
                max: 1
            }];
            var counter_1_conf = {
                label: 'Active issues',
                decimal: 0,
                icon: 'fa fa-refresh',
                iconbackground: '#EE7529',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_1_dom, counter_1_metrics, [timeCtx, userCtx], counter_1_conf);

            // --------------------------------------- COMMITS COUNTER --------------------------------------------
            var counter_2_dom = document.getElementById("issues-reopened");
            var counter_2_metrics = [{
                id: 'member-commits', //TODO: member-product-active-reopened-issues
                max: 1
            }];
            var counter_2_conf = {
                label: 'Active reopened issues',
                decimal: 0,
                icon: 'fa fa-retweet',
                iconbackground: '#F75333',
                background: 'transparent'
            };
            new framework.widgets.CounterBox(counter_2_dom, counter_2_metrics, [timeCtx, userCtx], counter_2_conf);




            // USER COMMITS LINE CHART
            var userCC_dom = document.getElementById("commits-lines");
            var userCC_metrics = [
                {
                    id: 'member-commits',
                    max: 30
                },
                {
                    id: 'member-commits',
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
            new framework.widgets.LinesChart(userCC_dom, userCC_metrics,
                    [timeCtx, userCtx], userCC_configuration);


            // WORKLOAD LINES
            var changeScalePostModifier = function toPercentagePostModifier(resourceData) {

                // Data will be [0, 200] aprox, but we want 100 to be the y axis origin. Therefore, we change it to a
                // [-100, 100] so that 100 will be 0 in our new scale, and then modify the yAxisTickFormat function of the
                // widget to restore it to the [0,200] by adding 100
                var scale = d3.scale.linear().domain([0, 200]).range([-100, 100]);

                var values = resourceData['data']['values'];
                for(var x = 0; x < values.length; x++) {
                    values[x] = Math.random() * 200; //TODO: Remove: Just to generate random numbers until the metric is ready
                    values[x] = scale(values[x]);
                }
                //debugger;
                return resourceData;

            };
            var workload_dom = document.getElementById("workload-lines");
            var workload_metrics = [
                {
                    id: 'member-commits', //TODO: change to the real workload metric
                    max: 30,
                    post_modifier: changeScalePostModifier
                }
            ];
            var workload_configuration = {
                xlabel: '',
                ylabel: '',
                interpolate: 'monotone',
                height: 200,
                labelFormat: 'Workload',
                colors: ["#2876B8"],
                area: true,
                yAxisTickFormat : function(d) {  return Math.round(d + 100); },
            };
            new framework.widgets.LinesChart(workload_dom, workload_metrics,
                    [timeCtx, userCtx], workload_configuration);


            // -------------------------- ISSUES MULTIBAR ------------------------------------
            var prod_member_issues_multibar_dom = document.getElementById("issues-multibar");
            var prod_member_issues_multibar_metrics = [];
            var categories = ['Blocked', 'Critical', 'Grave', 'Normal', 'Trivial'];
            var statuses = ['Other Open', 'Other In Progress', 'Task Open', 'Task In progress', 'Bug Open', 'Bug In progress' ];
            var colors = ['#ffbb78', '#ff7f0e', '#aec7e8', '#1f77b4', '#ff9896', '#d62728' ];
            var category_1 = {};
            var status_1 = {};
            var color_1 = {};
            for(var f = 0; f < 30; f++) {
                var metricName = 'member-issues-breakdown-' + f;
                prod_member_issues_multibar_metrics.push({
                    id: metricName, //product-member-...
                    max: 1
                });
                category_1[metricName] = categories[f % categories.length];
                status_1[metricName] = statuses[Math.floor(f / categories.length) % statuses.length];
                color_1[metricName] = colors[f % colors.length];
            }

            var prod_member_issues_multibar_conf = {
                stacked: true,
                color: color_1,
                labelFormat: function(metric, extra) {
                    return status_1[extra.resource];
                },
                showControls: false,
                height: 200,
                showLegend: true,
                x: function(metric, extra) {
                    return category_1[extra.resource];
                }
            };
            new framework.widgets.MultiBar(prod_member_issues_multibar_dom, prod_member_issues_multibar_metrics,
                    [timeCtx, userCtx], prod_member_issues_multibar_conf);


            // ------------------------------- ISSUES STATUS PIE -------------------------------------
            var developer_status_pie_dom = document.getElementById("developer-pie-status");
            var developer_status_pie_metrics = [
                {
                    id: 'product-developers', //TODO: member-product opened issues
                    max: 1,
                    aggr: "sum",
                    prid: 'product-jenkins' //TODO: remove
                },
                {
                    id: 'product-developers', //TODO: member-product in-progress issues
                    max: 1,
                    aggr: "sum",
                    prid: 'product-jenkins' //TODO: remove
                },
                {
                    id: 'product-developers', //TODO: member-product closed issues
                    max: 1,
                    aggr: "sum",
                    prid: 'product-jenkins' //TODO: remove
                }
            ];
            var developer_status_pie_configuration = {
                height: 320,
                showLegend: true,
                showLabels: false,
                labelFormat: "¬_D.data.info.title¬",
                maxDecimals: 0
            };
            new framework.widgets.PieChart(developer_status_pie_dom, developer_status_pie_metrics,
                    [timeCtx, userCtx], developer_status_pie_configuration);


            // ------------------------------- ISSUES SEVERITY PIE -------------------------------------
            var developer_severity_pie_dom = document.getElementById("developer-pie-severity");
            var developer_severity_pie_metrics = [
                {
                    id: 'product-developers', //TODO: member-product trivial issues
                    max: 1,
                    aggr: "sum",
                    prid: 'product-jenkins' //TODO: remove
                },
                {
                    id: 'product-developers', //TODO: member-product normal issues
                    max: 1,
                    aggr: "sum",
                    prid: 'product-jenkins' //TODO: remove
                },
                {
                    id: 'product-developers', //TODO: member-product high issues
                    max: 1,
                    aggr: "sum",
                    prid: 'product-jenkins' //TODO: remove
                },
                {
                    id: 'product-developers', //TODO: member-product critical issues
                    max: 1,
                    aggr: "sum",
                    prid: 'product-jenkins' //TODO: remove
                },
                {
                    id: 'product-developers', //TODO: member-product blocker issues
                    max: 1,
                    aggr: "sum",
                    prid: 'product-jenkins' //TODO: remove
                }
            ];
            var developer_severity_pie_configuration = {
                height: 320,
                showLegend: true,
                showLabels: false,
                labelFormat: "¬_D.data.info.title¬",
                maxDecimals: 0
            };
            new framework.widgets.PieChart(developer_severity_pie_dom, developer_severity_pie_metrics,
                    [timeCtx, userCtx], developer_severity_pie_configuration);


            // SKILLS STAR CHART
            var skills_star_dom = document.getElementById("skills-star");
            var skills_star_metrics = [
                {
                    id: 'member-speed-fake',
                    max: 1,
                    aggr: "avg",
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'member-collaboration-fake',
                    max: 1,
                    aggr: "avg",
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'member-quality',
                    max: 1,
                    aggr: "sum",
                    post_modifier: toPercentagePostModifier
                }];
            var skills_star_configuration = {
                labels: ["Quality", "Speed", "Collaboration"],
                labelsAssoc: [{
                    'member-speed-fake': 'Speed',
                    'member-collaboration-fake': 'Collaboration',
                    'member-quality': 'Quality'
                }],
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
                    id: 'member-speed-fake',
                    max: 20,
                    aggr: 'avg',
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'member-collaboration-fake',
                    max: 20,
                    aggr: 'avg',
                    post_modifier: toPercentagePostModifier
                },
                {
                    id: 'member-quality',
                    max: 20,
                    aggr: 'sum',
                    post_modifier: toPercentagePostModifier
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
            var table_metrics = ['view-member-repositories'];
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
                        id: reposTableCtx,
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
                maxRowsSelected: 6,
                filterControl: true,
                initialSelectedRows: 5,
                showHeader: false,
                height: 330
            };
            var table = new framework.widgets.Table(table_dom, table_metrics, [timeCtx, userCtx], table_configuration);

            // HORIZONTAL CONTRIBUTION TO REPOSITORIES
            var multibar_repositories_dom = document.getElementById("repositories-commits-horizontal");
            var multibar_repositories_metrics = [{
                id: 'repository-member-commits',
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
                    id: 'member-commits',
                    max: 1,
                    aggr: 'sum'
                }
            };
            var multibar_projects = new framework.widgets.HorizontalBar(multibar_repositories_dom, multibar_repositories_metrics,
                    [timeCtx, userCtx, reposTableCtx], multibar_repositories_configuration);

            // COMMITS PER REPOSITORY AND USER
            var user_repositories_commits_dom = document.getElementById("repositories-commits-lines");
            var user_repositories_commits_metrics = [{
                id: 'repository-member-commits',
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
