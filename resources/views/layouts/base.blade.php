<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>

        <link rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/core.css">
        @yield('css')

        <script src="assets/js/jquery/jquery-2.1.3.min.js"></script>
        <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    </head>
    <body class="page-body">
    @yield('body')
    @yield('scripts')
    </body>
</html>