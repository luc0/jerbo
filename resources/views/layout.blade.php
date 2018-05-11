<!DOCTYPE html>
<html lang="en">

	@include('includes.head')

	<body>

			@include('includes.cabecera')

			<div class="container">
				@yield('contenido')
			</div>

			@include ('bind_javascript')
			@include ('includes.assets')
			@yield('custom_assets')

			@include('includes.footer')

	</body>
</html>
