@if( $gestor->con_formato > 0 )
	<!-- FORMATO -->
	<div class="form-group">
		<label for="formato" class="col-sm-2 control-label"> Formato </label>
		<div class="radio col-sm-offset-2 col-sm-10">
			@if( ISSET($registro->formato) )
				@foreach( $formatos as $i => $formato )
				<label>
						<div class="thumbnail radioPreview">
							<img src="{{ asset('img/maqueta'.++$i.'.jpg') }}" alt="maqueta{{ $i }}" width="220" />
						</div>
						@if( intval($registro->formato) === $i )
							<input type="radio" name="formato" id="formato" name="formato" value="{{ $i }}" checked="checked">
						@else
						<input type="radio" name="formato" id="formato" name="formato" value="{{ $i }}">
						@endif
					<span>Opción {{ $i }}</span>
				</label>
				@endforeach
			@else
			<div class="alert alert-danger" role="alert">La tabla <b>contenido_{{ $gestor->contenido }}</b> no contiene un campo <b>"formato"</b> o esta mal escrito. </div>
			@endif
		</div>
	</div>
@endif


@if( $gestor->custom === 0 )
	<!-- CATEGOGIRAS (GESTORES) -->
	<div class="form-group">
		<label for="id_gestor" class="col-sm-2 control-label">Categoria</label>
		<div class="col-sm-10">
			<select class="form-control" name="id_gestor" id="id_gestor" data-gestor-id="{{ $gestor->id }}" data-api-url="{{ route('api.gestor.show',array('')) }}">
				@foreach( $gestoresHermanosPorModulo as $m )
					<optgroup label="{{ $m->nombre }}">
						@foreach( $m->gestores as $optionGestor )
							@if( $registro->id_gestor === $optionGestor->id )
								<option value="{{ $optionGestor->id }}" selected="selected">{{ $optionGestor->nombre }}</option>
							@else
								<option value="{{ $optionGestor->id }}">{{ $optionGestor->nombre }}</option>
							@endif
						@endforeach
					</optgroup>
				@endforeach
			</select>
		</div>
	</div>
@endif

@if( $gestor->con_categoria > 0 )
	@if( count($gestor->subcategorias) )
	<!-- SUBCATEGORIAS -->
	<div class="form-group">
		<label for="id_subcategoria" class="col-sm-2 control-label">Subcategoria</label>
		<div class="col-sm-10">
			<select class="form-control" name="id_subcategoria" id="id_subcategoria">
				@foreach( $gestor->subcategorias as $subcat )
					@if( $registro->id_subcategoria === $subcat->id )
						<option value="{{ $subcat->id }}" selected="selected">{{ $subcat->nombre }}</option>
					@else
						<option value="{{ $subcat->id }}">{{ $subcat->nombre }}</option>
					@endif
				@endforeach
			</select>
		</div>
	</div>
	@else
		<div class="form-group">
			<label class="col-sm-2 control-label">Subcategoria</label>
			<div class="col-sm-10">
				<a class="btn btn-primary" href="{{ route('alta_subcategoria',array($id_modulo)) }}">Agregar Subcategoria</a>
				<div class="alert alert-danger" role="alert">No hay <b>subcategorias</b> creadas para asignarle a la publicación. Primero deberías crear alguna para <b>{{ $gestor->nombre }}</b>.</div>

			</div>
		</div>
	@endif
@endif
