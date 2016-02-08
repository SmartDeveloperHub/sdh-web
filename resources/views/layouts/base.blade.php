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
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-68944437-1', 'auto');
          ga('send', 'pageview');

        </script>
    </head>
    <body class="page-body light hidd">
        @yield('header')
        @yield('body')
        @yield('scripts')
    </body>
</html>