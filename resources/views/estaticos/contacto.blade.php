@extends('layout')

@section('contenido')

	<h2>Contacto</h2>

	<form id="fm_enviar_email" class="form-horizontal row">

		@foreach( $campos_contacto['text'] as $input=>$validation )
			<div class="form-group">
				<label for="input_{{$input}}" class="col-sm-2 control-label">{{ $input }}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="input_{{$input}}" name="{{ $input }}" data-validation="{{ $validation['validation'] }}" data-validation-error-msg="{{ $validation['msg'] }}" />
				</div>
			</div>
		@endforeach

		@foreach( $campos_contacto['textarea'] as $input=>$validation )
		<div class="form-group">
			<label for="input_{{$input}}" class="col-sm-2 control-label">{{ $input }}</label>
			<div class="col-sm-10">
				<textarea id="input_{{$input}}" name="{{$input}}" rows="8" cols="40" data-validation="{{ $validation['validation'] }}" data-validation-error-msg="{{ $validation['msg'] }}"></textarea>
			</div>
		</div>

		@endforeach


		<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

		<!-- SAVE -->
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" id="fm_submit" class="btn btn-primary">Enviar</button>
				<a class="btn btn-default" onclick="goBack()">Volver</a>
			</div>
		</div>

	</form>
	<div class="form-group">
		<div id="fm_success" class="alert alert-success col-sm-12 hidden" role="alert">
			Gracias por contactarte.
		</div>
	</div>
	<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
@stop

@section('custom_assets')
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/jquery.form-validator.min.js"></script>
	<script type="text/javascript" src="{{ URL::asset('js/front.js') }}"></script>
@stop
