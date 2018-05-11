@extends('layout')

@section('contenido')

	<!-- Contenido -->
	<div class="row">
		<div class="col-md-12">

			@if( ISSET($hay_novedades) || ( ISSET($gestor) && count($gestor->data) ) )
					
				@if( ISSET($hay_novedades) )

					<h2 class="gestor-title"><span>Novedades</span></h2>

					<div class="row">

						@foreach( $gestores as $gestor )
							@foreach( $gestor->data as $i=>$p )
								<div class="col-md-6">
									<div class="item_publicacion">
										@if( ISSET($p->imagenes[0]) )
											<div class="img_cabecera" style="background-image:url( {{ asset( $p->imagenes[0] ) }})"></div>							
										@elseif( ISSET($p->video) )
											<div class="img_cabecera" style="background-image:url( {{ asset( 'http://img.youtube.com/vi/' . $p->video . '/0.jpg' ) }})"></div>
										@endif
										<!--{modulo}/{gestor}/{subcategoria}/{id_subcategoria}/{articulo}/{id_articulo}-->
										<h3><a href="{{ $gestor->modulos->nombre }}/{{ $gestor->nombre }}/{{ $gestor->nombre }}/{{ $p->id_subcategoria }}/{{ str_slug($p->titulo) }}/{{ $p->id }}">{{ $p->titulo }}</a></h3>
										@if( ISSET($p->introduccion) )
											<p>{!! $p->introduccion !!}</p>
										@endif
										<a class="leer_mas" href="{{ $gestor->modulos->nombre }}/{{ $gestor->nombre }}/{{ $gestor->nombre }}/{{ $p->id_subcategoria }}/{{ str_slug($p->titulo) }}/{{ $p->id }}">LEER MÁS</a>
									</div>
								</div>
							@endforeach
						@endforeach

					</div>	  

				@else

					<h2 class="gestor-title"><span>{{ $gestor->nombre }}: {{ $subcategoria->nombre }}</span></h2>

					<div class="row">

						@foreach( $gestor->data as $i=>$p )
							<div class="col-md-6">
								<div class="item_publicacion">
									@if( ISSET($p->imagenes[0]) )
										<div class="img_cabecera" style="background-image:url( {{ asset( $p->imagenes[0] ) }})"></div>							
									@elseif( ISSET($p->video) )
										<div class="img_cabecera" style="background-image:url( {{ asset( 'http://img.youtube.com/vi/' . $p->video . '/0.jpg' ) }})"></div>							
									@endif
									
									<h3><a href="{{ $id_subcategoria }}/{{ str_slug($p->titulo) }}/{{ $p->id }}">{{ $p->titulo }}</a></h3>
									@if( ISSET($p->introduccion) )
										<p>{{ $p->introduccion }}</p>
									@endif
									<a class="leer_mas" href="{{ $id_subcategoria }}/{{ str_slug($p->titulo) }}/{{ $p->id }}">LEER MÁS</a>
								</div>
							</div>
						@endforeach

					</div>	  	
				@endif


			@else

				<div class="panel panel-default">
				  <div class="panel-body">
				    No hay publicaciones.
				  </div>
				</div>
				<a href="#" onclick="history.go(-1);" class="boton" /><em class="glyphicon glyphicon-chevron-left"></em> Volver</a>
				<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

			@endif

		</div>
		<!--<div class="col-md-4">
			-- notas relacionadas --
		</div>-->
	</div>


@stop
