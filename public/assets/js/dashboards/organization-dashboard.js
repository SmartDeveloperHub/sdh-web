/*
#-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
  This file is part of the Smart Developer Hub Project:
    http://www.smartdeveloperhub.org
  Center for Open Middleware
        http://www.centeropenmiddleware.com/
#-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
  Copyright (C) 2015 Center for Open Middleware.
#-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at
            http://www.apache.org/licenses/LICENSE-2.0
  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
#-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
*/

var updateAll, randomData4Test, generalActivitySources, actChart, healthyChart, speedGraph, multilineChart;

jQuery(document).ready(function($)
{
	var gaugesPalette = ['#8dc63f', '#40bbea', '#ffba00', '#cc3f44'];
		
	// Data Sources for all charts
	var lines_added_data = [
		{ time: new Date("October 01, 2014"), lines: 69 },
		{ time: new Date("October 02, 2014"), lines: 1190 },
		{ time: new Date("October 03, 2014"), lines: 790 },
		{ time: new Date("October 04, 2014"), lines: 850 },
		{ time: new Date("October 05, 2014"), lines: 1060 },
		{ time: new Date("October 06, 2014"), lines: 1100 },
		{ time: new Date("October 07, 2014"), lines: 2960 },
		{ time: new Date("October 08, 2014"), lines: 1280 },
		{ time: new Date("October 09, 2014"), lines: 510 },
		{ time: new Date("October 10, 2014"), lines: 700 },
		{ time: new Date("October 11, 2014"), lines: 2040 },
		{ time: new Date("October 12, 2014"), lines: 2067 },
		{ time: new Date("October 13, 2014"), lines: 1840 },
		{ time: new Date("October 14, 2014"), lines: 480 },
		{ time: new Date("October 15, 2014"), lines: 750 },
		{ time: new Date("October 16, 2014"), lines: 2000 },
		{ time: new Date("October 17, 2014"), lines: 90 },
		{ time: new Date("October 18, 2014"), lines: 370 },
		{ time: new Date("October 19, 2014"), lines: 1520 },
		{ time: new Date("October 20, 2014"), lines: 1780 },
		{ time: new Date("October 21, 2014"), lines: 2540 },
		{ time: new Date("October 22, 2014"), lines: 1840 },
		{ time: new Date("October 23, 2014"), lines: 1670 },
		{ time: new Date("October 24, 2014"), lines: 1120 },
		{ time: new Date("October 25, 2014"), lines: 880 },
		{ time: new Date("October 26, 2014"), lines: 2000 },
		{ time: new Date("October 27, 2014"), lines: 1900 },
		{ time: new Date("October 28, 2014"), lines: 1800 },
		{ time: new Date("October 29, 2014"), lines: 1700 },
		{ time: new Date("October 30, 2014"), lines: 1600 },
		{ time: new Date("October 31, 2014"), lines: 1500 },
		{ time: new Date("November 01, 2014"), lines: 1400 },
		{ time: new Date("November 02, 2014"), lines: 1300 },
		{ time: new Date("November 03, 2014"), lines: 1200 },
		{ time: new Date("November 04, 2014"), lines: 1100 },
		{ time: new Date("November 05, 2014"), lines: 1000 },
		{ time: new Date("November 06, 2014"), lines: 900 },
		{ time: new Date("November 07, 2014"), lines: 800 },
		{ time: new Date("November 08, 2014"), lines: 700 },
		{ time: new Date("November 09, 2014"), lines: 600 },
		{ time: new Date("November 10, 2014"), lines: 500 },
		{ time: new Date("November 11, 2014"), lines: 400 },
		{ time: new Date("November 12, 2014"), lines: 300 },
		{ time: new Date("November 13, 2014"), lines: 200 },
		{ time: new Date("November 14, 2014"), lines: 100 },
		{ time: new Date("November 15, 2014"), lines: 50 },
		{ time: new Date("November 16, 2014"), lines: 300 },
		{ time: new Date("November 17, 2014"), lines: 900 },
		{ time: new Date("November 18, 2014"), lines: 1300 },
	];

		
	// Data Sources for all charts
	var lines_removed_data = [
		{ time: new Date("October 01, 2014"), lines: 69 },
		{ time: new Date("October 02, 2014"), lines: 600 },
		{ time: new Date("October 03, 2014"), lines: 400 },
		{ time: new Date("October 04, 2014"), lines: 21 },
		{ time: new Date("October 05, 2014"), lines: 400 },
		{ time: new Date("October 06, 2014"), lines: 568 },
		{ time: new Date("October 07, 2014"), lines: 1000 },
		{ time: new Date("October 08, 2014"), lines: 340 },
		{ time: new Date("October 09, 2014"), lines: 5 },
		{ time: new Date("October 10, 2014"), lines: 70 },
		{ time: new Date("October 11, 2014"), lines: 500 },
		{ time: new Date("October 12, 2014"), lines: 345 },
		{ time: new Date("October 13, 2014"), lines: 140 },
		{ time: new Date("October 14, 2014"), lines: 0 },
		{ time: new Date("October 15, 2014"), lines: 76 },
		{ time: new Date("October 16, 2014"), lines: 20 },
		{ time: new Date("October 17, 2014"), lines: 300 },
		{ time: new Date("October 18, 2014"), lines: 100 },
		{ time: new Date("October 19, 2014"), lines: 12 },
		{ time: new Date("October 20, 2014"), lines: 356 },
		{ time: new Date("October 21, 2014"), lines: 932 },
		{ time: new Date("October 22, 2014"), lines: 540 },
		{ time: new Date("October 23, 2014"), lines: 670 },
		{ time: new Date("October 24, 2014"), lines: 784 },
		{ time: new Date("October 25, 2014"), lines: 123 },
		{ time: new Date("October 26, 2014"), lines: 2000 },
		{ time: new Date("October 27, 2014"), lines: 1900 },
		{ time: new Date("October 28, 2014"), lines: 1800 },
		{ time: new Date("October 29, 2014"), lines: 1700 },
		{ time: new Date("October 30, 2014"), lines: 1600 },
		{ time: new Date("October 31, 2014"), lines: 1500 },
		{ time: new Date("November 01, 2014"), lines: 1400 },
		{ time: new Date("November 02, 2014"), lines: 1300 },
		{ time: new Date("November 03, 2014"), lines: 1200 },
		{ time: new Date("November 04, 2014"), lines: 1100 },
		{ time: new Date("November 05, 2014"), lines: 1000 },
		{ time: new Date("November 06, 2014"), lines: 900 },
		{ time: new Date("November 07, 2014"), lines: 800 },
		{ time: new Date("November 08, 2014"), lines: 700 },
		{ time: new Date("November 09, 2014"), lines: 600 },
		{ time: new Date("November 10, 2014"), lines: 500 },
		{ time: new Date("November 11, 2014"), lines: 400 },
		{ time: new Date("November 12, 2014"), lines: 300 },
		{ time: new Date("November 13, 2014"), lines: 200 },
		{ time: new Date("November 14, 2014"), lines: 100 },
		{ time: new Date("November 15, 2014"), lines: 50 },
		{ time: new Date("November 16, 2014"), lines: 300 },
		{ time: new Date("November 17, 2014"), lines: 900 },
		{ time: new Date("November 18, 2014"), lines: 1300 },
	];

	var speed_dataset = [
		{"time":"2014-10-01T22:00:00.000Z","speed":3225},
		{"time":"2014-10-02T22:00:00.000Z","speed":975},
		{"time":"2014-10-03T22:00:00.000Z","speed":0},
		{"time":"2014-10-04T22:00:00.000Z","speed":6150},
		{"time":"2014-10-05T22:00:00.000Z","speed":1330},
		{"time":"2014-10-06T22:00:00.000Z","speed":4900},
		{"time":"2014-10-07T22:00:00.000Z","speed":2350},
		{"time":"2014-10-08T22:00:00.000Z","speed":1262.5},
		{"time":"2014-10-09T22:00:00.000Z","speed":1575},
		{"time":"2014-10-10T22:00:00.000Z","speed":10100},
		{"time":"2014-10-11T22:00:00.000Z","speed":4305},
		{"time":"2014-10-12T22:00:00.000Z","speed":4250},
		{"time":"2014-10-13T22:00:00.000Z","speed":1200},
		{"time":"2014-10-14T22:00:00.000Z","speed":1685},
		{"time":"2014-10-15T22:00:00.000Z","speed":4950},
		{"time":"2014-10-16T22:00:00.000Z","speed":0},
		{"time":"2014-10-17T22:00:00.000Z","speed":675},
		{"time":"2014-10-18T22:00:00.000Z","speed":3770},
		{"time":"2014-10-19T22:00:00.000Z","speed":3560},
		{"time":"2014-10-20T22:00:00.000Z","speed":6270},
		{"time":"2014-10-21T22:00:00.000Z","speed":5000},
		{"time":"2014-10-22T22:00:00.000Z","speed":0},
		{"time":"2014-10-23T22:00:00.000Z","speed":840},
		{"time":"2014-10-24T22:00:00.000Z","speed":1892.5},
		{"time":"2014-10-25T22:00:00.000Z","speed":3225},
		{"time":"2014-10-26T22:00:00.000Z","speed":975},
		{"time":"2014-10-27T22:00:00.000Z","speed":0},
		{"time":"2014-10-28T22:00:00.000Z","speed":6150},
		{"time":"2014-10-29T22:00:00.000Z","speed":1330},
		{"time":"2014-10-30T22:00:00.000Z","speed":4900},
		{"time":"2014-10-31T22:00:00.000Z","speed":2350},
		{"time":"2014-11-01T22:00:00.000Z","speed":1262.5},
		{"time":"2014-11-02T22:00:00.000Z","speed":1575},
		{"time":"2014-11-03T22:00:00.000Z","speed":10100},
		{"time":"2014-11-04T22:00:00.000Z","speed":4305},
		{"time":"2014-11-05T22:00:00.000Z","speed":4250},
		{"time":"2014-11-06T22:00:00.000Z","speed":1200},
		{"time":"2014-11-07T22:00:00.000Z","speed":1685},
		{"time":"2014-11-08T22:00:00.000Z","speed":4950},
		{"time":"2014-11-09T22:00:00.000Z","speed":0},
		{"time":"2014-11-10T22:00:00.000Z","speed":675},
		{"time":"2014-11-11T22:00:00.000Z","speed":3770},
		{"time":"2014-11-12T22:00:00.000Z","speed":3560},
		{"time":"2014-11-13T22:00:00.000Z","speed":6270},
		{"time":"2014-11-14T22:00:00.000Z","speed":5000},
		{"time":"2014-11-15T22:00:00.000Z","speed":0},
		{"time":"2014-11-16T22:00:00.000Z","speed":840},
		{"time":"2014-11-17T22:00:00.000Z","speed":1892.5}
	];

	// Healthy data
	var healthy_data = [
		{ time: new Date("October 01, 2014"), health: 0.5 },
		{ time: new Date("October 02, 2014"), health: 0.00 },
		{ time: new Date("October 03, 2014"), health: 0.12 },
		{ time: new Date("October 04, 2014"), health: 0.41 },
		{ time: new Date("October 05, 2014"), health: 0.62 },
		{ time: new Date("October 06, 2014"), health: 0.79 },
		{ time: new Date("October 07, 2014"), health: 0.85 },
		{ time: new Date("October 08, 2014"), health: 0.99 },
		{ time: new Date("October 09, 2014"), health: 0.80 },
		{ time: new Date("October 10, 2014"), health: 0.33 },
		{ time: new Date("October 11, 2014"), health: 0.50 },
		{ time: new Date("October 12, 2014"), health: 0.95 },
		{ time: new Date("October 13, 2014"), health: 0.90 },
		{ time: new Date("October 14, 2014"), health: 0.90 },
		{ time: new Date("October 15, 2014"), health: 0.98 },
		{ time: new Date("October 16, 2014"), health: 0.99 },
		{ time: new Date("October 17, 2014"), health: 0.94 },
		{ time: new Date("October 18, 2014"), health: 0.91 },
		{ time: new Date("October 19, 2014"), health: 0.92 },
		{ time: new Date("October 20, 2014"), health: 0.93 },
		{ time: new Date("October 21, 2014"), health: 0.94 },
		{ time: new Date("October 22, 2014"), health: 0.95 },
		{ time: new Date("October 23, 2014"), health: 0.96 },
		{ time: new Date("October 24, 2014"), health: 0.97 },
		{ time: new Date("October 25, 2014"), health: 0.98 },
		{ time: new Date("October 26, 2014"), health: 0.99 },
		{ time: new Date("October 27, 2014"), health: 0.98 },
		{ time: new Date("October 28, 2014"), health: 0.97 },
		{ time: new Date("October 29, 2014"), health: 0.95 },
		{ time: new Date("October 30, 2014"), health: 0.80 },
		{ time: new Date("October 31, 2014"), health: 0.70 },
		{ time: new Date("November 01, 2014"), health: 0.75 },
		{ time: new Date("November 02, 2014"), health: 0.70 },
		{ time: new Date("November 03, 2014"), health: 0.80 },
		{ time: new Date("November 04, 2014"), health: 0.90 },
		{ time: new Date("November 05, 2014"), health: 0.91 },
		{ time: new Date("November 06, 2014"), health: 0.92 },
		{ time: new Date("November 07, 2014"), health: 0.93 },
		{ time: new Date("November 08, 2014"), health: 0.94 },
		{ time: new Date("November 09, 2014"), health: 0.95 },
		{ time: new Date("November 10, 2014"), health: 0.99 },
		{ time: new Date("November 11, 2014"), health: 0.90 },
		{ time: new Date("November 12, 2014"), health: 0.89 },
		{ time: new Date("November 13, 2014"), health: 0.88 },
		{ time: new Date("November 14, 2014"), health: 0.87 },
		{ time: new Date("November 15, 2014"), health: 0.86 },
		{ time: new Date("November 16, 2014"), health: 0.85 },
		{ time: new Date("November 17, 2014"), health: 0.90 },
		{ time: new Date("November 18, 2014"), health: 0.95 },
	];

	var total_commits_serie = [
		{ time: new Date("October 01, 2014"), commits: 100 },
		{ time: new Date("October 02, 2014"), commits: 200 },
		{ time: new Date("October 03, 2014"), commits: 250 },
		{ time: new Date("October 04, 2014"), commits: 300 },
		{ time: new Date("October 05, 2014"), commits: 500 },
		{ time: new Date("October 06, 2014"), commits: 600 },
		{ time: new Date("October 07, 2014"), commits: 750 },
		{ time: new Date("October 08, 2014"), commits: 760 },
		{ time: new Date("October 09, 2014"), commits: 800 },
		{ time: new Date("October 10, 2014"), commits: 850 },
		{ time: new Date("October 11, 2014"), commits: 900 },
		{ time: new Date("October 12, 2014"), commits: 950 },
		{ time: new Date("October 13, 2014"), commits: 1000 },
		{ time: new Date("October 14, 2014"), commits: 1100 },
		{ time: new Date("October 15, 2014"), commits: 1150 },
		{ time: new Date("October 16, 2014"), commits: 1200 },
		{ time: new Date("October 17, 2014"), commits: 1300 },
		{ time: new Date("October 18, 2014"), commits: 1400 },
		{ time: new Date("October 19, 2014"), commits: 1500 },
		{ time: new Date("October 20, 2014"), commits: 1600 },
		{ time: new Date("October 21, 2014"), commits: 1700 },
		{ time: new Date("October 22, 2014"), commits: 1800 },
		{ time: new Date("October 23, 2014"), commits: 1900 },
		{ time: new Date("October 24, 2014"), commits: 2000 },
		{ time: new Date("October 25, 2014"), commits: 2100 },
		{ time: new Date("October 26, 2014"), commits: 2300 },
		{ time: new Date("October 27, 2014"), commits: 2500 },
		{ time: new Date("October 28, 2014"), commits: 3000 },
		{ time: new Date("October 29, 2014"), commits: 4000 },
		{ time: new Date("October 30, 2014"), commits: 5000 },
		{ time: new Date("October 31, 2014"), commits: 5200 },
		{ time: new Date("November 01, 2014"), commits: 5500 },
		{ time: new Date("November 02, 2014"), commits: 5600 },
		{ time: new Date("November 03, 2014"), commits: 5700 },
		{ time: new Date("November 04, 2014"), commits: 6000 },
		{ time: new Date("November 05, 2014"), commits: 6200 },
		{ time: new Date("November 06, 2014"), commits: 7000 },
		{ time: new Date("November 07, 2014"), commits: 7300 },
		{ time: new Date("November 08, 2014"), commits: 7900 },
		{ time: new Date("November 09, 2014"), commits: 8100 },
		{ time: new Date("November 10, 2014"), commits: 8360 },
		{ time: new Date("November 11, 2014"), commits: 8500 },
		{ time: new Date("November 12, 2014"), commits: 8700 },
		{ time: new Date("November 13, 2014"), commits: 9060 },
		{ time: new Date("November 14, 2014"), commits: 9400 },
		{ time: new Date("November 15, 2014"), commits: 9600 },
		{ time: new Date("November 16, 2014"), commits: 9700 },
		{ time: new Date("November 17, 2014"), commits: 9750 },
		{ time: new Date("November 18, 2014"), commits: 10000 },
	];

	// Data Sources for all charts
	var total_lines_serie = [
		{ time: new Date("October 01, 2014"), lines: 1000 },
		{ time: new Date("October 02, 2014"), lines: 1500 },
		{ time: new Date("October 03, 2014"), lines: 2200 },
		{ time: new Date("October 04, 2014"), lines: 4000 },
		{ time: new Date("October 05, 2014"), lines: 5000 },
		{ time: new Date("October 06, 2014"), lines: 6000 },
		{ time: new Date("October 07, 2014"), lines: 7000 },
		{ time: new Date("October 08, 2014"), lines: 8700 },
		{ time: new Date("October 09, 2014"), lines: 9020 },
		{ time: new Date("October 10, 2014"), lines: 10000 },
		{ time: new Date("October 11, 2014"), lines: 12000 },
		{ time: new Date("October 12, 2014"), lines: 13500},
		{ time: new Date("October 13, 2014"), lines: 13600 },
		{ time: new Date("October 14, 2014"), lines: 13800 },
		{ time: new Date("October 15, 2014"), lines: 14000 },
		{ time: new Date("October 16, 2014"), lines: 14200 },
		{ time: new Date("October 17, 2014"), lines: 14600 },
		{ time: new Date("October 18, 2014"), lines: 14900 },
		{ time: new Date("October 19, 2014"), lines: 15200 },
		{ time: new Date("October 20, 2014"), lines: 15300 },
		{ time: new Date("October 21, 2014"), lines: 15400 },
		{ time: new Date("October 22, 2014"), lines: 15600 },
		{ time: new Date("October 23, 2014"), lines: 16000 },
		{ time: new Date("October 24, 2014"), lines: 17000 },
		{ time: new Date("October 25, 2014"), lines: 17300 },
		{ time: new Date("October 26, 2014"), lines: 17400 },
		{ time: new Date("October 27, 2014"), lines: 17400 },
		{ time: new Date("October 28, 2014"), lines: 17400 },
		{ time: new Date("October 29, 2014"), lines: 17900 },
		{ time: new Date("October 30, 2014"), lines: 18400 },
		{ time: new Date("October 31, 2014"), lines: 19400 },
		{ time: new Date("November 01, 2014"), lines: 20400 },
		{ time: new Date("November 02, 2014"), lines: 21300 },
		{ time: new Date("November 03, 2014"), lines: 22000 },
		{ time: new Date("November 04, 2014"), lines: 23400 },
		{ time: new Date("November 05, 2014"), lines: 23400 },
		{ time: new Date("November 06, 2014"), lines: 24000 },
		{ time: new Date("November 07, 2014"), lines: 25000 },
		{ time: new Date("November 08, 2014"), lines: 25500 },
		{ time: new Date("November 09, 2014"), lines: 25600 },
		{ time: new Date("November 10, 2014"), lines: 26500 },
		{ time: new Date("November 11, 2014"), lines: 27900 },
		{ time: new Date("November 12, 2014"), lines: 28900 },
		{ time: new Date("November 13, 2014"), lines: 30000 },
		{ time: new Date("November 14, 2014"), lines: 31400 },
		{ time: new Date("November 15, 2014"), lines: 32400 },
		{ time: new Date("November 16, 2014"), lines: 33400 },
		{ time: new Date("November 17, 2014"), lines: 34400 },
		{ time: new Date("November 18, 2014"), lines: 35400 },
	];

	// Combine charts for filtering, grouped by time

	var rangedData = {
		commits: 0,
		linesadd: 0,
		linesrm: 0,
		speedSerie: speed_dataset,
		doneIssues: 0,
		activeIssues: 0,
		activeIssues: 0,
		averageHealth: 0,
		healthSerie: 0,
		totalProjects: 0,
		activeProjects: 0,
		developers: 0,
		averageDevelopers: 0,
		speed: 0,
	};

	var updateRangeLabel = function updateRangeLabel(startDate, endDate){
		var timeLabel = $("#timeBarLabel")[0];
		if (timeLabel) {
			timeLabel.innerHTML = "[ <span class='dateBarLabel'>" + moment(startDate).format('LL') + "</span> , <span class='dateBarLabel'>" + moment(endDate).format('LL') + "</span> ]";
		}
	};

	generalActivitySources = [
	    {
	      values: [],      //values - represents the array of {x,y} data points
	      key: 'Lines Added', //key  - the name of the series.
	      color: '#2ca02c',  //color - optional: choose your own line color.
	      area: true      //area - set to true if you want this line to turn into a filled area chart.
	    },
	    {
	      values: [],
	      key: 'Lines Removed',
	      color: '#ff7f0e',
	      area: true      //area - set to true if you want this line to turn into a filled area chart.
	    }
	];
	$.map(lines_added_data, function(arg, i) {
		generalActivitySources[0].values.push(
			{date: new Date(arg.time), lines: lines_added_data[i].lines}
		);
		generalActivitySources[1].values.push(
			{date: new Date(arg.time), lines: -lines_removed_data[i].lines}
		);
	});
	// Load Range Chart
	//activityChart(data, containerId, changeHandler, brushedHandler)
	activityChart(generalActivitySources, "activity-chart", function(d1, d2) {
	        randomData4Test(d1, d2);
	        updateAll(d1);
	    },
	    updateRangeLabel
    );

	var loadGraphs = function loadGraphs() {
		// Healthy line graph
		nv.addGraph(function() {
			healthyChart = nv.models.lineChart()
		        .margin(({"left":28,"right":5,"top":10,"bottom":12}))  //Adjust chart margins to give the x-axis some breathing room.
		        .useInteractiveGuideline(true)  //We want nice looking tooltips and a guideline!
		        .showLegend(false)     //Show the legend, allowing users to turn on/off line series.
		        .showYAxis(true)       //Show the y-axis
		        .showXAxis(false)       //Show the x-axis
		        .forceY([0,25,50,100])
			;
			healthyChart.yAxis     //Chart y-axis settings
				.axisLabel('')
				.tickFormat(function(d){return d + "%"})
				.tickValues([0, 25, 50, 75, 100]);

			healthyChart.xAxis     //Chart y-axis settings
				.axisLabel('')
				.tickFormat(function(d){return ""})

			/* Done setting the chart up? Time to render it!*/
			var myData = [{
				values: rangedData.healthSerie.map(function(d) {
					return {'x': d.time, 'y': d.health} //values - represents the array of {x,y} data points
				}),
				key: 'Health', //key  - the name of the series.
				color: '#FFF'  //color - optional: choose your own line color.
			}];

			d3.select('#healthy-chart svg')    //Select the <svg> element you want to render the chart in.   
				.datum(myData)         //Populate the <svg> element with chart data...
				.call(healthyChart);          //Finally, render the chart!

			//Update the chart when window resizes.
			nv.utils.windowResize(function() { healthyChart.update() });

			// Create the event
			var event = new CustomEvent("chart-loaded",{detail:"healthy-chart"});

			// Dispatch/Trigger/Fire the event
			document.dispatchEvent(event);

			return healthyChart;
		});

		// Healthy line graph
		nv.addGraph(function() {
			speedGraph = nv.models.lineChart()
		        .margin(({"left":60,"right":20,"top":10,"bottom":27}))  //Adjust chart margins to give the x-axis some breathing room.
		        .useInteractiveGuideline(true)  //We want nice looking tooltips and a guideline!
		        .showLegend(true)      //Show the legend, allowing users to turn on/off line series.
		        .showYAxis(true)       //Show the y-axis
		        .showXAxis(true)       //Show the x-axis
		        .forceY([0,25,50,100])
			;
			speedGraph.yAxis     //Chart y-axis settings
				.axisLabel('')
				.tickFormat(function(d){return d + "l/m"});

			speedGraph.xAxis     //Chart y-axis settings
				.axisLabel('')
				.tickFormat(function(d) { return d3.time.format('%b %d')(new Date(d)); });

			/* Done setting the chart up? Time to render it!*/
			var myData = [{
				values: rangedData.speedSerie.map(function(d) {
					return {'x': new Date(d.time), 'y': d.speed} //values - represents the array of {x,y} data points
				}),
				key: 'Development Speed', //key  - the name of the series.
				color: '#010FA5'  //color - optional: choose your own line color.
			}];

			d3.select('#speed-chart svg')
				.datum(myData)
				.call(speedGraph);

			//Update the chart when window resizes.
			nv.utils.windowResize(function() { speedGraph.update() });

			// Create the event
			var event = new CustomEvent("chart-loaded",{detail:"speed-chart"});

			// Dispatch/Trigger/Fire the event
			document.dispatchEvent(event);

			return speedGraph;
		});

		// Commits_lines graph
		nv.addGraph(function() {
			multilineChart = nv.models.lineChart()
		        .margin(({"left":60,"right":20,"top":10,"bottom":27}))  //Adjust chart margins to give the x-axis some breathing room.
		        .useInteractiveGuideline(true)  //We want nice looking tooltips and a guideline!
		        .showLegend(true)      //Show the legend, allowing users to turn on/off line series.
		        .showYAxis(true)       //Show the y-axis
		        .showXAxis(true)       //Show the x-axis
			;
			multilineChart.xAxis     //Chart y-axis settings
				.axisLabel('')
				.tickFormat(function(d) { return d3.time.format('%b %d')(new Date(d)); });

			multilineChart.yAxis     //Chart y-axis settings
				.axisLabel('')
				.tickFormat(function(d){
					if (d < 1000) {
						return parseInt(d);
					} else {
						return parseInt(d/1000) + 'K';
					}
				});

			/*multilineChart.y2Axis
			.tickFormat(d3.format(',f'));*/

			var devAux = 15;
			var myData = [{
				values: rangedData.commitSerie.map(function(d) {
					return {'x':d.time, 'y': d.commits} //values - represents the array of {x,y} data points
				}),
				key: 'Commits', //key  - the name of the series.
				color: 'blue',  //color - optional: choose your own line color.
				area: true,
			},
			{
				values: rangedData.linesSerie.map(function(d) {
					return {'x':d.time, 'y': d.lines} //values - represents the array of {x,y} data points
				}),
				key: 'Code lines', //key  - the name of the series.
				color: 'red',  //color - optional: choose your own line color.
				area: true,
			},
			{
				values: rangedData.linesSerie.map(function(d) {
					devAux = devAux + parseInt(Math.random() * 100) + 19;
					return {'x':d.time, 'y': devAux} //values - represents the array of {x,y} data points
				}),
				key: 'Developers', //key  - the name of the series.
				color: 'yellow',  //color - optional: choose your own line color.
				area:true
			}];

			d3.select('#multiline-chart svg')
				.datum(myData)
				.call(multilineChart);

			//Update the chart when window resizes.
			nv.utils.windowResize(function() { multilineChart.update() });

			// Create the event
			var event = new CustomEvent("chart-loaded",{detail:"multiline-chart"});

			// Dispatch/Trigger/Fire the event
			document.dispatchEvent(event);

			return multilineChart;
		});
	};

	/*$('#speed-gauge').dxCircularGauge({
		scale: {
			startValue: 0,
			endValue: 6000,
			majorTick: {
				tickInterval: 200
			}
		},
		rangeContainer: {
			palette: 'pastel',
			width: 3,
			ranges: [
				{ startValue: 0, endValue: 1500, color: "#686868" },
				{ startValue: 1500, endValue: 3000, color: "#A6B425" },
				{ startValue: 3000, endValue: 4500, color: "#C89025" },
				{ startValue: 4500, endValue: 6000, color: "#d5080f" },
			],
		},
		value: 0,
		valueIndicator: {
			offset: 10,
			color: '#7c38bc',
			type: 'triangleNeedle',
			spindleSize: 12
		}
	});*/

	/*$("#speed-chart").dxChart({
		dataSource: rangedData.speedSerie,
		commonSeriesSettings: {
			argumentField: "time",
            label: {
                connector: { visible: true },
            }
		},
		series: [
			{ valueField: "speed", name: "speed", color: '#0E62C7', opacity: .4 },
		],
		tooltip: {
			enabled: true,
            format: 'largeNumber',
            customizeTooltip: function (e) {
                return {
                    text: e.valueText + ' lines/m <br \>(' + e.argument.getDate() + '/' + (e.argument.getMonth() + 1) + '/' + e.argument.getFullYear() + ')',
                }
            }
	    },
		legend: {
			verticalAlignment: "bottom",
			horizontalAlignment: "center"
		},
		commonAxisSettings: {
			label: {
				visible: false
			},
			grid: {
				visible: true,
				color: 'rgba(255, 0, 0, 0.16)'
			}
		},
		legend: {
			visible: false
		},
		argumentAxis: {
	        valueMarginsEnabled: true
	    },
		valueAxis: {
			max: 9000
		},
		animation: {
			enabled: true
		}
	});*/

	var speedLabelUpdate = function speedLabelUpdate(value) {
		var $el = jQuery("#devSpeed-label");
		var options = {
				useEasing : true, 
				useGrouping : true, 
				separator : '.', 
				decimal : ',', 
				prefix : '' ,
				suffix : ' lines/m' 
			};
		var cntr = new countUp($el[0], $el.data('to'), value, 0, 3, options);
			
		cntr.start();
		$el.data('to', value);
	}

	var updateHealthChart = function updateHealthChart(values) {
		var myData = [{
			values: values.map(function(d) {
				return {'x':d.time, 'y': d.health} //values - represents the array of {x,y} data points
			}),
			key: 'Health', //key  - the name of the series.
			color: '#FFF'  //color - optional: choose your own line color.
		}];
		d3.select('#healthy-chart svg')
			.datum(myData);
		healthyChart.update();
	}

	var updateSpeedGauge = function updateSpeedGauge(value) {
		var nr_gauge = jQuery('#speed-gauge').dxCircularGauge('instance');
		
		nr_gauge.value(value);
	}

	var updateSpeedGraph = function updateSpeedGraph(values) {
		var myData = [{
			values: values.map(function(d) {
				return {'x':d.time, 'y': d.speed} //values - represents the array of {x,y} data points
			}),
			key: 'Development Speed', //key  - the name of the series.
			color: '#010FA5'  //color - optional: choose your own line color.
		}];
		d3.select('#speed-chart svg')
			.datum(myData);
		speedGraph.update();
	};

	var updateMultilineGraph = function updateMultilineGraph(commits, lines) {
		var devAux = 15;
		var myData = [{
			values: rangedData.commitSerie.map(function(d) {
				return {'x':d.time, 'y': d.commits} //values - represents the array of {x,y} data points
			}),
			key: 'Commits', //key  - the name of the series.
			color: 'blue',  //color - optional: choose your own line color.
			area: true,
		},
		{
			values: rangedData.linesSerie.map(function(d) {
				return {'x':d.time, 'y': d.lines} //values - represents the array of {x,y} data points
			}),
			key: 'Code lines', //key  - the name of the series.
			color: 'red',  //color - optional: choose your own line color.
			area: true,
		},
		{
			values: rangedData.linesSerie.map(function(d) {
				devAux = devAux + (Math.random() * 100) + 10;
				return {'x':d.time, 'y': devAux} //values - represents the array of {x,y} data points
			}),
			key: 'Developers', //key  - the name of the series.
			color: 'yellow',  //color - optional: choose your own line color.
			area: true
		}];

		d3.select('#multiline-chart svg')
			.datum(myData);
		multilineChart.update();
	};

	var updateSimpleWidget = function updateSimpleWidget(element, newValue, decimals){
        
        var options = {
            useEasing : true, 
            useGrouping : true, 
            separator : ',', 
            decimal : '.', 
            prefix : '' ,
            suffix : $(element).data('suffix')
        };
        
        var cntr = new countUp($(element).find($(element).data('count'))[0], $(element).data('to'), newValue, decimals, $(element).data('duration'), options);
        cntr.start();
        $(element).data('to', newValue);
    };

    var updateProgressWidget = function updateProgressWidget(element, newValue, decimals) {
    	updateSimpleWidget(element, newValue, decimals);
		var myProgressBar = $(element).find(".com-progress-fill");
		myProgressBar.data('fill-to', newValue);
		myProgressBar[0].style.width = newValue + '%';
		var myRed, myGreen, myBlue;
		if(newValue >= 50) {
			myRed = 200 - (200-0)*(Math.pow(newValue-50,4)/6250000);
			myGreen = 180;
			myBlue = 37;
		} else {
			myRed = 200;
			myGreen = 0 - (0-180)*(newValue)/50;
			myBlue = 37;
		}
		console.log("percent: " + newValue + "  red: " + myRed + "  green: " + myGreen);
		element[0].style.backgroundColor = "rgb(" + parseInt(myRed) + ', ' + parseInt(myGreen) + ", " + parseInt(myBlue) + ")";
    };

    var updateBigWidget = function updateBigWidget (element, newValueList, decimalsList) {
    	var suffixList = $(element).data('extraSuffix')
    	if (suffixList) {
    		suffixList = suffixList.split(" ");
    	} else {
    		suffixList = [];
    	}
    	var classToChangeList = $(element).data('extracount').split(" ");
		var i, options, cntr;

    	for (i=0; i < newValueList.length; i++) {
	    	options = {
	            useEasing : true, 
	            useGrouping : true, 
	            separator : ',', 
	            decimal : '.', 
	            prefix : '' ,
	            suffix : suffixList[i]
	        };
	        
	        cntr = new countUp($(element).find(classToChangeList[i])[0], $(element).find(classToChangeList[i]).data('to'), newValueList[i], decimalsList[i], $(element).find(classToChangeList[i]).data('duration'), options);
	        cntr.start();
	        $(element).find(classToChangeList[i]).data('to', newValueList[i]);
	    }
    }

	updateAll = function updateAll() {
		updateProgressWidget($('#averageHealth-widget'), rangedData.averageHealth, 0);
		updateHealthChart(rangedData.healthSerie);
		updateBigWidget($('#projects-widget'), [rangedData.totalProjects, rangedData.activeProjects], 0);
		updateBigWidget($('#activeIssues-widget'), [rangedData.activeIssues, rangedData.totalIssues], [0,0]);
		updateSimpleWidget($('#commits-widget'), rangedData.commits, 0);
		updateSimpleWidget($('#developers-widget'), rangedData.developers, 0);
		updateSimpleWidget($('#addlines-widget'), rangedData.linesadd, 0);
		updateSimpleWidget($('#rmlines-widget'), rangedData.linesrm, 0);
		speedLabelUpdate(rangedData.speed);
		//updateSpeedGauge(rangedData.speed);
		updateSpeedGraph(rangedData.speedSerie);
		updateSimpleWidget($('#devAverage-widget'), rangedData.averageDevelopers, 0);
		updateMultilineGraph(rangedData.commitSerie, rangedData.linesSerie);

		// TODO create all charts and info boxes
		/*$('#commits-widget').dxChart('instance').option('dataSource', rangedData.commits);
		$('#linesadd-widget').dxChart('instance').option('dataSource', rangedData.linesadd);
		$('#linesrm-widget').dxChart('instance').option('dataSource', rangedData.linesrm);
		$('#doneIssues-widget').dxChart('instance').option('dataSource', rangedData.doneIssues);
		$('#activeIssues-widget').dxChart('instance').option('dataSource', rangedData.activeIssues);
		$('#averageHealth-widget').dxChart('instance').option('dataSource', rangedData.averageHealth);
		$('#projects-widget').dxChart('instance').option('dataSource', rangedData.projects);
		$('#developers-widget').dxChart('instance').option('dataSource', rangedData.developers);
		$('#averageDevelopers-widget').dxChart('instance').option('dataSource', rangedData.averageDevelopers);
		$('#speed-widget').dxChart('instance').option('dataSource', rangedData.speed);*/
	};
	var i = 0;

	randomData4Test = function randomData4Test(start, end) {
		// DEMO values
		var totalrmlines = 0;
		var totaladdlines = 0;

		var randomInt1 = parseInt(Math.random() * 10) + 1;
		var randomInt2 = parseInt(Math.random() * 10) + randomInt1;
		var dayTime = 1000*60*60*24;
		var daysLapse;
		
		if (!start || !end) {
			daysLapse = 23;
			// Update time label
			updateRangeLabel(new Date("October 01, 2014"), new Date("November 18, 2014"));
		} else {
			daysLapse = parseInt((end.getTime() - start.getTime()) / dayTime);
			updateRangeLabel(start, end);
		}

		var speedSerie = [];
		var healthSerie = [];
		var commitSerie = [];
		var linesSerie = [];
		var averageHealth = 0;
		$.map(lines_added_data, function(arg, i) {
			totaladdlines += lines_added_data[i].lines;
			totalrmlines += lines_removed_data[i].lines;
			if (!start || !end || ((start.getTime() <= arg.time.getTime()) && (arg.time.getTime() <= end.getTime()))) {
				// 23 days serie... speed simulation
				var aux = (lines_added_data[i].lines - lines_removed_data[i].lines)/24*60;
				if (aux < 0) {
					aux = 0;
				}
				// speed serie
				speedSerie.push({ time: arg.time, speed: aux});
				// Lo mismo para los datios demo del healthSeries
				healthSerie.push({ time: arg.time, health: healthy_data[i].health * 100});

				// Lo mismo para los datios demo del multiline chart. commits y lines
				commitSerie.push({ time: arg.time, commits: total_commits_serie[i].commits});
				linesSerie.push({ time: arg.time, lines: total_lines_serie[i].lines});

				// Health Average
				averageHealth += healthy_data[i].health * 100;
			}
		});
		averageHealth = parseInt(averageHealth/healthSerie.length);

		rangedData = {
			commits: 30 * daysLapse,
			linesadd: (totaladdlines / 23) * daysLapse,
			linesrm: (totalrmlines / 23) * daysLapse,
			speedSerie: speedSerie,
			doneIssues: daysLapse  * randomInt1,
			activeIssues: randomInt2 * randomInt1,
			totalIssues: randomInt2 * randomInt1 * 6,
			averageHealth: averageHealth,
			healthSerie: healthSerie,
			totalProjects: parseInt(daysLapse * 0.5) * 2,
			activeProjects: parseInt(daysLapse * 0.5) * 2 + 4,
			developers: daysLapse * randomInt2 * (5 + randomInt1),
			averageDevelopers: 5 + randomInt1,
			speed: parseInt((totaladdlines - totalrmlines) / daysLapse),
			commitSerie: commitSerie,
			linesSerie: linesSerie
		};
		 i += 3;
		//console.log("rangedData: " + JSON.stringify(rangedData));
	}
	
	// Resize charts
    $(window).resize(function(e) {
        if(this.resizeTO) clearTimeout(this.resizeTO);
        $(this).trigger('resizing');
        this.resizeTO = setTimeout(function() {
            $(this).trigger('resizeEnd');
        }, 100);
        mainContainerHeigth = $("body")[0].getBoundingClientRect().height - 85;
        console.log("container height1: " + mainContainerHeigth);
        if (timeChartVisible) {
        	mainContainerHeigth -= 280;
        } else {
        	//mainContainerHeigth += 280;
        }
        console.log("page-container height: " + $(".page-container")[0].style.height);
        console.log("new heiht gor page-container " + mainContainerHeigth);
        $(".page-container")[0].style.height = mainContainerHeigth + 'px';
    });

    var timePanelHandler = function timePanelHandler(e) {
        var theIcon1 = $(".timeBarIcon");
        var theIcon2 = theIcon1[1];
        theIcon1 = theIcon1[0];
        if (!timeChartVisible) {
        	console.log('oppening timeline');
            theIcon1.classList.remove("fa-angle-double-down");
            theIcon1.classList.add("fa-angle-double-up");
            theIcon2.classList.remove("fa-angle-double-down");
            theIcon2.classList.add("fa-angle-double-up");
            $(".settings-pane")[0].classList.add('open');
            timeChartVisible = true;
            // Scroll correction
            mainContainerHeigth -= 257;
        } else {
        	console.log('closing timeline');
            theIcon1.classList.add("fa-angle-double-down");
            theIcon1.classList.remove("fa-angle-double-up");
            theIcon2.classList.add("fa-angle-double-down");
            theIcon2.classList.remove("fa-angle-double-up");
            $(".settings-pane")[0].classList.remove('open');
            timeChartVisible = false;
            // Scroll correction
            mainContainerHeigth += 257;
        }
        $(".page-container")[0].style.height = mainContainerHeigth + 'px';
    };

    $("#timeBar")[0].addEventListener("click", timePanelHandler);

	var mainContainerHeigth = $("body")[0].getBoundingClientRect().height;
	mainContainerHeigth = mainContainerHeigth - 280 - 85;
	$(".page-container")[0].style.height = mainContainerHeigth + 'px';

	var healthyChartLoaded = false;
	var speedChartLoaded = false;
	var multilineChartLoaded = false;

	document.addEventListener("chart-loaded", function(e) {
		console.log('chart-loaded: ' + e.detail);
		if (e.detail == "speed-chart") {
			speedChartLoaded = true;
		} else if (e.detail == "healthy-chart") {
			healthyChartLoaded = true;
		} else if (e.detail == "multiline-chart") {
			multilineChartLoaded = true;
		} 
		if(healthyChartLoaded && speedChartLoaded && multilineChartLoaded) {
			healthyChartLoaded = true;
			speedChartLoaded = true;
			multilineChartLoaded = true;
			updateAll();
		}
	});

	// Initial data TODO
	randomData4Test();
	loadGraphs();
	//updateAll();
});