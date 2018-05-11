<?php

namespace App\Helpers;

// Helper para la nav
class Nav{

	// -----------------------------------------
	// Decide donde re dirije el link..
    // -----------------------------------------

/*
	public static function makeLink($m, $g, $subcat){

		if( count($g->contenido_subcategorias) ){
            foreach( $g->contenido_subcategorias as $index_subcat => $content_count ){
                if( $subcat->id == $index_subcat ){
                    if( $content_count == 1 && count($g->data) )
                        $link = '<li><a href="' . {{ route('publicacion',array( str_slug($m->nombre,'-'), str_slug($g->nombre,'-'), str_slug($subcat->nombre,'-'), $subcat->id, str_slug($g->data[0]->titulo), $g->data[0]->id )) }} . '"><span class="titulo">{{ $g->nombre }}</span></a></li>';
                    }else{
                        $link = '<li><a href="' . {{ route('listado_publicaciones',array( str_slug($m->nombre,'-'), str_slug($g->nombre,'-'), str_slug($subcat->nombre,'-'), $subcat->id )) }} . '"><span class="titulo">{{ $subcat->nombre }}</span></a></li>';
                    }
                }
            }
        }

        return $link;

	}
*/
}