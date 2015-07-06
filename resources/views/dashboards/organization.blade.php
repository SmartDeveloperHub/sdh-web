{{--
    Organization dashboard
 --}}
@extends('layouts.template')

@section('require')
    [
    "https://cdn.rawgit.com/matthieua/WOW/master/dist/wow.js",
    "css!https://cdn.rawgit.com/daneden/animate.css/master/animate.css",
    "css!assets/css/dashboards/organization-dashboard",
    ]
@stop

@section('html')
    <div class="initial-section gradient-2 white wow pulse animated" data-wow-duration="3s" data-wow-iteration="infinite" data-wow-delay="300ms">
        <div class="container">
            <div class="row">
                <div class="center-block p4 initial-section-content">
                    <h2 class="h1 thin h-has-icon roboto page-title">Smart Developer Hub</h2>
                    <p class="h4 page-title-content">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="feature-section gradient-1 blue">
        <div class="container">
            <div class="row">
                <div class="col col-sm-6 wow animated fadeInLeft animated" data-wow-duration="1.1s" data-wow-delay="0.2s">
                    <div class="screenshot ss-left">
                        <img class="feature-section-img 2x radius-img" height="100%" width="100%" src="/assets/images/sdh-architecture.png">
                    </div>
                </div>
                <div class="col col-md-4 col-sm-6 wow animated fadeInUp animated" data-wow-duration="1.1s">
                    <div class="left p4 feature">
                        <h2 class="h1 thin  h-has-icon">A Linked Data Platform</h2>
                        <p class="lighter-gray h4">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')

$(".timeMidBox").remove();
$("#timeBar").remove();

@stop