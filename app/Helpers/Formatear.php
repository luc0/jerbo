<?php

namespace App\Helpers;

// Helper para las vistas
class Formatear{

	// -----------------------------------------
	// Cortar texto exactamente en..
    // -----------------------------------------

	public static function cortar($texto,$maxLetras=60){
		$texto = Formatear::limpiar_html( $texto );
		if (strlen($texto) > $maxLetras){
			$texto = substr($texto,0,$maxLetras);
			return $texto."...";
		}else{
			return $texto;
		}
	}

	// -----------------------------------------
	// Ajustar titulo
    // -----------------------------------------
	public static function header_title($texto,$maxLetras=60){
		if (strlen($texto) > $maxLetras){
			return "<p><span>" . wordwrap($texto, 44, "</span><span>") . "</span></p>";
		}else{
			return $texto;
		}
	}

	// -----------------------------------------
	// Transforma saltos de linea en <br> y escapa texto. (evita inyeccion xss)
    // -----------------------------------------
    public static function texto( $texto ){
		return nl2br( e($texto) );
	}

	// Limpia acutes y tags
	public static function limpiar_html( $texto ){
		
		$no_tags = strip_tags( $texto );
		$no_acutes = html_entity_decode( $no_tags );
		
		return $no_acutes;

	}


}