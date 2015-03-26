@extends('layouts.panel')

@section('scripts')
    @parent
    <script src="assets/js/chart.js/Chart.min.js"></script> {{-- From https://github.com/nnnick/Chart.js --}}
    <script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/activityChart.js"></script>
    <script src="assets/js/HeatMapChart.js"></script>
    <script src="assets/js/dashboards/user-dashboard.js"></script>


@stop

@section('css')
    @parent
    <link rel="stylesheet" href="/assets/css/activityChart.css">
    <link rel="stylesheet" href="/assets/css/dashboards/user-dashboard.css">
@stop

@section('main-content')
    <div class="row">
        <div class="col col-sm-3 col-lg-3"> <!-- LEFT PANEL -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row"><!-- user -->
                        <div class="col text-center">
                            <img src="assets/images/demo/JohnSnow_small.jpg" alt="John Snow" class="img-responsive img-circle center-block">
                            <h1 style="color: #2c2e2f;">John Snow</h1>
                        </div>
                    </div>
                    <div class="row">
                        <canvas id="radar-chart"></canvas>
                    </div>
                    <div class="row"><!-- own projects -->
                        <div class="col">
                            <h3>Own Projects</h3>
                            <div id="self-projects-list" class="list-group"></div>
                        </div>
                    </div>
                    <div class="row"><!-- assigned projects -->
                        <div class="col">
                            <h3>Assigned Projects</h3>
                            <div id="assigned-projects-list" class="list-group"></div>
                        </div>
                    </div>
                    <div class="row"><!-- badges -->
                        <div class="col">
                            <h3>Badges</h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col col-sm-9 col-lg-9">  <!-- CENTER PANEL -->

            <div class="row"> <!-- TOP RANGE CHART -->
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

            <div class="row"> <!-- WIDGETS -->

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
                <div class="col-md-5"> <!-- LANGUAGES CHART -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                    <span>Most used languages</span>
                        </div>
                        <div class="panel-body">
                            <svg id="languages-chart"></svg>
                        </div>
                    </div>
                </div>
                <div class="col-md-7"> <!-- ISSUES CHART -->
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
    </div>





    <!-- Main Footer -->
    <!-- Choose between footer styles: "footer-type-1" or "footer-type-2" -->
    <!-- Add class "sticky" to  always stick the footer to the end of page (if page contents is small) -->
    <!-- Or class "fixed" to  always fix the footer to the end of page -->
    <footer class="main-footer sticky footer-type-1">

        <div class="footer-inner">

            <!-- Add your copyright text here -->
            <div class="footer-text">
                &copy; 2014
                <strong>Xenon</strong>
                theme by <a href="http://laborator.co" target="_blank">Laborator</a>
            </div>


            <!-- Go to Top Link, just add rel="go-top" to any link to add this functionality -->
            <div class="go-up">

                <a href="#" rel="go-top">
                    <i class="fa-angle-up"></i>
                </a>

            </div>

        </div>

    </footer>

@stop