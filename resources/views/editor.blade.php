@extends('layouts.base')

<?php $requirejsMain = "/assets/js/editor"; ?>

@section('scripts')
    @parent
    <script type="application/javascript">
    </script>
@stop

@section('css')
    @parent
    <link rel="stylesheet" href="assets/css/editor.css">
    <link rel="stylesheet" href="vendor/sdh-framework/fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/sdh-framework/fonts/linecons/css/linecons.css">
    <link rel="stylesheet" href="vendor/sdh-framework/fonts/octicons/css/octicons.css">
    <link rel="stylesheet" href="assets/css/header/header-fixed.css">
@stop

@section('header')
    @parent
    @include('layouts.header')
@stop

@section('body')
<div class="page-container" style="display: none;">
    <div class="main-content">
        <div class="row">
            <div class="grid-stack">
                <div class="grid-stack-item"
                     data-gs-x="0" data-gs-y="0"
                     data-gs-width="4" data-gs-height="2">
                    <div class="grid-stack-item-content">1</div>
                </div>
                <div class="grid-stack-item"
                     data-gs-x="4" data-gs-y="0"
                     data-gs-width="4" data-gs-height="4">
                    <div class="grid-stack-item-content">2</div>
                </div>
            </div>
        </div>


    </div>
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
