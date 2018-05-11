<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Modulo;
use App\Subcategoria;
use DB;
use App\Helpers\Formatear;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	public $cutted_text = 'introduccion';

	protected function get_modules(){
		$module = Modulo::with('gestores','gestores.subcategorias')->orderBy('orden')->get();
		return $module;
	}


	// Formatea los campos que utilizan los FILE.
	protected function format_file_fields( $gestores, $ID_PUBLICACION = null, $filter = null ){
		foreach( $gestores as $g ){
			$this->array_campos_file = $g->campos->where('tipo','file')->lists('nombre');

			$query_data = DB::table( 'contenido_'.$g->contenido )->where('activo',1);

			// si no es novedad toma solo el contenido del gestor, novedades es global, no filtra por gestor
			if( $filter != 'novedad'){
				$query_data = $query_data->where('id_gestor',$g->id);
			}

			if( $filter != 'novedad' && ISSET($this->subcategoria) ){
				$query_data = $query_data->where('id_subcategoria',$this->subcategoria->id);
			}

			$query_data = $query_data->orderBy('orden','asc');

			if( $filter == 'novedad' ){
				$query_data = $query_data->where('novedad',1);
			}

			if( $ID_PUBLICACION ){
				$g->data = $query_data->where( 'id', $ID_PUBLICACION )->first();
			}else{
				$g->data = $query_data->get();
			}
			
			if( $g->data ){
				if( is_Array($g->data) ){
					foreach( $g->data as $d ){
						$this->format_current( $g, $d );
						if( ISSET($d->{$this->cutted_text}) ){ // algunos contenidos no tienen "texto"
							$d->{$this->cutted_text} = Formatear::cortar( $d->{$this->cutted_text}, 140 );
						}
					}
				}else{
					$this->format_current( $g, $g->data );
					if( ISSET($d->{$this->cutted_text}) ){ // algunos contenidos no tienen "texto"
						$g->{$this->cutted_text} = Formatear::cortar( $g->{$this->cutted_text}, 140 );
					}
				}
			}

			if( is_Array($g->data) ){
				$g->data = array_reverse($g->data);
			}
		}
	}

	// Formatea los campos que utilizan los FILE.
	protected function format_file_fields_novedades( $gestores ){

		foreach( $gestores as $g ){

			$this->array_campos_file = $g->campos->where('tipo','file')->lists('nombre');

			$g->data = DB::table( 'contenido_nota' )->where('activo',1)->where('id_gestor',$g->id)->where('novedad',1)->orderBy('orden','asc')->get();
			
			// DUPLICADO. OPTIMIZAR
			if( $g->data ){
				if( is_Array($g->data) ){
					foreach( $g->data as $d ){
						$this->format_current( $g, $d );
						if( ISSET($d->{$this->cutted_text}) ){ // algunos contenidos no tienen "texto"
							$d->{$this->cutted_text} = Formatear::cortar( strip_tags($d->{$this->cutted_text}), 140 );
						}
					}
				}else{
					$this->format_current( $g, $g->data );
					if( ISSET($d->{$this->cutted_text}) ){ // algunos contenidos no tienen "texto"
						$g->{$this->cutted_text} = Formatear::cortar( strip_tags($g->{$this->cutted_text}), 140 );
					}
				}
			}

			$g->data = array_reverse($g->data);
		}
	}

	// TODO: esta funcion es casi igual a la de adminController->format_file_fields, nada mas que la de admin tiene otra parte que esta no necesita. Se diferencia en que si tiene una imagen te la devuelve en array
	private function format_current( $g, $d ){
		// Agrega el type del file, se fija si tiene campo SRC!
		foreach( $this->array_campos_file as $campo ){
			if( ISSET($d->{$campo}) && $d->{$campo} ){
				$url = "/upload/" . $g->contenido . "/" . $campo . "/";
				$array_val = explode(',',$d->{$campo});
				if( count($array_val) > 1 ){
				// si tiene varios archivos
					foreach( $array_val as $i=>$file_path ){
						$array_val[$i] = $url . $file_path;
					}
					$d->{$campo} = $array_val;
				}elseif( count($array_val) == 1 ){
				// si tiene 1 solo archivo o ninguno
					$exploded = explode('.',$d->{$campo});
					$d->{$campo} = array($url . $d->{$campo}); // (* en esto se diferencia)
					if( count($exploded) ){
						$d->{'type_'.$campo} = $exploded[count($exploded)-1];
					}
				}
			}else{
				$d->{$campo} = [];
			}
		}
	}

	public function modules_with_contenido_categorias(){
		
		$modules = $this->get_modules();
		// Navbar cabecera
		// agrega data a modules->gestores
		$all_subcategorias = Subcategoria::with('gestor')->get();
		foreach( $modules as $m ){
			foreach( $m->gestores as $g ){
				// agrega gestores->data
				$g->campos_compartidos();	
				$this->format_file_fields( [$g] );

				if( !ISSET($count) ){
					$count = array();
				}

				// agrega gesores->contenido_subcategorias ( tiene publicaciones por cada subcategoria )
				foreach( $all_subcategorias as $subcategoria ){
					if( count($subcategoria->gestor) ){
						if( $subcategoria->gestor->id == $g->id ){
							$contenidos = DB::table( 'contenido_nota' )->where('id_subcategoria',$subcategoria->id)->where('activo',1)->count();
							if( count($contenidos) ){
								$count[$subcategoria->id] = $contenidos;
							}
						}
					}
				}
				$g->contenido_subcategorias = $count;
				
			}
		}

		//return dd($modules);

		return $modules;
	}
}
