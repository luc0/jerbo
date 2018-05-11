@extends('app')

@section('admin_content')

<div class="row">

	@include('includes.admin_subcategoria_breadcrumb', array('accion' => 'agregar nueva'))

	<div class="col-sm-3 col-md-2 sidebar">
		@if( $modulos )
		<ul class="nav nav-sidebar">
			@foreach( $modulos as $m )
			<li><a href="{{ route('admin') }}/{{ $m->id }}">{{ $m->nombre }}</a></li>
			@endforeach
		</ul>
		@endif
	</div>
	<div class="col-sm-offset-2 col-md-offset-2 col-sm-7 col-md-8 main">

    <h2>Sub-categorias</h2>
    <!-- FORM -->
    <form class="form-horizontal row" method="POST" action="{{ route('do_alta_subcategoria',array($id_modulo)) }}">

			<!-- CATEGORIA (GESTOR) -->
			<div class="form-group">
				<label for="id_gestor" class="col-sm-2 control-label">Categoria</label>
				<div class="col-sm-10">
					<select class="form-control" name="id_gestor" id="id_gestor">
						@foreach( $categorias as $categoria )
							<option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
						@endforeach
					</select>
				</div>
			</div>

      <!-- TEXT -->
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Nombre</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="nombre" name="nombre">
        </div>
      </div>


      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Agregar</button>
          <a class="btn btn-default" href="{{ route('modulo_subcategoria',$id_modulo) }}">Volver</a>
        </div>
      </div>

      <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

    </form>
    <!-- END FORM -->


  </div>

</div>

@endsection
