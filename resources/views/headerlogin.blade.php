<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SDH</title>

	<link href="/assets/css/app.css" rel="stylesheet">
	<link href="/assets/css/header/header-login.css" rel="stylesheet">
    <link href="/assets/css/login/login.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<!-- Fonts -->
	<!--link href='//fonts.googleapis.com/css?family=Roboto:400' rel='stylesheet' type='text/css'-->
    <link href="/assets/css/robotofont.css" rel="stylesheet">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="page-body light">
	<header id= "headerGeneric" class="header-fixed">
        <div class="header-first-bar">
            <div class="header-limiter">
                <div id="headerleft" class="headcomp">
                    <a href="#" class="logo">
                        <div id="myLogo"></div>
                    </a>
                </div>
                <div id="headermid" class="headcomp">
                    <span id="htitle"></span>
                    <span id="hsubtitle"></span>
                </div>
                <div id="headerright" class="headcomp">
                    <button id="loginButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Login</button>
                </div>
            </div>
        </div>
    </header>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

            <div id="loginForm" class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="tooltip" title="Please, fill the form and login SDH">Login</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="/auth/login">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">Username</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="username" value="{{ old('username') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

      </div>
    </div>
	@yield('content')
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            /*$("#loginbutton").click(function() {
                $('html, body').animate({
                    scrollTop: $("#arqPanel")[0].scrollHeight
                }, 600);
            });*/
            $("#loginbutton").click(function() {
                $('html, body').animate({
                    scrollTop: $("#arqPanel")[0].scrollHeight
                }, 600);
            });
        });
    </script>
	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>
