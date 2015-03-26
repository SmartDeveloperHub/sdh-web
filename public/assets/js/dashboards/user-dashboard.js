jQuery(document).ready(function($)
{
	var chartsCreated = false;
	var issuesChart, languagesChart, heatMapChart, radarChart;
    var rangeStart, rangeEnd;
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - -  - - - - 

	function getRandom(min, max) {
		return Math.random() * (max - min) + min;
	}
	
	var gaugesPalette = ['#8dc63f', '#40bbea', '#ffba00', '#cc3f44'];
		
	// Data Sources for all charts
	
	var linesDeleted = [
		{ time: new Date("October 02, 2014 01:00:00"), lines: 175 },
		{ time: new Date("October 02, 2014 02:00:00"), lines: 55 },
		{ time: new Date("October 02, 2014 03:00:00"), lines: 249 },
		{ time: new Date("October 02, 2014 04:00:00"), lines: 87 },
		{ time: new Date("October 02, 2014 05:00:00"), lines: 258 },
		{ time: new Date("October 02, 2014 06:00:00"), lines: 20 },
		{ time: new Date("October 02, 2014 07:00:00"), lines: 139 },
		{ time: new Date("October 02, 2014 08:00:00"), lines: 26 },
		{ time: new Date("October 02, 2014 09:00:00"), lines: 87 },
		{ time: new Date("October 02, 2014 10:00:00"), lines: 69 },
		{ time: new Date("October 02, 2014 11:00:00"), lines: 40 },
		{ time: new Date("October 02, 2014 12:00:00"), lines: 157 },
		{ time: new Date("October 02, 2014 13:00:00"), lines: 48 },
		{ time: new Date("October 02, 2014 14:00:00"), lines: 273 },
		{ time: new Date("October 02, 2014 15:00:00"), lines: 28 },
		{ time: new Date("October 02, 2014 16:00:00"), lines: 225 },
		{ time: new Date("October 02, 2014 17:00:00"), lines: 73 },
		{ time: new Date("October 02, 2014 18:00:00"), lines: 127 },
		{ time: new Date("October 02, 2014 19:00:00"), lines: 63 },
		{ time: new Date("October 02, 2014 20:00:00"), lines: 35 },
		{ time: new Date("October 02, 2014 21:00:00"), lines: 100 },
		{ time: new Date("October 02, 2014 22:00:00"), lines: 61 },
		{ time: new Date("October 02, 2014 23:00:00"), lines: 339 },
		{ time: new Date("October 03, 2014 00:00:00"), lines: 159 },
	];
	
	var linesAdded = [
		{ time: new Date("October 02, 2014 01:00:00"), lines: 559 },
		{ time: new Date("October 02, 2014 02:00:00"), lines: 692 },
		{ time: new Date("October 02, 2014 03:00:00"), lines: 534 },
		{ time: new Date("October 02, 2014 04:00:00"), lines: 607 },
		{ time: new Date("October 02, 2014 05:00:00"), lines: 50 },
		{ time: new Date("October 02, 2014 06:00:00"), lines: 772 },
		{ time: new Date("October 02, 2014 07:00:00"), lines: 30 },
		{ time: new Date("October 02, 2014 08:00:00"), lines: 108 },
		{ time: new Date("October 02, 2014 09:00:00"), lines: 440 },
		{ time: new Date("October 02, 2014 10:00:00"), lines: 371 },
		{ time: new Date("October 02, 2014 11:00:00"), lines: 209 },
		{ time: new Date("October 02, 2014 12:00:00"), lines: 378 },
		{ time: new Date("October 02, 2014 13:00:00"), lines: 308 },
		{ time: new Date("October 02, 2014 14:00:00"), lines: 585 },
		{ time: new Date("October 02, 2014 15:00:00"), lines: 96 },
		{ time: new Date("October 02, 2014 16:00:00"), lines: 473 },
		{ time: new Date("October 02, 2014 17:00:00"), lines: 864 },
		{ time: new Date("October 02, 2014 18:00:00"), lines: 895 },
		{ time: new Date("October 02, 2014 19:00:00"), lines: 610 },
		{ time: new Date("October 02, 2014 20:00:00"), lines: 893 },
		{ time: new Date("October 02, 2014 21:00:00"), lines: 753 },
		{ time: new Date("October 02, 2014 22:00:00"), lines: 209 },
		{ time: new Date("October 02, 2014 23:00:00"), lines: 555 },
		{ time: new Date("October 03, 2014 00:00:00"), lines: 144 },
	];
	
	var languages = [
		{language: "PHP", percent: .35},
		{language: "Js", percent: .25},
		{language: "Java", percent: .09},
		{language: "Python", percent: .2},
		{language: "Clojure", percent: .1},
		{language: "Chef", percent: .01}
	];

    var assignedProjectsList = [
        {id: "1231231", name: "Golang", url:"project-dashboard/1231231"},
        {id: "1231551", name: "BankCenter", url:"project-dashboard/1231551"},
    ];

    var selfProjectsList = [
        {id: "1231931", name: "VideoPlayer", url:"project-dashboard/1231931"},
        {id: "1243341", name: "BankTestApp", url:"project-dashboard/1243341"},
    ];
	
	var contributedProjectsList = [
		{id: "56732345", name: "Hadoop", commits: 15, issues: 3, url:"project-dashboard/56732345"},
		{id: "36434534", name: "Symfony", commits: 13, issues: 2, url:"project-dashboard/36434534"},
		{id: "567567345", name: "Laravel", commits: 3, issues: 0, url:"project-dashboard/567567345"},
		{id: "4566734", name: "HipHop", commits: 7, issues: 1, url:"project-dashboard/4566734"},
		{id: "565535", name: "Yii", commits: 4, issues: 6, url:"project-dashboard/565535"},
		{id: "7899753", name: "Eclipse", commits: 14, issues: 6, url:"project-dashboard/7899753"},
		{id: "2326568", name: "Nginx", commits: 37, issues: 4, url:"project-dashboard/2326568"},
	]; 
	
	var issuesByStatus = [];
	for(var i in linesDeleted){
		var time = linesDeleted[i].time;
		issuesByStatus.push({
			time: time,
			pending: getRandom(0,20),
			ongoing: getRandom(0,20),
			done: getRandom(0,20) 
		});
	}
	
	var commitsHeatMap = [];
	for(var d = 0; d < 365; d++){
		commitsHeatMap.push({date: new Date(new Date().getTime() + d * 24 * 60 * 60 * 1000), value: getRandom(0,50)})
	}

    var userSkills = {
        labels: [],
        longLabels: ["Experience", "Coding speed", "Work quality", "Social appreciation", "Cooperation"],
        shortLabels: ["Experience", "Speed", "Quality", "Appreciation", "Cooperation"],
        datasets: [{
            fillColor: "rgba(22,22,220,0.2)",
            strokeColor: "rgba(22,22,220,0.5)",
            pointColor: "rgba(22,22,220,0.75)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(22,22,220,1)",
            data: [38, 60, 20, 90, 74]
        }]
    };


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// - - - - - - -  A U X I L I A R    F U N C T I O N S - - - - - - - - - - - - - 
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
	var adaptDataToIssuesChart = function(issuesData){
		var newData = [];
		var lavels = ['done', 'ongoing', 'pending'];
		for(var i in lavels){
			var lavel = lavels[i];
			var points = [];
			for(var j in issuesData){
				points.push({ x: issuesData[j]['time'].getTime(), y: issuesData[j][lavel] });
			}
			newData.push({ key: lavel, values: points});
		}
		
		return newData;
	};
	
	var getTimeFormatFromTimeRange = function(first, last){
		if(first.getTime() + 24 * 60 * 60 * 1000 > last) { //Range is less than a day
			return "%H:%M";
		} else if(first.getTime() + 31 * 24 * 60 * 60 * 1000 > last) { //Range is less than a month
			return "%d/%m";
		} else {
			return "%d/%m/%Y";
		}
	};
	
	/**
	 * This method updates a counter widget.
	 * @param element Jquery element or selector string
	 * @param newValue New value of the widget content
	 * @param decimals Number of decimals of the new value
	 */
	var updateWidget = function(element, newValue, decimals){
			
		var options = {
			useEasing : true, 
			useGrouping : true, 
			separator : ',', 
			decimal : '.', 
			prefix : $(element).data('prefix') ,
			suffix : $(element).data('suffix')
		};
		
		var cntr = new countUp($(element).find($(element).data('count'))[0], $(element).data('to'), newValue, decimals, $(element).data('duration'), options);
		cntr.start();
		$(element).data('to', newValue);
		
	};
	
	
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// - - - - - - - - - - - -  M A I N    C O D E - - - - - - - - - - - - - - - - -
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
	var dashboardData = {
		totalCommits: 0,
		totalCreatedIssues: 0,
		totalSolvedIssues: 0,
		contributedProjects:0,
		linesDeletedHistorical: linesDeleted,
		linesAddedHistorical: linesAdded,
		languages: languages,
		contributedProjectsList: [],
		assignedProjectsList: assignedProjectsList,
        selfProjectsList: selfProjectsList,
		badges: [],
		issuesByStatus: issuesByStatus,
		issuesScale: "%c",
		commitsHeatMap: commitsHeatMap,
        userSkills: userSkills
	};
	
	/**
	 * This method parses the data received and stores it in "dashboardData"
	 */
	var parseData = function parseData(data) {
		
		dashboardData.totalCommits = Math.floor(getRandom(linesDeleted.length, linesDeleted.length*3));
		dashboardData.totalCreatedIssues = Math.floor(getRandom(5,50));
		dashboardData.totalSolvedIssues = Math.floor(getRandom(5,50));
		dashboardData.contributedProjects = Math.floor(getRandom(1,contributedProjectsList.length));
		
		dashboardData.contributedProjectsList = [];
		for(var i = 0; i < dashboardData.contributedProjects; i++)
			dashboardData.contributedProjectsList.push(contributedProjectsList[i]);
		
		var restPercent = 1.0;
		for(var i in dashboardData.languages){
			var percent = getRandom(0.0, restPercent);
			dashboardData.languages[i].percent = percent;
			restPercent -= percent;
		}
		
		if(chartsCreated){
			dashboardData.issuesByStatus = [];
			for(var i in issuesByStatus) {
				var data = issuesByStatus[i];
				if(rangeStart <= data.time && rangeEnd >= data.time) {
					dashboardData.issuesByStatus.push(data);
				}
			}
		}
		
		//Calculate the time scale for the issues chart
		if(dashboardData.issuesByStatus.length > 0){
			var first = dashboardData.issuesByStatus[0].time;
			var last = dashboardData.issuesByStatus[dashboardData.issuesByStatus.length - 1].time;
			dashboardData.issuesScale = getTimeFormatFromTimeRange(first, last);
		}
		
		if(chartsCreated){
			var n = getRandom(50, 365);
			dashboardData.commitsHeatMap = [];
			for(var i = 0; i < n; i++){
				dashboardData.commitsHeatMap.push(commitsHeatMap[i]);
			}
		}
		
		
	};
	
	/**
	 * This method updates all the charts with the dashboardData
	 */
	var updateChartsData = function updateChartsData() {
		
		updateWidget("#commits-counter", dashboardData.totalCommits, 0);
		updateWidget("#created-issues-counter", dashboardData.totalCreatedIssues, 0);
		updateWidget("#resolved-issues-counter", dashboardData.totalSolvedIssues, 0);
		
		updateContributedProjects();
		
		d3.select('#languages-chart').datum(dashboardData.languages);
		d3.select('#issues-chart svg').datum(adaptDataToIssuesChart(dashboardData.issuesByStatus));
		
		issuesChart.update();
		languagesChart.update();
		
		heatMapChart.updateData(dashboardData.commitsHeatMap);
		
	};
	
	var updateContributedProjects = function updateContributedProjects() {
        var table = $("#contributed-projects-table");
        table.empty();
		
		for(var i in dashboardData.contributedProjectsList){
			var project = dashboardData.contributedProjectsList[i];
            table.append("<tr><th>" + project.name + "</th><td>" + project.commits + "</td></tr>");
		}
		
		updateWidget("#contributed-projects-counter", dashboardData.contributedProjects, 0);
		
	};

    var resizeRadarChartLabels = function resizeRadarChartLabels(){
        //Clear the array
        radarChart.scale.labels.length = 0;

        var labels = null;
        if($("#radar-chart").width() < 200){
            labels = dashboardData.userSkills.shortLabels;
        } else {
            labels = dashboardData.userSkills.longLabels;
        }

        for(var i in labels){
            radarChart.scale.labels[i] = labels[i];
            //radarChart.labels.push(labels);
        }

        radarChart.update();
    };
	
	var createCharts = function createCharts() {

        //List of own projects
        var self_project_list = $('#self-projects-list');
        for(var i in dashboardData.selfProjectsList){
            var projectInfo = dashboardData.selfProjectsList[i];
            $('<a/>')
                .attr('href', projectInfo.url)
                .addClass("list-group-item")
                .text(projectInfo.name)
                .appendTo(self_project_list);
        }

        //List of assigned projects
        var assigned_project_list = $('#assigned-projects-list');
        for(var i in dashboardData.assignedProjectsList){
            var projectInfo = dashboardData.assignedProjectsList[i];
            $('<a/>')
                .attr('href', projectInfo.url)
                .addClass("list-group-item")
                .text(projectInfo.name)
                .appendTo(assigned_project_list);
        }
		
		// Combine charts for filtering, grouped by time
		var all_data_sources = [];
		
		$.map(dashboardData.linesAddedHistorical, function(arg, i)
		{
			all_data_sources.push({
				timeOfCommit: 			arg.time,
				linesAdded: 			dashboardData.linesAddedHistorical[i].lines,
				linesDeleted: 			-dashboardData.linesDeletedHistorical[i].lines
			});
		});


        var generalActivitySources = [
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
        $.map(dashboardData.linesAddedHistorical, function(arg, i) {
            generalActivitySources[0].values.push(
                {date: dashboardData.linesAddedHistorical[i].time, lines: dashboardData.linesAddedHistorical[i].lines}
            );
        });
        $.map(dashboardData.linesDeletedHistorical, function(arg, i) {
            generalActivitySources[1].values.push(
                {date: dashboardData.linesDeletedHistorical[i].time, lines: -dashboardData.linesDeletedHistorical[i].lines}
            );
        });

        // Load Range Chart
        activityChart(generalActivitySources, "range-chart", function(start, end){
            rangeStart = start;
            rangeEnd = end;

            //TODO: make http request

            onDataReady();
        });


		
		//Update widgets with initial values
		updateWidget("#commits-counter", dashboardData.totalCommits, 0);
		updateWidget("#created-issues-counter", dashboardData.totalCreatedIssues, 0);
		updateWidget("#resolved-issues-counter", dashboardData.totalSolvedIssues, 0);
		
		updateContributedProjects();
		
		nv.addGraph({
			generate: function() {
			
				var width = $("#languages-chart").parent()[0].getBoundingClientRect().width,
					height = $("#languages-chart").parent()[0].getBoundingClientRect().height;
						
				languagesChart = nv.models.pieChart()
					.x(function(d) { 
						return d.language;
					})
					.y(function(d) { 
						return d.percent;
					})
					.donut(true)
					.width(width)
					.height(height)
					.padAngle(.08)
					.cornerRadius(5)
					.growOnHover(false); 

				languagesChart.pie.donutLabelsOutside(true).donut(true);

				d3.select("#languages-chart")
					.datum(dashboardData.languages)
					.transition().duration(0)
					.call(languagesChart);

				return languagesChart;

			},
			callback: function(graph) {
				nv.utils.windowResize(function() {
					var width = $("#languages-chart").parent()[0].getBoundingClientRect().width;
					var height = $("#languages-chart").parent()[0].getBoundingClientRect().height;
					graph.width(width).height(height);

					d3.select('#languages-chart')
						.attr('width', width)
						.attr('height', height)
						.transition().duration(0)
						.call(graph);

				});
			}
		});

		
		
		$("#cont-proj-id").dataTable({
			paging: false,
			searching: false,
			info: false,
			"order": [[ 1, "desc" ]]
		});
		
		//Issues chart
		nv.addGraph({
			generate: function() {
				var width = document.getElementById("issues-chart").getBoundingClientRect().width,
					height = document.getElementById("issues-chart").getBoundingClientRect().height;
				
				issuesChart = nv.models.multiBarChart()
					.width(width)
					.height(height)
					.stacked(true)
					;

				issuesChart.dispatch.on('renderEnd', function(){
					console.log('Render Complete');
				});
				
				issuesChart.xAxis.tickFormat(function(d) { 
					return d3.time.format(dashboardData.issuesScale)(new Date(d)); 
				});
				
				issuesChart.color(["#68b828","#ffd700","#40bbea"]);
				issuesChart.showControls(false);

				var svg = d3.select('#issues-chart svg').datum(adaptDataToIssuesChart(dashboardData.issuesByStatus));
				console.log('calling chart');
				svg.transition().duration(0).call(issuesChart);

				return issuesChart;
			},
			callback: function(graph) {
				nv.utils.windowResize(function() {
					var width = document.getElementById("issues-chart").getBoundingClientRect().width;
					var height = document.getElementById("issues-chart").getBoundingClientRect().height;
					graph.width(width).height(height);

					d3.select('#issues-chart svg')
						.attr('width', width)
						.attr('height', height)
						.transition().duration(0)
						.call(graph);

				});
			}
		});

        // Create the heat map with commits
		heatMapChart = new HeatMapChart($('#heatmap')[0], dashboardData.commitsHeatMap);
		heatMapChart.paint();


        //User skills radar chart
        var radar = $("#radar-chart");
        var ctx = radar.get(0).getContext("2d");
        radar.attr('width', radar.parent().width());
        Chart.defaults.global.responsive = true;

        radarChart = new Chart(ctx).Radar(dashboardData.userSkills, {
            scaleOverride : true,
            scaleSteps : 4,
            scaleStepWidth : 25,
            scaleStartValue : 0
        });

        //Set the radar chart labels given the size of the chart
        resizeRadarChartLabels();

        $(window).resize(function(){
            console.log("resize");
            resizeRadarChartLabels();
        });



        chartsCreated = true;
		
	};
	
	/**
	 * This method is called when all the data has been received
	 */
	var onDataReady = function onDataReady(data) {
		
		//Fill the dashboardData
		parseData(data);
		
		//Create the charts or update them
		if(chartsCreated) {
			updateChartsData();
		} else {
			createCharts();
		}
		
	};
	
	//TODO
	onDataReady();
});