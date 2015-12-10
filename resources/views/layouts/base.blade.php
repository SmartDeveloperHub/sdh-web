<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="icon" type="image/png" href="{{$PUBLIC_PATH}}assets/images/favicon.png" />

        <link rel="stylesheet" href="{{$PUBLIC_PATH}}vendor/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{$PUBLIC_PATH}}assets/css/core.css">
        @yield('css')
        <script>
            PUBLIC_PATH = "{{$PUBLIC_PATH}}";
            ENV = "{{getenv('APP_ENV')}}";
        </script>
        <script src="{{$PUBLIC_PATH}}vendor/requirejs/require.js"></script>
        <script>
            require(['{{$PUBLIC_PATH}}assets/js/require-config.js'], function() {
                require(['{{$requirejsMain}}']);
            });
        </script>
    </head>
    <body class="page-body light hidd">
        @yield('header')
        @yield('body')
        @yield('scripts')
    </body>
</html>