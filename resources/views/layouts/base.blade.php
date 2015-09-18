<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="icon" type="image/png" href="/assets/images/favicon.png" />

        <link rel="stylesheet" href="/assets/css/bootstrap.css">
        <link rel="stylesheet" href="/assets/css/core.css">
        @yield('css')
        <script data-main="/assets/js/dashboardLoader" src="/assets/js/requirejs/require.js"></script>
    </head>
    <body class="page-body light hidd">
        @yield('header')
        @yield('body')
        @yield('scripts')
    </body>
</html>