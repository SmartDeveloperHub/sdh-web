<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="icon" type="image/png" href="{{$PUBLIC_PATH}}assets/images/favicon.png" />

        <link rel="stylesheet" href="{{$PUBLIC_PATH}}vendor/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{$PUBLIC_PATH}}assets/css/core.css">
        <link rel="stylesheet" href="{{$PUBLIC_PATH}}vendor/nvd3/build/nv.d3.min.css">
        <link rel="stylesheet" href="{{$PUBLIC_PATH}}vendor/sdh-framework/style/components.css">
        <link rel="stylesheet" href="{{$PUBLIC_PATH}}vendor/sdh-framework/fonts/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{$PUBLIC_PATH}}vendor/sdh-framework/fonts/linecons/css/linecons.css">
        <link rel="stylesheet" href="{{$PUBLIC_PATH}}vendor/sdh-framework/fonts/octicons/css/octicons.css">
        @yield('css')
        <script>
            PUBLIC_PATH = "{{$PUBLIC_PATH}}";
        </script>
        <script src="{{$PUBLIC_PATH}}vendor/requirejs/require.js"></script>
        <script>
            require(['{{$PUBLIC_PATH}}assets/js/require-config.js'], function() {
                require(['{{$PUBLIC_PATH}}assets/js/loginLoader.js']);
            });
        </script>
    </head>
    <body class="page-body light hidd">
        @yield('header')
        @yield('body')
        <script type="application/javascript">
            SDH_API_URL = "{{{ $_ENV['SDH_API'] }}}";
            SDH_API_KEY = "";
            BASE_DASHBOARD = "organization";
        </script>
        @yield('scripts')
    </body>
</html>