<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="icon" type="image/png" href="/assets/images/favicon.png" />

        <link rel="stylesheet" href="/vendor/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/core.css">
        @yield('css')
        <script data-main="{{$requirejsMain}}" src="/vendor/requirejs/require.js"></script>
    </head>
    <body class="page-body light hidd">
        @yield('header')
        @yield('body')
        @yield('scripts')
    </body>
</html>