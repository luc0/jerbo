<div class="formato">

	<h1>{{ $p->titulo }}</h1>

	<h2>{{ $p->copete }}</h2>

	<p>{!! $p->introduccion !!}</p>

	<!-- imagenes -->
	<div class="row">
		@foreach( (array)$gestor->data->imagenes as $i=>$img )
		  @if( $img )
		    @if( $i > 0 )
				<div class="col-md-6">
					<div class="imagenes" style="background-image:url( {{ asset( $img ) }})"></div>
				</div>
		  	@endif
		  @endif
		@endforeach
	</div>

	<p>{!! $p->epigrafe !!}</p>

	<!-- imagenes -->
	<div class="row">
		@foreach( (array)$gestor->data->imagenes as $i=>$img )
		  @if( $img )
		    @if( $i == 0 )
				<div class="col-md-12">
					<img class="imagen_grande" src="{{ asset( $img ) }}" width="100%">
				</div>
		  	@endif
		  @endif
		@endforeach
	</div>

	<p>{!! $p->texto !!}</p>

	@if( ISSET($gestor->data->video) && !EMPTY($gestor->data->video) )
	  <div>
	    <iframe width="100%" height="400" src="https://www.youtube.com/embed/{{ $gestor->data->video }}" frameborder="0" allowfullscreen></iframe>
	  </div>
	@endif

	@if( count($gestor->data->audios) )
		<h1>Audios</h1>
	@endif
	
	@foreach( (array)$gestor->data->audios as $audio)
	  @if( $audio )
	    <div class="listado_files">
			<!--<div class="preview_files">
				<div class="jFiler-items-default"><div class="jFiler-item"><div class="jFiler-item-icon"><i class="icon-jfi-file-o jfi-file-type-application jfi-file-ext-img"></i></div></div></div>
			</div>-->
			<ul class="files_details">
				<li><p class="file_nombre">{{ explode('/',$audio)[ count(explode('/',$audio)) - 1 ] }}</p></li>
        		<li>
	        		<audio controls>
					  <source src="{{ asset( $audio ) }}" type="audio/ogg">
					  El navegador no soporta reproducción de audio.
					</audio>
        			<!--<a class="btn btn-default btn-sm" href="{{ asset( $audio ) }}"><em class="glyphicon glyphicon-eye-open"></em> ver</a>-->
        		</li>
        		<li>
					<a class="btn btn-default btn-sm" href="{{ asset( $audio ) }}" download><em class="glyphicon glyphicon-download-alt"></em> descargar</a>
				</li>
        	</ul>
	    </div>
	  @endif
	@endforeach

	@if( count($gestor->data->documentos) )
		<h1>Documentos</h1>
	@endif

	@foreach( (array)$gestor->data->documentos as $doc)
	  @if( $doc )
	    <div class="listado_files">
			<div class="preview_files">
				<div class="jFiler-items-default"><div class="jFiler-item"><div class="jFiler-item-icon"><i class="icon-jfi-file-o jfi-file-type-application jfi-file-ext-img"></i></div></div></div>
			</div>
			<ul class="files_details">
				<li><p class="file_nombre">{{ explode('/',$doc)[ count(explode('/',$doc)) - 1 ] }}</p></li>
        		<li>
        			<a class="btn btn-default btn-sm" href="{{ asset( $doc ) }}" target="_blank"><em class="glyphicon glyphicon-eye-open"></em> ver</a>
					<a class="btn btn-default btn-sm" href="{{ asset( $doc ) }}" download><em class="glyphicon glyphicon-download-alt"></em> descargar</a>
				</li>
        	</ul>
	    </div>
	  @endif
	@endforeach

</div>