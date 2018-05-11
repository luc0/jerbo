<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Psr\Http\Message\ServerRequestInterface;
//use App\Subcategoria;
//use App\Modulo;
//use App\Gestor;
//use App\Campo;
use DB;
use File;
use Hash;

class FileController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
	}

	public function upload(Request $request)
	{

		$files = $request->file();
		if( count($files) ){
			foreach($files as $file){

				// Verifica carpeta, si no existe la crea.
				$dir = public_path().'/upload/temp';
				if(!is_dir( $dir )){
						File::makeDirectory( $dir );
				}

				$fileNombre = $this->generateUniqueName( $file->getClientOriginalName() );

				// Crea el archivo
				$mover = $file->move( $dir, $fileNombre);

				if($mover){
					return response()->json([
						'success' => true,
						'new_name' => $fileNombre
					]);
				}
			}
		}

	}

	public function generateUniqueName( $file_name ){
		$file_version = 0;
		$fileNombre = str_replace(' ','_',$file_name);
		while( $file_version == 0 || $existe ){
			$filePath = public_path().'/upload/temp/' . $fileNombre;
			$existe = File::exists($filePath);
			if ( $existe ) {
				$file_version++;
				$fileNombre = $file_version . '_' . $file_name;
			}else{
				break;
			}
		}
		return $fileNombre;
	}

	public function delete(Request $request){

		$file = $request->input('name');
		$existente = $request->input('existente');
		$dropzone = $request->input('dropzone');

		if( ISSET($file) ){
			if( $existente ){
				$filename = public_path().'/upload/' . $existente . '/' . $dropzone . '/' . $file;
			}else{
				$filename = public_path().'/upload/temp/' . $file;
			}

			if (File::exists($filename)) {
			  File::delete($filename);
				return response()->json(['success' => true]);
			}
			return response()->json(['success' => false, 'msg' => 'El archivo no existe '.$filename]);
		}

		return response()->json(['success' => false, 'msg' =>'No se paso ningun parametro']);

	}


}

/*
	FUNCIONAMIENTO DE LOS ARCHIVOS

	1 - Cuando cargas en el dropzone, se suben a /temp
	2 - Se fija si existe un archivo con el mismo nombre, si existe le agrega un numero adelante ( ej: 1_foto.jpg, 2_foto.jpg )
	3 - Guardar: Cuando guardas el adminController ejecuta 2 metodos.
		A - el primero organiza la data que se guarda en la DB, y la data para mover los archivos.
			Se fija si ya existe un archivo con ese nombre en la carpeta. Si existe hace lo mismo que hacia /temp, le agrega un numero adelante.
		B - mueve los archivos. ( tiene en cuenta el posible nuevo nombre si es que ya existia un archivo ) Esta info se lo da el metodo A.
	4 - Se guardan los datos en la DB.

*/