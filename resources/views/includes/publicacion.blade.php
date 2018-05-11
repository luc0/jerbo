@extends('layout')

@section('contenido')

	<!-- Contenido -->
	<div class="row">

		@if( ISSET( $quienes_somos ) && $quienes_somos )
			@include('layouts.formato_estatico', ['p' => $publicacion])
			<a href="#" onclick="history.go(-1);" class="boton" /><em class="glyphicon glyphicon-chevron-left"></em> Volver</a>
		@else
			<div class="col-md-8">
				@if( ISSET($publicacion) )
					
						@if( ISSET($publicacion->formato) )
							@if($publicacion->formato == 1)
								@include('layouts.formato1', ['p' => $publicacion])
							@elseif($publicacion->formato == 2)
								@include('layouts.formato2', ['p' => $publicacion])
							@elseif($publicacion->formato == 3)
								@include('layouts.formato3', ['p' => $publicacion])
							@endif
						@else
						{{-- Sin formato --}}
							@foreach( $gestor->campos as $campo )
								@if( $campo->html == 'p' )
									<p>{{ $publicacion->{$campo->nombre} }}</p>

								@elseif( $campo->html == 'h2' )
									<h2>{{ $publicacion->{$campo->nombre} }}</h2>

								@elseif( $campo->html == 'h3' )
									<h3>{{ $publicacion->{$campo->nombre} }}</h3>

								@elseif( $campo->html == 'link' )
									<a href="#">{{ $publicacion->{$campo->nombre} }}</a>

								@elseif( $campo->html == 'youtube' )
									<p>{{ $publicacion->{$campo->nombre} }}</p>

								@elseif( $campo->html == 'img' )
									@foreach( (array)$gestor->data->imagenes as $i=>$img )
									  @if( $img )
										<div class="col-md-12">
											<img class="imagen_grande" src="{{ asset( $img ) }}" width="100%">
										</div>
									  @endif
									@endforeach

								@elseif( $campo->html == 'audio' )
									<p>{{ $publicacion->{$campo->nombre} }}</p>
								@endif
							@endforeach
						@endif
					
				@else
					No hay publicaciones.
					
				@endif	  	

				<a href="#" onclick="history.go(-1);" class="boton" /><em class="glyphicon glyphicon-chevron-left"></em> Volver</a>
			</div>
		@endif
		<!--<div class="col-md-4">
			-- notas relacionadas --
		</div>-->
		
	</div>


@stop
