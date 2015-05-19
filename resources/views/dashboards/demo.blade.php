@extends('layouts.panel')

@section('scripts')
    @parent
    <script type="application/javascript">
        SDH_API_URL = "http://localhost:12345";
    </script>
    <script type="application/javascript" src="sdh-framework/framework.js"></script>
    <script type="application/javascript" src="sdh-framework/framework.widget.common.js"></script>
    <script type="application/javascript" src="sdh-framework/framework.widget.rangechart.js"></script>
    <script type="application/javascript" src="sdh-framework/framework.widget.counterbox.js"></script>
    <script type="application/javascript" src="sdh-framework/framework.widget.bigcounterbox.js"></script>
    <script type="application/javascript" src="sdh-framework/framework.widget.heatmap.js"></script>
    <script type="application/javascript" src="sdh-framework/framework.widget.piechart.js"></script>

    <!-- moment src -->
    <script src="sdh-framework/lib/moment/moment.js"></script>

    <!-- code -->
    <script type="application/javascript">

        framework.ready(function() {
            console.log("Framework ready");
            var context4rangeChart = "default_rangeChartD3_Context_id";

            //TEST RANGE CHART
            var rangechart_dom = document.getElementById("rangeChart1");
            var rangechart_metrics = [{
                id: 'orgcommits',
                max: 0,
                from: moment().startOf('year').format("YYYY-MM-DD"),
                to: moment().format("YYYY-MM-DD")
            }];
            var rangechart = new framework.widgets.RangeChart(rangechart_dom, rangechart_metrics, null, {ownContext: context4rangeChart});

            //TEST COUNTERBOX 1
            var counterBox_dom = document.getElementById("commitsBox");
            var counterBox_metrics = [{
                id: 'orgcommits',
                max: 1
            }];
            var config =
            {
                label: 'Total Commits',
                background: '#FFF',
                icon: "octicon octicon-git-commit",
                decimal: 0
            };
            var counterBox = new framework.widgets.CounterBox(counterBox_dom, counterBox_metrics, context4rangeChart, config);

            //TEST COUNTERBOX 2
            var counterBox2_dom = document.getElementById("developersBox");
            var counterBox2_metrics = [{
                id: 'orgcommits',
                max: 1
            }];
            var config2 =
            {
                label: 'Total Developers',
                countercolor: '#FFF',
                background: '#6E6E6E',
                icon: "octicon octicon-organization",
                iconbackground: '#0E62C7',
                decimal: 0
            };
            var counterBox = new framework.widgets.CounterBox(counterBox2_dom, counterBox2_metrics, context4rangeChart, config2);

            //TEST BIGCOUNTERBOX
            var big1_dom = document.getElementById("bigBox1");
            var big1_metrics = [{
                id: 'orgcommits',
                max: 1
            },
                {
                    id: 'usercommits',
                    max: 1,
                    uid: 'u1'
                }];
            var config =
            {
                label: 'Open Issues',
                label2: 'Total Issues: ',
                labelcolor: '#00C27F',
                label2color: '#00C27F',
                countercolor: '#00C27F',
                counter2color: '#00C27F',
                background: "#000",
                icon: "octicon octicon-flame",
                iconcolor: "#00C27F",
                iconbackground: '#800909',
                decimal: 0
            };
            var big1 = new framework.widgets.BigCounterBox(big1_dom, big1_metrics, context4rangeChart, config);

            //TEST HEATMAP
            var heatmap_dom = document.getElementById("heatmap");
            var heatmap_metrics = [{
                id: 'usercommits',
                uid: 'u1',
                max: 365
            }];
            var heatmap = new framework.widgets.Heatmap(heatmap_dom, heatmap_metrics, context4rangeChart, null);

            //TEST PIECHART
            var piechart_dom = document.getElementById("piechart");
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
                }
            ];
            var piechart_configuration = {
                labelFormat: "User: %uid%"
            };
            var piechart = new framework.widgets.PieChart(piechart_dom, piechart_metrics, context4rangeChart, piechart_configuration);
        });
    </script>


@stop

