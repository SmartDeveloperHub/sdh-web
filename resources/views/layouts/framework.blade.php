@extends('layouts.base')

@section('scripts')
    @parent
        <script type="application/javascript">
            SDH_API_URL = "{{{ $_ENV['SDH_API'] }}}";
            BASE_DASHBOARD = "organization";
        </script>
@stop

@section('css')
    @parent
        <link rel="stylesheet" href="/sdh-framework/lib/nvd3/nv.d3.min.css">
        <link rel="stylesheet" href="/sdh-framework/style/components.css">
        <link rel="stylesheet" href="/sdh-framework/fonts/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/sdh-framework/fonts/linecons/css/linecons.css">
        <link rel="stylesheet" href="/sdh-framework/fonts/octicons/css/octicons.css">
        <!--link rel="stylesheet" href="/assets/css/header/header-fixed.css"-->
@stop