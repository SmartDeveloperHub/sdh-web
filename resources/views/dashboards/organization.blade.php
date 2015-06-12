@extends('layouts.panel_old')

@section('scripts')
    @parent
	<!-- dashboards src -->
	<script src="assets/js/dashboards/organization-dashboard.js"></script>
	<script src="assets/js/activityChart.js"></script>
@stop

@section('css')
    @parent
	<!-- custom style -->
	<link rel="stylesheet" href="assets/css/dashboards/organization-dashboard.css">
	<link rel="stylesheet" href="assets/css/activityChart.css">
@stop

@section('timeChart')
	<div class="settings-pane open">
		<div id="timeBar">
			<a>
				<i class="timeBarIcon fa-angle-double-up"></i>
				<i id="timeBarLabel"></i>
				<i class="timeBarIcon fa-angle-double-up"></i>
			</a>
		</div>
		<div class="row" id="floatingRow"> <!-- TOP RANGE CHART -->
			<div class="col-sm-12 timePanel">
				<div class="panel panel-default">
					<div class="panel-body">
						<div id="activity-chart">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="timeBarDown">
		</div>
	</div>
@stop

@section('main-content')
<div class="row"> <!-- WIDGETS 1 -->

	<div class="col-sm-6">
		
		<div id="averageHealth-widget" class="com-widget com-progress-counter com-progress-counter-pink" data-count=".num" data-suffix="%" data-from="0" data-to="0" data-duration="2">
			<div class="leftBox">
				<div class="com-background">
					<i class="linecons-heart"></i>
				</div>
				<div class="com-upper">
					<div class="com-icon">
						<i class="linecons-heart"></i>
					</div>
					<div id="averageHealth-label" class="com-label">
						<span>health</span>
						<strong class="num">0%</strong>
					</div>
				</div>
				
				<div id="averageHealth-progress" class="com-progress">
					<span class="com-progress-fill" data-fill-from="0" data-fill-to="56" data-fill-unit="%" data-fill-property="width" data-fill-duration="3" data-fill-easing="true"></span>
				</div>
				<div class="com-lower">
					<strong>General Projects Health</strong>
				</div>
			</div>
			<div id="healthy-chart" style="height: 110px">
				<svg style="height: 100px"></svg>
			</div>
		</div>
		
	</div>
	<div class="col-sm-3">
		<div id="projects-widget" class="com-widget com-counter-block com-counter-block-turquoise" data-count=".num" data-extracount=".num .ntotal">
			<div class="com-upper">
				<div class="com-icon">
					<i class="octicon octicon-repo"></i>
				</div>
				<div class="com-label">
					<strong class="num" data-from="0" data-to="0" data-duration="3">0</strong>
					<span>Active <strong>Projects</strong></span>
				</div>
			</div>
			<div class="com-lower">
				<div class="border"></div>
				<span>Total Projects:
					<strong class="ntotal" data-from="0" data-to="0" data-duration="3">0</strong>
				</span>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div id="activeIssues-widget" class="com-widget com-counter-block com-counter-block-blue" data-count=".num" data-extracount=".num .ntotal">
			<div class="com-upper">
				
				<div class="com-icon">
					<i class="octicon octicon-issue-opened"></i>
				</div>
				<div class="com-label">
					<strong class="num" data-from="0" data-to="0" data-duration="3">0</strong>
					<span>Tickets <strong>Opened</strong></span>
				</div>
			</div>
			<div class="com-lower">
				<div class="border"></div>
				
				<span>Total Tickets:
					<strong class="ntotal" data-from="0" data-to="0" data-duration="3">0</strong>
				</span>
			</div>
		</div>
	</div>
</div>

<div class="row"> <!-- WIDGETS 2 -->
	<div class="col-sm-6">

		<div class="chart-item-bg">
			<div class="chart-label">
				<div id="devSpeed-label" class="h1 text-purple text-bold" data-count="this" data-from="0" data-to="0" data-suffix=" lines/m" data-duration="3">0 lines/m</div>
				<span class="text-small text-muted text-upper">Development Speed Average</span>
			</div>
			<!--div class="chart-right-legend">
				<div id="speed-gauge" style="width: 170px; height: 140px"></div>
			</div-->
			<div id="speed-chart" style="height: 213px; top: 100px;">
				<svg style="height: 213px"></svg>
			</div>
		</div>

	</div>
	<div class="col-sm-3">
		
		<div id="commits-widget" class="com-widget com-counter com-counter-green" data-count=".num" data-from="0" data-to="0" data-duration="3" data-easing="true">
			<div class="com-icon">
				<i class="octicon octicon-git-commit"></i>
			</div>
			<div class="com-label">
				<strong class="num">0</strong>
				<span>Commits</span>
			</div>
		</div>

		<div id="addlines-widget" class="com-widget com-counter com-counter-green" data-count=".num" data-from="0" data-to="0" data-duration="3" data-easing="false">
			<div class="com-icon">
				<i class="octicon octicon-diff"></i>
			</div>
			<div class="com-label">
				<strong class="num">0</strong>
				<span>Lines Added</span>
			</div>
		</div>

		<div id="rmlines-widget" class="com-widget com-counter com-counter-red" data-count=".num" data-from="0" data-to="0" data-duration="3" data-easing="true">
			<div class="com-icon">
				<i class="octicon octicon-diff"></i>
			</div>
			<div class="com-label">
				<strong class="num">0</strong>
				<span>Lines Removed</span>
			</div>
		</div>
	</div>

	<div class="col-sm-3">
		
		<div id="developers-widget" class="com-widget com-counter com-counter-blue" data-count=".num" data-from="0" data-to="0" data-duration="3" data-easing="true">
			<div class="com-icon">
				<i class="octicon octicon-organization"></i>
			</div>
			<div class="com-label">
				<strong class="num">0</strong>
				<span>Developers</span>
			</div>
		</div>

		<div id="devAverage-widget" class="com-widget com-counter com-counter-turquoise" data-count=".num" data-from="0" data-to="0" data-duration="3" data-easing="true">
			<div class="com-icon">
				<i class="fa-users"></i>
			</div>
			<div class="com-label">
				<strong class="num">0</strong>
				<span>Developers Per Project Average</span>
			</div>
		</div>

	</div>
</div>

<div class="row"> <!-- WIDGETS 3 -->
	<div class="col-sm-12">
	    <div class="panel panel-default">
	        <div class="panel-heading">
	            <span>Commits and code lines</span>
	        </div>
	        <div id="multiline-chart" class="panel-body">
	            <svg style="height: 300px"></svg>
	        </div>
	    </div>
	</div>
</div>
@stop