@section('css')
    <link rel="stylesheet" href="assets/css/nv.d3.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/linecons/css/linecons.css">
    <link rel="stylesheet" href="assets/fonts/octicons/css/octicons.css">
    <link rel="stylesheet" type="text/css" href="sdh-framework/framework.widget.common.css">
    <link rel="stylesheet" type="text/css" href="sdh-framework/framework.widget.rangechart.css">
    <link rel="stylesheet" type="text/css" href="sdh-framework/framework.widget.heatmap.css">
    <link rel="stylesheet" type="text/css" href="sdh-framework/style/core.css">
    <link rel="stylesheet" type="text/css" href="sdh-framework/style/components.css">
@stop

@section('main-content')
    <div class="row">
        <div class="col" id="rangeChart1"></div> <!-- RANGE CHART -->
    </div>
    <div class="row">
        <div class="col col-sm-6" id="bigBox1"></div>
        <div class="col col-sm-3" id="developersBox"></div>
        <div class="col col-sm-3" id="commitsBox"></div>
    </div>
    <div class="row">
        <div class="col col-sm-4" id="piechart" style="height: 300px"></div>
        <div class="col col-sm-8" id="heatmap" style="height: 300px"></div>
    </div>
    <!--<div class="row">
        <div class="col col-sm-3 col-lg-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col text-center">
                            <img src="assets/images/demo/JohnSnow_small.jpg" alt="John Snow" class="img-responsive img-circle center-block">
                            <h1 style="color: #2c2e2f;">John Snow</h1>
                        </div>
                    </div>
                    <div class="row">
                        <canvas id="radar-chart"></canvas>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h3>Own Projects</h3>
                            <div id="self-projects-list" class="list-group"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h3>Assigned Projects</h3>
                            <div id="assigned-projects-list" class="list-group"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h3>Badges</h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col col-sm-9 col-lg-9">

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Added / deleted lines
                        </div>
                        <div class="panel-body">
                            <div id="range-chart" class=".activity-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-5">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span>Contributed projects</span>
                        </div>
                        <div class="panel-body">
                            <table id="cont-proj-id" cellspacing="0" class="table table-small-font dataTable" role="grid">
                                <thead>
                                <tr>
                                    <th>Project</th>
                                    <th aria-controls="cont-proj-id" aria-sort="descending" data-priority="2">Commits</th>
                                </tr>
                                </thead>
                                <tbody id="contributed-projects-table">
                                <tr>
                                    <th>GOOG <span class="co-name">Google Inc.</span></th>
                                    <td>597</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="row">
                        <div class="col-lg-6">

                            <div id="commits-counter" class="com-widget com-counter" data-count=".num" data-from="0" data-to="0" data-suffix=" commits" data-duration="2">
                                <div class="com-icon">
                                    <i class="octicon octicon-git-commit"></i>
                                </div>
                                <div class="com-label">
                                    <strong class="num">0 commits</strong>
                                    <span>Total number of commits</span>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6">

                            <div id="contributed-projects-counter" class="com-widget com-counter com-counter-red" data-count=".num" data-from="0" data-to="0" data-suffix=" projects" data-duration="2">
                                <div class="com-icon">
                                    <i class="octicon octicon-repo"></i>
                                </div>
                                <div class="com-label">
                                    <strong class="num">0 projects</strong>
                                    <span>Contributed projects</span>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6">

                            <div id="created-issues-counter" class="com-widget com-counter com-counter-blue" data-count=".num" data-from="0" data-to="0" data-suffix=" issues" data-duration="2">
                                <div class="com-icon">
                                    <i class="octicon octicon-issue-opened"></i>
                                </div>
                                <div class="com-label">
                                    <strong class="num">0 issues</strong>
                                    <span>Total created issues</span>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6">

                            <div id="resolved-issues-counter" class="com-widget com-counter com-counter-info" data-count=".num" data-from="0" data-to="0" data-suffix=" issues" data-duration="2">
                                <div class="com-icon">
                                    <i class="octicon octicon-issue-closed"></i>
                                </div>
                                <div class="com-label">
                                    <strong class="num">0 issues</strong>
                                    <span>Total solved issues</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                    <span>Most used languages</span>
                        </div>
                        <div class="panel-body">
                            <svg id="languages-chart"></svg>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                    <span>Issues</span>
                        </div>
                        <div class="panel-body">
                            <div id="issues-chart">
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                    <span>Contribution heatmap</span>
                        </div>
                        <div class="panel-body panel-body-heatchart">
                            <svg id="heatmap" role="heatmap" class="heatmap" preserveAspectRatio="xMidYMid"></svg>
                        </div>
                    </div>
                </div>
            </div>




        </div>
    </div>-->

@stop