<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jquery.filer.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet">
	<!--admin-->
	<link href="{{ asset('/css/inspinia.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/admin.css?v=1') }}" rel="stylesheet">
	<link href="{{ asset('/css/dropzone.min.css') }}" rel="stylesheet">


	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Admin</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Ver página</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<!--<li><a href="{{ url('/auth/login') }}">Login</a></li>-->
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Cerrar sesión</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('admin_content')
	@include ('bind_javascript')

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{ URL::asset('js/underscore.min.js') }}"></script>
	<!-- Admin -->
	<script type="text/javascript" src="{{ URL::asset('js/inspinia.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/jquery.metisMenu.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/jquery.slimScroll.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/jquery.filer.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/formulario.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/dropzone.min.js') }}"></script>
	
	<script type="text/javascript" src="{{ URL::asset('js/tinymce.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/admin.js') }}"></script>


</body>
</html>
