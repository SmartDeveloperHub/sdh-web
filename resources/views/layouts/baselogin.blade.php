<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="icon" type="image/png" href="/assets/images/favicon.png" />

        <link rel="stylesheet" href="/assets/css/bootstrap.css">
        <link rel="stylesheet" href="/assets/css/core.css">
        <link rel="stylesheet" href="/sdh-framework/lib/nvd3/nv.d3.min.css">
        <link rel="stylesheet" href="/sdh-framework/style/components.css">
        <link rel="stylesheet" href="/sdh-framework/fonts/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/sdh-framework/fonts/linecons/css/linecons.css">
        <link rel="stylesheet" href="/sdh-framework/fonts/octicons/css/octicons.css">
        @yield('css')
        <script data-main="/assets/js/loginLoader" src="/assets/js/requirejs/require.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!--script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script-->
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