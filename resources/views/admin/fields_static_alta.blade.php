@if( $gestor->con_formato > 0 )
	<!-- FORMATO -->
	<div class="form-group">
		<label for="formato" class="col-sm-2 control-label"> Formato </label>
		<div class="radio col-sm-offset-2 col-sm-10">
			@foreach( $formatos as $i => $formato )
			<label>
				<div class="thumbnail radioPreview">
					<img src="{{ asset('img/maqueta'.++$i.'.jpg') }}" alt="maqueta{{ $i }}" width="220" />
				</div>
				<input type="radio" name="formato" id="formato" value="{{ $i }}" checked="checked">
				Opción {{ $i }}
			</label>
			@endforeach
		</div>
	</div>
@endif

@if( $gestor->con_categoria > 0 )
	@if( count($gestor->subcategorias) )
		<!-- SUBCATEGORIAS -->
		<div class="form-group">
			<label for="id_subcategoria" class="col-sm-2 control-label"> Subcategoria </label>
			<div class="col-sm-10">
				<select class="form-control" name="id_subcategoria" id="id_subcategoria">
					@foreach( $gestor->subcategorias as $subcat )
						<option value="{{ $subcat->id }}">{{ $subcat->nombre }}</option>
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


<input type="hidden" name="id_gestor" value="{{ $gestor->id }}">

