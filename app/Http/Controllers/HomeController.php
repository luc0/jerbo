<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modulo;
use App\Gestor;
use App\Subcategoria;
use App\Helpers\Formatear;
use DB;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/


	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{

		$breadcrumb = ['Home'=>'/'];

		

		// novedades (TODO: contenido duplicado)
		$gestores_array = Gestor::where('contenido','nota')->get();
		//$gestor->campos;
		foreach( $gestores_array as $g ){
			$g->campos_compartidos();	
		}
		
		$this->format_file_fields_novedades( $gestores_array );

		$hay_novedades = false;
		$gestores_con_data = collect([]);

		foreach( $gestores_array as $g ){
			if( $g->data ){
				$hay_novedades = true;
				$gestores_con_data[] = $g;
			}
		}

		$modules = $this->modules_with_contenido_categorias();

		//return dd( $modules );

		/*
		- cada modulo chequear gestor
		- cada gestor get contenido
		*/

		return view('includes.contenido')
			->with('modules',$modules)
			->with('gestores',$gestores_con_data)
			->with('gestores_novedades',$gestores_con_data)
			->with('hay_novedades',$hay_novedades)
			->with('breadcrumb',$breadcrumb);

	}

	public function getAllPublicaciones($MODULO, $GESTOR, $SUBCATEGORIA, $ID_SUBCATEGORIA){

		$modules = $this->modules_with_contenido_categorias();

		$subcategoria = Subcategoria::where('id',$ID_SUBCATEGORIA)->first();

		if( $subcategoria ){
			$gestor = $subcategoria->gestor;
			$this->subcategoria = $subcategoria;
		}else{
			return 'ERROR: no hay subcategorias';
		}

		//$publicaciones = DB::table( 'contenido_' . $gestor->contenido )->where('id_subcategoria',$ID_SUBCATEGORIA)->get();

		$gestor->campos;
		$gestor->campos_compartidos();
		$this->format_file_fields( [$gestor] );

		$breadcrumb = ['Home'=>'/', ucfirst($MODULO)=>'', ucfirst($GESTOR)=>'', ucfirst($SUBCATEGORIA)=>''];

		// TODO: Esta hardcodeado, deberia hacer un select en los sectores y sacar estos valores.
		$CONTENIDOS = ['audio','galeria_de_fotos','nota','propositos','que_hacemos','video'];

		// Novedades (TODO: contenido duplicado)
		$gestores_array = Gestor::whereIn('contenido', $CONTENIDOS)->with('campos')->get();

		foreach( $gestores_array as $g ){
			$g->campos_compartidos();
			
		}
		
		$this->format_file_fields_novedades( $gestores_array );

		$gestores_con_data = collect([]);

		foreach( $gestores_array as $g ){
			if( $g->data ){
				$gestores_con_data[] = $g;
			}
		}

		// Novedades para rotador TODO: poner CONTENIDOS en vez de 'nota'
		$gestores_array = Gestor::where('contenido','nota')->get();
		//$gestor->campos;
		foreach( $gestores_array as $g ){
			$g->campos_compartidos();	
		}
		
		$this->format_file_fields_novedades( $gestores_array );

		return view('includes.contenido')
			->with('modules',$modules)
			->with('gestor',$gestor)
			->with('gestores',$gestores_con_data)
			->with('id_subcategoria',$ID_SUBCATEGORIA)
			->with('subcategoria',$subcategoria)
			->with('breadcrumb',$breadcrumb)
			->with('hide_slider',true);

	}

	public function getPublicacion($MODULO, $GESTOR, $SUBCATEGORIA, $ID_SUBCATEGORIA, $NOTA, $ID_NOTA ){

		$modules = $this->modules_with_contenido_categorias();

		$subcategoria = Subcategoria::where('id',$ID_SUBCATEGORIA)->first();

		if( $subcategoria ){
			$gestor = $subcategoria->gestor;
			$this->subcategoria = $subcategoria;
		}else{
			return 'ERROR: no hay subcategorias';
		}

		$publicacion = DB::table( 'contenido_' . $gestor->contenido )->where('id_subcategoria',$ID_SUBCATEGORIA)->where('id',$ID_NOTA)->first();

		$gestor->campos;
		$gestor->campos_compartidos();
		$this->format_file_fields( [$gestor], $publicacion->id );

		$breadcrumb = ['Home'=>'/', ucfirst($MODULO)=>'', ucfirst($GESTOR)=>'', ucfirst($SUBCATEGORIA)=>''];
		
		$CONTENIDOS = ['audio','galeria_de_fotos','nota','propositos','que_hacemos','video'];
		
		/* custom hardcoded format */
		$gestores_quienes_somos = Modulo::where('id',3)->first()->gestores;

		$quienes_somos = false;
		foreach( $gestores_quienes_somos as $g ){
			if( $g->id == $publicacion->id_gestor ){
				$quienes_somos = true;
			}
		}

		return view('includes.publicacion')
			->with('modules',$modules)
			->with('publicacion',$publicacion)
			->with('gestor',$gestor)
			->with('quienes_somos',$quienes_somos)
			->with('id_subcategoria',$ID_SUBCATEGORIA)
			->with('breadcrumb',$breadcrumb)
			->with('hide_slider',true);

	}

}





// GET ALL PUBLICACIONES NOVEDADES


/*
	$hay_novedades = false;
	$gestores_novedades = collect([]);

	foreach( $gestores_array as $g ){
		if( $g->data ){
			$hay_novedades = true;
			$gestores_novedades[] = $g;
		}
	}


	DESPUES AGREGAR A LA VISTA
	//->with('gestores_novedades',$gestores_novedades)
*/




// GET PUBLICACION NOVEDADES

/*

	// Novedades (TODO: contenido casi duplicado)
	$gestores_array = Gestor::whereIn('contenido', $CONTENIDOS)->with('campos')->get();

	foreach( $gestores_array as $g ){
		$g->campos_compartidos();	
	}

	$this->format_file_fields_novedades( $gestores_array );

	$hay_novedades = false;
	$gestores_novedades = collect([]);

	foreach( $gestores_array as $g ){
		if( $g->data ){
			$hay_novedades = true;
			$gestores_novedades[] = $g;
		}
	}



	DESPUES AGREGAR A LA VISTA
	//->with('gestores_novedades',$gestores_novedades)

*/