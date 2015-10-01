@extends('layouts.template')

@section('require')
    [
    /*
        Fill this Javascript array with a list of paths to the resources that you whant to use in the dashboard.

        Each element of this array must be an string that represents a file path starting from the "public" folder.
        Paths should not finish with the extension of the file (e.g: js, css). By default the file is considered to be
        a Javascript file. In case you whant to add a CSS file, just start the path with "css!".

        For example, the following array whould load the counterbox widget and a css file for this dashboard:
        [
            "sdh-framework/framework.widget.counterbox",
            "css!assets/css/dashboards/empty-dashboard"
        ]
    */
    ]
@stop

@section('html')
    <!-- Write your HTML code here -->
@stop

@section('script')
    function _() {
        /*
            Write your Javascript code here.
            Note: do not add the HTML script tags.
        */
    }
@stop
