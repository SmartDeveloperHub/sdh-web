{{--
    Sample dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "sdh-framework/framework.widget.common",
    "sdh-framework/framework.widget.heatmap",
    "sdh-framework/framework.widget.piechart",
    "sdh-framework/framework.widget.rangeNv",
    "css!sdh-framework/framework.widget.common",
    "css!sdh-framework/framework.widget.heatmap",
    ]
@stop

@section('html')
    <div class="row">
        <div class="col-sm-4">
            <div id="heatmap1" style="height: 300px"></div>
        </div>
        <div class="col-sm-4">
            <div id="piechart" style="height: 300px"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div id="piechart2" style="height: 500px"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div id="piechart3" style="height: 800px"></div>
        </div>
    </div>
@stop

@section('script')

    context4rangeChart = "context4rangeChart";
    //TEST HEATMAP
    var heatmap_dom = document.getElementById("heatmap1");
    var heatmap_metrics = [{
        id: 'usercommits',
        uid: 'u1',
        max: 1000
    }];
    var heatmap = new framework.widgets.Heatmap(heatmap_dom, heatmap_metrics, [context4rangeChart], null);


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
        },
    ];
    var piechart_configuration = {
        labelFormat: "User: %uid%"
    };
    var piechart = new framework.widgets.PieChart(piechart_dom, piechart_metrics, [context4rangeChart], piechart_configuration);

    //TEST PIECHART2
    var piechart_dom = document.getElementById("piechart2");
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
    var piechart2 = new framework.widgets.PieChart(piechart_dom, piechart_metrics, [context4rangeChart], piechart_configuration);

//TEST PIECHART3
    var piechart_dom = document.getElementById("piechart3");
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
    var piechart3 = new framework.widgets.PieChart(piechart_dom, piechart_metrics, [context4rangeChart], piechart_configuration);

    //TEST rangeNv
    var rangeNv_dom = document.getElementById("fixed-chart");
    var rangeNv_metrics = [
        {
            id: 'usercommits',
            uid: 'u1',
            max: 20,
            aggr: 'avg'
        },
        {
            id: 'usercommits',
            uid: 'u2',
            max: 20,
            aggr: 'avg'
        },
        {
            id: 'usercommits',
            uid: 'u3',
            max: 20,
            aggr: 'max'
        }
    ];
    var rangeNv_configuration = {
        ownContext: context4rangeChart,
        labelFormat: "User: %uid%",
        isArea: false,
        interpolate: 'monotone',
        showFocus: false,
        height : 140,
        duration: 500
    };
    var rangeNv = new framework.widgets.RangeNv(rangeNv_dom, rangeNv_metrics, null, rangeNv_configuration);

@stop