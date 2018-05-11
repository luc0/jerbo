@extends('app')

@section('admin_content')

@include('includes.admin_breadcrumb')

<div class="row">

	@include ('admin.sidebar')

	@if( ISSET($moduloActual))
		<div class="col-sm-10 col-md-10 col-md-offset-1 main">

			<div class="container">
				<div class="row">
					<div class="col-md-11 col-md-offset-1">
						<h2>{{ $moduloActual['nombre'] }}</h2>
						<a class="btn btn-primary" href="{{ route('modulo_subcategoria',array($moduloActual['id']))}}">Subcategorias</a>
					</div>
				</div>
			</div>

			@if( isset($moduloActual) && isset($moduloActual['gestores']) )
			@foreach( $moduloActual['gestores'] as $g )
			<div class="container">
				<div class="row">
					<div class="col-md-11 col-md-offset-1">
								<div class="ibox float-e-margins">
								    <div class="ibox-title">
								        <h5>{{ $g['nombre'] }}</h5>
								        <div class="ibox-tools">
														@if( count($g['campos']) == 0 )
														<div class="alert alert-danger" role="alert"> No tiene campos definidos. </div>
														@else
														<a class="btn btn-primary" href="{{ route('alta_registro',array($moduloActual['id'], $g['id'])) }}">Publicar</a>
														@endif
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
											@if( count($g['data']) )
												@foreach( $g['campos'] as $c )
													@if( $c['visible_en_listado'] )
														<th>{{ $c['nombre'] }}</th>
													@endif
												@endforeach
												<th>Ordenar</th>
												<th>Opciones</th>
											@endif

										</tr>
									</thead>
									<tbody>
									@if( count($g['data']) )
										@foreach( $g['data'] as $i => $data )
											@if( ( $g['custom'] ||  $data->id_gestor == $g['id'] ) )
												<tr>
													<th>{{ $i+1 }}</th>
													@foreach( $g['campos'] as $c )

														@if( $c['visible_en_listado'] )
															@if( isset($data->$c['nombre']) )
																@if( $c['tipo'] == 'file' )
																	 @if( count($data->$c['nombre']) > 1 )
																	 	<td>
																	 		<div class="jFiler-items-default"><div class="jFiler-item"><div class="jFiler-item-icon"><i class="icon-jfi-file-o jfi-file-type-application jfi-file-ext-img"></i></div><div><div>
																			{{ count($data->$c['nombre']) }} archivos.
																		</td>
																	 @else
																		@if( ISSET($data->{'type_'.$c['nombre']}))
																			@if( $data->{'type_'.$c['nombre']} == 'jpg' || $data->{'type_'.$c['nombre']} == 'jpeg' || $data->{'type_'.$c['nombre']} == 'png' || $data->{'type_'.$c['nombre']} == 'gif')

																				<td><img src="{{ asset( 'upload/' . $g['contenido'] . '/' . $c['nombre'] . '/' . $data->$c['nombre'] ) }}" alt="preview" width="60" height="60" /></td>
																			@else
																				<td>
																					<div class="jFiler-items-default"><div class="jFiler-item"><div class="jFiler-item-icon"><i class="icon-jfi-file-o jfi-file-type-application jfi-file-ext-img"></i></div><div><div>
																					{{ $data->{'type_'.$c['nombre']} }}
																				</td>
																			@endif
																		@else
																			<td class="alert alert-warning" ><em>No hay archivos.</em></td>
																		@endif
																	@endif
																@else
																	<td>{{ str_limit($data->$c['nombre'], 220) }}</td>
																@endif
															@else
																<td class="alert alert-danger" >No existe campo <b>{{$c['nombre']}}</b></td>
															@endif
														@endif
													@endforeach
													<td>
														@if( $i > 0 )
															<a title="Mover hacia arriba" href="{{ route('do_mover_registro',array($moduloActual['id'], $g['id'], $g['contenido'], $data->id, 0)) }}"><em class="glyphicon glyphicon-chevron-up"></em></a> |
														@else
															<em class="glyphicon glyphicon-chevron-up"></em> |
														@endif
														@if( $i < count($g['data'])-1 )
															<a title="Mover hacia abajo" href="{{ route('do_mover_registro',array($moduloActual['id'], $g['id'], $g['contenido'], $data->id, 1)) }}"><em class="glyphicon glyphicon-chevron-down"></em></a>
														@else
															<em class="glyphicon glyphicon-chevron-down"></em>
														@endif
													</td>
													<td>
														<a href="{{ route('modificacion_registro',array($moduloActual['id'], $g['id'], $g['contenido'], $data->id)) }}" title="Editar"><em class="glyphicon glyphicon-pencil"></em></a> |
														<a href="#" title="Eliminar" data-toggle="modal" class="btn_eliminar"
															data-target="#modalEliminar"
															data-modulo="{{ $moduloActual['id'] }}"
															data-gestor-id="{{ $g['id'] }}"
															data-gestor-contenido="{{ $g['contenido'] }}"
															data-id="{{ $data->id }}">
															<em class="glyphicon glyphicon-trash"></em>
														</a> |
														@if( $data->activo )
															<a title="Activado" href="{{ route('do_activar_registro',array($moduloActual['id'], $g['id'], $g['contenido'], $data->id, 0)) }}"><em class="glyphicon glyphicon-eye-open"></em></a>
														@else
															<a class="llamativo" title="Desactivado" href="{{ route('do_activar_registro',array($moduloActual['id'], $g['id'], $g['contenido'], $data->id, 1)) }}"><em class="glyphicon glyphicon-eye-close"></em></a>
														@endif
													</td>
												</tr>
											@endif
										@endforeach
									@else
										<tr>
											<td> No hay contenido cargado.</td>
										</tr>
									@endif
									</tbody>
								</table>

							</div>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		@endif

	@endif
</div>

@include ('includes.modal_eliminar')

@endsection
