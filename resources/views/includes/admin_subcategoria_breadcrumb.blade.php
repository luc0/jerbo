<div class="row">
	<div class="col-sm-9 col-md-9 col-md-offset-3">
		<ol class="breadcrumb">
				<li>
						<a href="/admin">Admin</a>
				</li>
				<li>
						<a href="{{ route('admin') }}/{{ $moduloActual->id }}"> {{ $moduloActual->nombre }}</a>
				</li>
				<li class="active">
					<strong> Subcategoria ( {{ $accion }} ) </strong>
				</li>
		</ol>
	</div>
</div>
