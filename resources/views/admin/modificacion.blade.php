@extends('app')

@section('admin_content')


<div class="row">

	@include('includes.admin_breadcrumb', array('accion' => 'modificar'))

	@include ('admin.sidebar')
	<div class="col-sm-offset-2 col-md-offset-2 col-sm-7 col-md-8 main">

    <h2>{{ $gestor->nombre }}</h2>
    <!-- FORM -->
    <form class="form-horizontal row" id="form_abm" method="POST" action="{{ route('do_modificacion_registro',array($id_modulo, $gestor->id, $gestor->contenido, $registro->id)) }}" enctype="multipart/form-data">

			@include ('admin.fields_static_modificacion')

			@include ('admin.fields')

    </form>
    <!-- END FORM -->

  </div>

</div>

@endsection
