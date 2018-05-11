@extends('app')

@section('admin_content')

<div class="row">
	<div class="col-sm-3 col-md-2 col-md-offset-3">
		<ol class="breadcrumb">
		    <li>
		        <a href="/admin">Admin</a>
		    </li>
				@if( isset($moduloActual) )
					<li>
		        	<a href="{{ route('admin', array($moduloActual['id'])) }}">{{ $moduloActual['nombre'] }}</a>
		    	</li>
		    	<li class="active">
		        	<strong>Subcategorias</strong>
		    	</li>
				@endif
		</ol>
	</div>
</div>

<div class="row">

	@include ('admin.sidebar')

	<div class="col-sm-10 col-md-10 col-md-offset-1 main">

		@if( ISSET($categorias) )


		<div class="container">
			<div class="row">
				<div class="col-md-11 col-md-offset-1">
					<h2>Subcategorias</h2>
				</div>
			</div>
		</div>

		<!-- Categoria -->
		<div class="container">
			<div class="row">
				<div class="col-md-11 col-md-offset-1">


					<div class="ibox float-e-margins">
					    <div class="ibox-title">

					        <div class="ibox-tools">
											<a class="btn btn-primary" href="{{ route('alta_subcategoria',array($moduloActual['id'])) }}">Agregar</a>
					            <a class="collapse-link">
					                <i class="fa fa-chevron-up"></i>
					            </a>
					        </div>
					    </div>
					    <div class="ibox-content">

					        <!--<table class="table">-->
								<table class="table table-striped">
									<thead>
										<tr>
											<th>#</th>
											<th>Subcategoria</th>
											<th>Categoria</th>
											<th>Opciones</th>
										</tr>
									</thead>

									<tbody>
									@foreach( $categorias as $cat )
										@if( count($cat->subcategorias) )
											@foreach( $cat->subcategorias as $s )
												<tr>
													<th>{{ $s->id }}</th>
													<td>{{ $s->nombre }}</td>
													<td>{{ $cat->nombre }}</td>
													<td>
														<a href="{{ route('modificacion_subcategoria',array($moduloActual['id'], $s->id)) }}">Editar</a> |
														<a href="#" data-toggle="modal" class="btn_eliminar_subcategoria"
															data-target="#modalEliminar"
															data-modulo="{{ $moduloActual['id'] }}"
															data-id="{{ $s->id }}">
															Eliminar
														</a>
													</td>
												</tr>
											@endforeach
										@else
											<tr>
												<td> No hay contenido cargado.</td>
											</tr>
										@endif
									@endforeach
									</tbody>
								</table>
							</div>
					</div>

					@if( isset($moduloActual) )
						<a type="button" class="btn btn-default" href="{{ route('modulo', array($moduloActual['id'])) }}">Volver</a>
					@endif
				</div>
			</div>
		</div>
		<!-- Fin:Categoria -->
		@endif

</div>

@include ('includes.modal_eliminar')

@endsection
