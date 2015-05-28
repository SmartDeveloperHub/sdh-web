@extends('layouts.template')

@section('html')
    <div class="row">
        <div class="col-sm-4">
            <div id="heatmap1" style="height: 300px"></div>
        </div>
        <div class="col-sm-4">
            <div id="piechart" style="height: 300px"></div>
        </div>
    </div>
@stop
"framework.widgets.heatmap", "http://cdn.google.es/"
@section('script')
    framework.ready(function() {
        console.log("Framework ready");

        //TEST HEATMAP
        var heatmap_dom = document.getElementById("heatmap1");
        var heatmap_metrics = [{
            id: 'usercommits',
            uid: 'u1',
            max: 1000
        }];
        var heatmap = new framework.widgets.Heatmap(heatmap_dom, heatmap_metrics, null, null);


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
        var piechart = new framework.widgets.PieChart(piechart_dom, piechart_metrics, null, piechart_configuration);

    });
@stop