
        <div class="cabecera_img">
            <a href="/"></a>
        </div>   

<!-- Menu -->

        <div class="navbar navbar-default menu" role="navigation">
            <div class="social">
                <p>
                    <span>Seguinos en:</span>
                    <a href="https://www.facebook.com/funcei" title="facebook" class="ico-fb"></a>
                    <a href="https://twitter.com/fundstamboulian" title="twitter" class="ico-twitter"></a>
                </p>
            </div>
            <div class="container">
                <div class=""> <!-- no responsive purposes: class="collapse navbar-collapse"-->
            		<ul class="nav navbar-nav">
            			@if( ISSET($modules) && count($modules) )
            				@foreach( $modules as $m )
                                <li>
                                    @if( count($m->gestores) > 1 )
                                        <!-- varias categorias -->
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $m->nombre }} <b class="caret"></b></a>
                                        <ul class="dropdown-menu multi-level">
                                            @foreach( $m->gestores as $g )
                                                @if( count($g->subcategorias) )
                                                    <!-- IDEM bloque de abajo -->
                                                    @if( count($g->subcategorias) > 1 || $g->subcategorias[0]->nombre != $g->nombre )
                                                        <li class="dropdown-submenu">
                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="titulo">{{ $g->nombre }}</span></a>
                                                            <ul class="dropdown-menu">
                                                                @foreach( $g->subcategorias as $subcat )
                                                                    <!-- varias subcategorias + nombre != categoria -->
                                                                    @if( count($g->contenido_subcategorias) )
                                                                        @foreach( $g->contenido_subcategorias as $index_subcat => $content_count )
                                                                            @if( $subcat->id == $index_subcat )
                                                                                @if( $content_count == 1 && count($g->data) )
                                                                                    @foreach( $g->data as $i => $data )
                                                                                        @if( $data->id_subcategoria == $subcat->id )
                                                                                            <li><a href="{{ route('publicacion',array( str_slug($m->nombre,'-'), str_slug($g->nombre,'-'), str_slug($subcat->nombre,'-'), $subcat->id, str_slug($g->data[$i]->titulo), $g->data[$i]->id )) }}"><span class="titulo">{{ $subcat->nombre }}</span></a></li>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @else
                                                                                    <li><a href="{{ route('listado_publicaciones',array( str_slug($m->nombre,'-'), str_slug($g->nombre,'-'), str_slug($subcat->nombre,'-'), $subcat->id )) }}"><span class="titulo">{{ $subcat->nombre }}</span></a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @else
                                                        <!-- 1 subcategoria || nombre == categoria -->
                                                        @if( count($g->contenido_subcategorias) )
                                                            @foreach( $g->contenido_subcategorias as $index_subcat => $content_count )
                                                                @if( $g->subcategorias[0]->id == $index_subcat )
                                                                    @if( $content_count == 1 && count($g->data) )
                                                                        <li><a href="{{ route('publicacion',array( str_slug($m->nombre,'-'), str_slug($g->nombre,'-'), str_slug($g->subcategorias[0]->nombre,'-'), $g->subcategorias[0]->id, str_slug($g->data[0]->titulo), $g->data[0]->id )) }}"><span class="titulo">{{ $g->nombre }}</span></a></li>
                                                                    @else
                                                                        <li><a href="{{ route('listado_publicaciones',array( str_slug($m->nombre,'-'), str_slug($g->nombre,'-'), str_slug($g->subcategorias[0]->nombre,'-'), $g->subcategorias[0]->id )) }}"><span class="titulo">{{ $g->nombre }}</span></a></li>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                @endif
            							     @endforeach
                                         </ul>
                                    @elseif( count($m->gestores) == 1 )
                                        <!-- 1 categoria-->
                                        @if( count($m->gestores[0]->subcategorias) > 1 )
                                            <!-- varias subcategorias -->
                                            <!-- DESPLIEGA MENU CON varias SUBCATEGORIAS -->
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $m->nombre }} <b class="caret"></b></a>
                                            <ul class="dropdown-menu multi-level">
                                                <li class="dropdown-submenu">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="titulo">{{ $m->gestores[0]->nombre }}</span></a>
                                                    <ul class="dropdown-menu">
                                                        @foreach( $m->gestores[0]->subcategorias as $subcat )
                                                            <!-- 1 categoria + muchas subcategorias -->
                                                            @if( count($m->gestores[0]->contenido_subcategorias) )
                                                                @foreach( $m->gestores[0]->contenido_subcategorias as $index_subcat => $content_count )
                                                                    @if( $subcat->id == $index_subcat )
                                                                        @if( $content_count == 1 && count($m->gestores[0]->data) )
                                                                            <li><a href="{{ route('publicacion',array( str_slug($m->nombre,'-'), str_slug($m->gestores[0]->nombre,'-'), str_slug($subcat->nombre,'-'), $subcat->id, str_slug($m->gestores[0]->data[0]->titulo), $m->gestores[0]->data[0]->id )) }}"><span class="titulo">{{ $m->gestores[0]->nombre }}</span></a></li>
                                                                            <!--<li><a href="{{ route('listado_publicaciones',array( str_slug($m->nombre,'-'), str_slug($g->nombre,'-'), str_slug($subcat->nombre,'-'), $subcat->id )) }}"><span class="titulo">1 content {{ $subcat->nombre }}</span></a></li>-->
                                                                        @else
                                                                            <li><a href="{{ route('listado_publicaciones',array( str_slug($m->nombre,'-'), str_slug($g->nombre,'-'), str_slug($subcat->nombre,'-'), $subcat->id )) }}"><span class="titulo">{{ $subcat->nombre }}</span></a></li>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            </ul>
                                        @elseif( count($m->gestores[0]->subcategorias) == 1 )
                                            <!-- 1 subcategoria -->
                                            @if( $m->gestores[0]->subcategorias[0]->nombre != $m->gestores[0]->nombre )
                                                <!-- DESPLIEGA MENU CON 1 SUBCATEGORIA -->
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $m->nombre }} <b class="caret"></b></a>
                                                <ul class="dropdown-menu multi-level">
                                                    <!-- 1 categoria + 1 subcategoria + nombre != gestor -->
                                                    @if( count($m->gestores[0]->contenido_subcategorias) )
                                                        @foreach( $m->gestores[0]->contenido_subcategorias as $index_subcat => $content_count )
                                                            @if( $m->gestores[0]->subcategorias[0]->id == $index_subcat )
                                                                @if( $content_count == 1 && count($m->gestores[0]->data) )
                                                                    <li><a href="{{ route('publicacion',array( str_slug($m->nombre,'-'), str_slug($m->gestores[0]->nombre,'-'), str_slug($m->gestores[0]->subcategorias[0]->nombre,'-'), $m->gestores[0]->subcategorias[0]->id, str_slug($m->gestores[0]->data[0]->titulo), $m->gestores[0]->data[0]->id )) }}"><span class="titulo">{{ $m->gestores[0]->nombre }}</span></a></li>
                                                                @else
                                                                    <li><a href="{{ route('listado_publicaciones',array( str_slug($m->nombre,'-'), str_slug($m->gestores[0]->nombre,'-'), str_slug($m->gestores[0]->subcategorias[0]->nombre,'-'), $m->gestores[0]->subcategorias[0]->id )) }}"><span class="titulo">{{ $m->gestores[0]->nombre }}</span></a></li>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            @else
                                                <!-- NO DESPLIEGA MENU -->
                                                <!-- 1 categoria + 1 subcategoria + nombre == gestor -->
                                                @if( count($m->gestores[0]->contenido_subcategorias) )
                                                    @foreach( $m->gestores[0]->contenido_subcategorias as $index_subcat => $content_count )
                                                        @if( $m->gestores[0]->subcategorias[0]->id == $index_subcat )
                                                            @if( $content_count == 1 && count($m->gestores[0]->data) )
                                                                <a href="{{ route('publicacion',array( str_slug($m->nombre,'-'), str_slug($m->gestores[0]->nombre,'-'), str_slug($m->gestores[0]->subcategorias[0]->nombre,'-'), $m->gestores[0]->subcategorias[0]->id, str_slug($m->gestores[0]->data[0]->titulo), $m->gestores[0]->data[0]->id )) }}">{{ $m->nombre }}</a>
                                                            @else
                                                                <a href="{{ route('listado_publicaciones',array( str_slug($m->nombre,'-'), str_slug($m->gestores[0]->nombre,'-'), str_slug($m->gestores[0]->subcategorias[0]->nombre,'-'), $m->gestores[0]->subcategorias[0]->id )) }}" class="dropdown-toggle" >{{ $m->nombre }}</a>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endif
                                    @endif
            					</li>
            				@endforeach
            			@endif
                        <!--static-->
                        <li><a href="{{ route('estatico_contacto') }}">Contacto</b></a></li>
            		</ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
        
        @if( !ISSET($hide_slider) )
        <div class="slider">
            <div id="cbp-fwslider" class="cbp-fwslider">
                <ul>
                    @foreach( $gestores_novedades as $gestor )
                        @foreach( $gestor->data as $i=>$p )
                            <li>
                                <div class="container"><p><a href="{{ route('publicacion',array( 'novedades', 'novedades', 'novedades', $p->id_subcategoria, str_slug($p->titulo), $p->id )) }}">{!! App\Helpers\Formatear::header_title($p->titulo,10) !!}</a></p></div>
                                <div class="oscurecer"></div>
                                @if( ISSET($p->imagenes[0]) )
                                    <img src="{{ asset( $p->imagenes[0] ) }}" width="100%" />
                                @endif
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>   
        @endif


<!-- Breadcrumb -->
@if( ISSET($breadcrumb) )
<div class="breadcrumb">
    <div class="container">
        <div class="row">
        	<div class="col-md-12">
        		@foreach( $breadcrumb as $section => $route )
        			@if( $route != '/' )
                        /
        			@endif
                    @if( count($breadcrumb) == $section+1 )
                        @if( $route )
                            <a href="{{ $route }}" class="active">{{ $section }}</a>
                        @else
                            <span class="active">{{ $section }}</span>
                        @endif
                    @else
                        @if( $route )
                            <a href="{{ $route }}">{{ $section }}</a>
                        @else
                            <span>{{ $section }}</span>
                        @endif
                    @endif
        		@endforeach
            </div>
        </div>
	</div>
</div>
@endif