@extends('layouts.base')

@section('scripts')
    @parent
    <script type="application/javascript">
        SDH_API_URL = "{{{ $_ENV['SDH_API'] }}}";
        BASE_DASHBOARD = "test-template";
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
                        <img src="assets/images/logo_bg.png" alt=""/>
                    </a>
                    <div class="logotextbox">
                        <span  class="logotext">
                            Smart Developer Hub
                        </span>
                    </div>
                    <div class="logotextbox2">
                        <span  class="logotext">
                            S.D.H
                        </span>
                    </div>
                </div>
                <div id="headermid" class="headcomp">
                    <span id="htitle">Repositories</span>
                    <span id="hsubtitle">Jenkins</span>
                </div>
                <div id="headerright" class="headcomp">
                    <div class="control">
                        <div id="buttonbox" class="headcomp">
                            <a class="headbutton mail fa-envelope-o"></a>
                            <a class="headbutton settings fa-cog"></a>
                        </div>
                        <div id="avatarbox" class="headcomp">
                            <a class="useravatar fa-user-secret"></a>
                        </div>
                        <div id="userinfobox" class="headcomp">
                            <span id="usernick">fserena</span>
                            <span id="username">Fernando Serena</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>
    <div class="settings-pane open">
        <div id="timeBar">
            <div id="infobar">
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
            <div id="timeControler">
                <div class="timeMidBox">
                    <i id="timeBarIcon fa-user-secret"></i>
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
        </div>
    </div>
{{-- End of header section --}}
@stop

@section('body')
<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

    <div class="main-content"></div>

</div>
<div id="template-exec" style="display: none";></div>
<div id="loading" class="hidden">
    <div class="loading-info text-center">
        <span class="loading-text"></span>
        <i class="loading-icon fa fa-spinner fa-pulse fa-3x"></i>
    </div>

</div>

{{-- End of body section --}}
@stop