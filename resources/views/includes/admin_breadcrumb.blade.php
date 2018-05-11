
<div class="row">
	<div class="col-sm-9 col-md-9 col-md-offset-3">
		<ol class="breadcrumb">
		    <li>
		        <a href="/admin">Admin</a>
		    </li>
				@if( isset($moduloActual) )
		    	<li>
		        	<strong> {{ $moduloActual['nombre'] }}</strong>
		    	</li>
				@elseif( isset($gestor) && $gestor->modulos )
		    	<li>
		        	<a href="{{ route('admin') }}/{{ $gestor->modulos->id }}"> {{ $gestor->modulos->nombre }}</a>
		    	</li>
					<li class="active">
						<strong> {{ $gestor->nombre }} ( {{ $accion }} )</strong>
					</li>
				@endif
		</ol>
	</div>
</div>
