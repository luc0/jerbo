<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Psr\Http\Message\ServerRequestInterface;
use App\Subcategoria;
use App\Modulo;
use App\Gestor;
use Illuminate\Support\Str;
use DB;

class SubcategoriaController extends Controller {

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
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}



	// Devuelve subcategorias del GESTOR / TABLA
	public function getSubcategoriasGestor( $CONTENIDO )
	{

		$gestoresCompartidos = Gestor::where('contenido',$CONTENIDO)->with('subcategorias')->get();
		$subcategorias = [];

		foreach($gestoresCompartidos as $gc){
			foreach($gc->subcategorias as $sc){
				$subcategorias[] = $sc;
			}
		}

		return $subcategorias;
	}

	public function getAltaRegistro( $ID_MODULO )
	{

		$modulos = Modulo::orderBy('orden')->get();

		$moduloActual = Modulo::find( $ID_MODULO );

		$gestores = Gestor::where('id_modulo',$ID_MODULO)->where('con_categoria',1)->orderBy('nombre')->get();

		return view('admin.alta_subcategoria')
			->with('modulos', $modulos)
			->with('moduloActual', $moduloActual)
			->with('id_modulo', $ID_MODULO)
			->with('categorias', $gestores);
	}


	public function doAltaRegistro(Request $request, $ID_MODULO )
	{

		$subcategoria = new Subcategoria;
		$subcategoria->id_gestor = $request->id_gestor;
		$subcategoria->nombre = $request->nombre;
		$subcategoria->save();

		return redirect()->route('modulo_subcategoria',[$ID_MODULO]);
	}


	public function getModificacionRegistro( $ID_MODULO , $ID_REGISTRO )
	{

		$modulos = Modulo::orderBy('orden')->get();

		$moduloActual = Modulo::find( $ID_MODULO );

		$registro = Subcategoria::find( $ID_REGISTRO );

		$gestores = Gestor::where('id_modulo',$ID_MODULO)->where('con_categoria',1)->orderBy('nombre')->get();

		return view('admin.modificacion_subcategoria')
			->with('modulos', $modulos)
			->with('moduloActual', $moduloActual)
			->with('id_modulo', $ID_MODULO)
			->with('registro', $registro)
			->with('categorias', $gestores);
	}

	public function doModificacionRegistro(Request $request, $ID_MODULO, $ID_REGISTRO )
	{

		$registro = Subcategoria::find( $ID_REGISTRO );
		$registro->id_gestor = $request->id_gestor;
		$registro->nombre = $request->nombre;
		$registro->save();

		return redirect()->route('modulo_subcategoria',[$ID_MODULO]);
	}

	public function doEliminacionRegistro( $ID_MODULO, $ID_REGISTRO ){

		Subcategoria::find( $ID_REGISTRO )->delete();

		return redirect()->route('modulo_subcategoria',[$ID_MODULO]);

	}



}
