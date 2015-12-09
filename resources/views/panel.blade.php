@extends('layouts.base')

<?php $requirejsMain = "/assets/js/dashboardLoader.js"; ?>

@section('scripts')
    @parent
    <script type="application/javascript">
        SDH_API_URL = "{{{ $_ENV['SDH_API'] }}}";
        SDH_API_KEY = "{{ Session::get('SdhApiToken') }}";
        BASE_DASHBOARD = "organization";
        USER_ID = "{{ Auth::user()->id }}";
        ORGANIZATION_ID = "{{ array_keys(Auth::user()->positions)[0] }}";
    </script>
@stop

@section('css')
    @parent
    <link rel="stylesheet" href="/vendor/nvd3/build/nv.d3.min.css">
    <link rel="stylesheet" href="/vendor/sdh-framework/style/components.css">
    <link rel="stylesheet" href="/vendor/sdh-framework/fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/sdh-framework/fonts/linecons/css/linecons.css">
    <link rel="stylesheet" href="/vendor/sdh-framework/fonts/octicons/css/octicons.css">
    <link rel="stylesheet" href="/assets/css/header/header-fixed.css">
@stop

@section('header')
    @parent
    @include('layouts.header')
    <div class="settings-pane">
        <div id="timeBar" style="display: none;">
            <div class="infobar">
                <div class="infoBox timeFrom">
                    <span class= "fa-calendar"></span>
                    <span id="fromLabel"></span>
                </div>
                <div class="infoBox since">
                    <span id="sinceLabel"></span>
                </div>
                <div class="infoBox timeTo">
                    <span id="toLabel"></span>
                    <span class= "fa-calendar"></span>
                </div>
            </div>
        </div>
        <div class="row" id="floatingRow">
            <div class="col-sm-12 timePanel">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="fixed-chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="timeBarDown">
           <div id="timeControler" style="display: none;">
                <div class="timeMidBox">
                    <span id="timeBarIcon" class="fa-caret-down"></span>
                </div>
            </div>
        </div>
    </div>
{{-- End of header section --}}
@stop

@section('body')
<div class="page-container" style="display: none;">
    <div class="main-content"></div>
    <div id="loading" class="hidden">
        <div class="loading-protection"></div>
        <div class="loading-white">
            <div class="loading-info text-center">
                <i class="loading-icon fa fa-spin">
                    <img height="158" width="150" src="assets/images/sdh_400ppp_RGB_imagotipo_small.png" />
                </i>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
<div id="template-exec" style="display: none;"></div>

{{-- End of body section --}}
@stop
