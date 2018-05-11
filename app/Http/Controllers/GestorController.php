<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Psr\Http\Message\ServerRequestInterface;
use App\Subcategoria;
use App\Modulo;
use App\Gestor;
use App\Campo;
use DB;
use File;

class GestorController extends Controller {

	public function show( $_GESTOR )
	{

		// Condicion 1
		/*if( $_CAMPO && $_VALOR ){
			return DB::table( $_MODELO )->where( $_CAMPO, $_VALOR )->get();
		}*/

		// Todo
		return Gestor::where( 'id', $_GESTOR )->with('subcategorias')->first();

	}



}
