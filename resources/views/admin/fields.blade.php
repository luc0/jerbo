
@foreach( $gestor->campos as $c )

	@if( $c->tipo == 'text' )
		<!-- TEXT -->
		<div class="form-group">
			<label for="{{ $c->nombre }}" class="col-sm-2 control-label">{{ $c->nombre }}</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="{{ $c->nombre }}" name="{{ $c->nombre }}"
				@if( ISSET($registro) )
					value="{{ $registro->{$c->nombre} }}"
				@endif
				>
			</div>
		</div>
	@elseif( $c->tipo == 'textarea' )
		<!-- TEXTAREA -->
		<div class="form-group">
			<label for="{{ $c->nombre }}" class="col-sm-2 control-label">{{ $c->nombre }}</label>
			<div class="col-sm-10">
				<textarea class="form-control" rows="3" id="{{ $c->nombre }}" name="{{ $c->nombre }}" >@if( ISSET($registro) ) {{ $registro->{$c->nombre} }}@endif</textarea>
			</div>
		</div>

	@elseif( $c->tipo == 'checkbox' )
		<!-- CHECKBOX (falta modificacion) -->
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="checkbox">
					<label>
						<input type="checkbox" input="{{ $c->nombre }}" name="{{ $c->nombre }}" value="1"
						@if( ISSET($registro) && $registro->{ $c->nombre } == 1 )
							checked="checked"
						@endif
						> {{ $c->nombre }}
					</label>
				</div>
			</div>
		</div>

	@elseif( $c->tipo == 'radio' )
		<!-- RADIO (ES UTIL?)(falta modificacion) -->
		<div class="form-group">
			<div class="radio col-sm-offset-2 col-sm-10">
				<label>
					<input type="radio" id="{{ $c->nombre }}" name="{{ $c->nombre }}" value="1">
					{{ $c->nombre }}
				</label>
			</div>
		</div>

	@elseif( $c->tipo == 'select' )
		<!-- falta modificacion -->
		@if( ISSET($c->opciones) )
			<!-- SELECT -->
			<div class="form-group">
				<label for="{{ $c->nombre }}" class="col-sm-2 control-label">{{ $c->nombre }}</label>
				<div class="col-sm-10">
					<select class="form-control" name="{{ $c->nombre }}" id="{{ $c->nombre }}">
						@foreach( $c->opciones as $o)
							<option value="{{ $o }}">{{ $o }}</option>
						@endforeach
					</select>
				</div>
			</div>
		@else
			<div class="form-group">
				<label for="{{ $c->nombre }}" class="col-sm-2 control-label">{{ $c->nombre }}</label>
				<div class="col-sm-10">
					<div class="alert alert-danger" role="alert"> Select: campo "opciones" esta vacío. </div>
				</div>
			</div>
		@endif
	@elseif( $c->tipo == 'file' )
		<div class="form-group">
			<label for="{{ $c->nombre }}" class="col-sm-2 control-label">{{ $c->nombre }}</label>
			<div class="col-sm-10">
				<div class="dropzone" id="{{ $c->nombre }}">
				</div>
			</div>
		</div>
		{{--
		<!--Subir 1 solo file-->
			<!-- BORRA IMG ANTERIOR -->
			<!--<input type="hidden" name="borra_{{ $c->nombre }}" value="{{ $registro->{$c->nombre} }}">-->
		<!-- FILE -->
		<!--<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">{{ $c->nombre }}</label>
			<div class="col-sm-10">
				<div class="jFiler jFiler-theme-default">
					<input type="file" name="{{ $c->nombre }}[]" id="{{ $c->nombre }}" multiple="multiple"/>
				</div>
			</div>
		</div>-->
		--}}

	@elseif( $c->tipo == 'hidden' )
		<input type="hidden" name="{{ $c->nombre }}" value="{{ $c->default }}">
	@else
		<div class="form-group">
			<label for="{{ $c->nombre }}" class="col-sm-2 control-label">{{ $c->nombre }}</label>
			<div class="col-sm-10">
				<div class="alert alert-danger" role="alert"> Tipo de campo incorrecto. </div>
			</div>
		</div>
	@endif

@endforeach


<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

<!-- SAVE -->
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		@if( $gestor->con_categoria === 0 || count($gestor->subcategorias) )
			<button type="submit" class="btn btn-primary">Guardar cambios</button>
		@else
			<button type="submit" class="btn btn-primary" disabled="disabled">Guardar cambios</button>
		@endif
		<a class="btn btn-default" href="{{ route('modulo',$id_modulo) }}">Volver</a>
	</div>
</div>
