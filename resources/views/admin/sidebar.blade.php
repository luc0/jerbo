<div class="col-sm-3 col-md-2 sidebar">
	@if( $modulos )
	<ul class="nav nav-sidebar">
		@foreach( $modulos as $m )
		<li class="{{ (ISSET($moduloActual) && $m->id === $moduloActual['id']) ? 'active' : '' }}" >
			<a href="{{ route('admin') }}/{{ $m->id }}">{{ $m->nombre }}</a>
		</li>
		@endforeach
	</ul>
	@endif
</div>
