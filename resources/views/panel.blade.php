@extends('layouts.base')

@section('scripts')
    @parent
    <script type="application/javascript">
        SDH_API_URL = "{{{ $_ENV['SDH_API'] }}}";
        SDH_API_KEY = "{{ Session::get('SdhApiToken') }}";
        BASE_DASHBOARD = "organization";
        USER_ID = "{{ Auth::user()->id }}";
    </script>
@stop

@section('css')
    @parent
    <link rel="stylesheet" href="sdh-framework/lib/nvd3/nv.d3.min.css">
    <link rel="stylesheet" href="sdh-framework/style/components.css">
    <link rel="stylesheet" href="sdh-framework/fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="sdh-framework/fonts/linecons/css/linecons.css">
    <link rel="stylesheet" href="sdh-framework/fonts/octicons/css/octicons.css">
    <link rel="stylesheet" href="assets/css/header/header-fixed.css">
@stop

@section('header')
    @parent
    <header class="header-fixed">
        <div class="header-first-bar">
            <div class="header-limiter">
                <div id="headerleft" class="headcomp">
                    <a href="#" class="logo">
                        <div id="myLogo"></div>
                    </a>
                </div>
                <div id="headermid" class="headcomp">
                    <span id="htitle"></span>
                    <span id="hsubtitle"></span>
                </div>
                <div id="headerright" class="headcomp">
                    <div class="control">
                        <div id="buttonbox" class="headcomp">
                            <a class="headbutton fa-cog"></a>
                            <a class="headbutton fa-sign-out" href="/auth/logout"></a>
                        </div>
                        <div id="avatarbox" class="headcomp">
                            <a class="useravatar fa-user-secret"></a>
                        </div>
                        <div id="userinfobox" class="headcomp">
                            <span id="usernick">{{ Auth::user()->username }}</span>
                            <span id="username">{{ Auth::user()->name }} {{ Auth::user()->surname }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
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
<footer class="footer-container" style="display: none;">
    <div class="row">
        <div class="col-sm-8 footer-text">
            <p>Copyright &copy; 2015 Center for Open Middleware, Universidad Politécnica de Madrid</p>
            <p>Licensed under the Apache License, Version 2.0</p>
            <p>The Center for Open Middleware is a collaboration between UPM and Banco Santander</p>
        </div>
        <div class="col-sm-2 footer-img">
            <a target="blank_" href="http://www.centeropenmiddleware.com/">
                <img src="assets/images/com.png" style="width:100%; max-width:80px; margin-left:0px">
            </a>
        </div>
        <div class="col-sm-2 footer-img">
            <a target="blank_" href="http://www.upm.es/">
                <img src="assets/images/upm.png" style="width:100%; max-width:70px; margin-left:0px">
            </a>
        </div>
    </div>
</footer>
<div id="template-exec" style="display: none;"></div>

{{-- End of body section --}}
@stop
