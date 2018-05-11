<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Psr\Http\Message\ServerRequestInterface;
use App\Subcategoria;
use App\Modulo;
use App\Gestor;
use App\Campo;
use JavaScript;
use DB;
use File;

class AdminController extends Controller {

	public $formatos = [1,2,3];
	public $save_fields = [];
	public $fileNombre = ''; // nombre de los archivos
	public $files_to_move_normalized = [];

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function admin()
	{
		// modulos
		$modulos = Modulo::orderBy('orden')->get();
		return view('admin.admin')->with('modulos', $modulos);
	}


	public function modulo( $ID )
	{

		$modulos = Modulo::orderBy('orden')->get();

		$moduloActual = $this->getCurrentModule( $ID );

		$categorias = Gestor::with('subcategorias')->has('subcategorias')->where('id_modulo',$ID)->orderBy('nombre')->get();

		JavaScript::put([
				'api' => route('api')
    	]);

		return view('admin.admin')
			->with('modulos', $modulos)
			->with('moduloActual', $moduloActual->toArray())
			->with('categorias', $categorias);
	}

	public function moduloSubcategoria( $ID )
	{

		$modulos = Modulo::orderBy('orden')->get();

		$moduloActual = $this->getCurrentModule( $ID );

		$categorias = Gestor::with('subcategorias')->has('subcategorias')->where('id_modulo',$ID)->orderBy('nombre')->get();

		JavaScript::put([
			'api' => route('api')
    	]);

		return view('admin.admin_subcategoria')
			->with('modulos', $modulos)
			->with('moduloActual', $moduloActual->toArray())
			->with('categorias', $categorias);
	}

	public function getAltaRegistro( $ID_MODULO , $ID_GESTOR )
	{

		$this->init( null, $ID_MODULO, $ID_GESTOR, null );

		$moduloActual = $this->getCurrentModule( $ID_MODULO );

		$modulos = Modulo::orderBy('orden')->get();

		$gestor = Gestor::where('id',$this->id_gestor)->with('campos')->first();
		$gestor->campos_compartidos();
		$gestor->listFileFields();
		$gestor = $gestor->configurarCamposSelect();

		$subcategoria = Subcategoria::all();
		//$campos_file = $gestor->campos_file->lists('nombre');
		$dropzone = [];

		foreach($gestor->campos_file as $c){
			$dropzone[ $c ] = ['campos' => $c];
		}

		JavaScript::put([
        'rutaDropzone' => route('api_file_upload'),
				'rutaDelete' => route('api_file_delete'),
				'rutaSaveRegistro' => route('api_alta_registro',array($this->id_modulo,$this->id_gestor,$gestor->contenido)),
				'dropzone' => $dropzone,
				'modificacion' => false
	    ]);

		return view('admin.alta')
			->with('modulos', $modulos)
			->with('id_modulo', $this->id_modulo)
			->with('moduloActual', $moduloActual)
			->with('gestor', $gestor)
			->with('subcategoria', $subcategoria)
			->with('formatos', $this->formatos);
	}


	public function doAltaRegistro(Request $request, $ID_MODULO, $ID_GESTOR, $TABLA )
	{
		$this->init( $request, $ID_MODULO, $ID_GESTOR, $TABLA );

		// Gestor
		$this->gestor = Gestor::where('id', $this->id_gestor)->with('campos')->first();

		$this->gestor->campos_compartidos();


		$orden = $this->get_last_order_value();

		$this->manage_files();

		$this->prepare_fields_to_save();

		$this->move_temp_files_to_folders();

		$this->save_fields['orden'] = $orden + 1;
		
		// save campos
		DB::table( 'contenido_'.$this->tabla )->insert( $this->save_fields );

		return response()->json([
			'success' => true,
			'redirect' => route('modulo',[$this->id_modulo])
		]);

	}


	public function getModificacionRegistro( $ID_MODULO , $ID_GESTOR, $TABLA, $ID_REGISTRO )
	{

		$this->init( null, $ID_MODULO, $ID_GESTOR, $TABLA );

		$moduloActual = $this->getCurrentModule( $ID_MODULO );

		$modulos = Modulo::orderBy('orden')->get();

		$this->gestor = Gestor::where('id',$this->id_gestor)->with('campos')->first();

		$this->gestor->campos_compartidos();

		$this->gestor = $this->gestor->configurarCamposSelect(); // Organiza

		// Gestores que usan la misma tabla. Para armar el select de CATEGORIA
		$gestoresHermanosPorModulo = Modulo::with(array('gestores' => function($query){
			$query->where('contenido',$this->gestor->contenido);
	 	}))->whereHas('gestores', function($query){
			$query->where('contenido',$this->gestor->contenido);
		})->get();

		$this->registro = DB::table( 'contenido_'.$this->tabla )->find( $ID_REGISTRO );

		$dropzone = $this->get_dropzone_data();

		JavaScript::put([
				'rutaDropzone' => route('api_file_upload'),
				'rutaDelete' => route('api_file_delete'),
				'rutaSaveRegistro' => route('api_modificacion_registro',array($this->id_modulo,$this->id_gestor,$this->gestor->contenido,$ID_REGISTRO)),
        		'registro' => $this->registro,
				'tabla' => $this->gestor->contenido,
				'dropzone' => $dropzone,
				'modificacion' => true
    	]);

		return view('admin.modificacion')
			->with('modulos', $modulos)
			->with('id_modulo', $this->id_modulo)
			->with('moduloActual', $moduloActual)
			->with('gestor', $this->gestor)
			->with('registro', $this->registro)
			->with('formatos', $this->formatos)
			->with('gestoresHermanosPorModulo', $gestoresHermanosPorModulo);
	}

	public function doModificacionRegistro(Request $request, $ID_MODULO, $ID_GESTOR, $TABLA, $ID_REGISTRO )
	{

		$this->init( $request, $ID_MODULO, $ID_GESTOR, $TABLA );

		//registro
		$this->registro = DB::table( 'contenido_'.$this->tabla )->where( 'id', $ID_REGISTRO )->first();

		if( $this->registro ){
			// Gestor
			$this->gestor = Gestor::where('id',$this->id_gestor)->with('campos')->first();

			$this->gestor->campos_compartidos();

			$this->manage_files();

			$this->prepare_fields_to_save();

			$this->move_temp_files_to_folders();

			// save campos
			DB::table( 'contenido_'.$this->tabla )->where( 'id', $ID_REGISTRO )->update( $this->save_fields );

		}else{
			return response()->json([
				'success' => false,
				'msg' => 'No existe el registro'
			]);
		}
		return response()->json([
			'success' => true,
			'redirect' => route('modulo',[$this->id_modulo])
		]);

	}

	public function doEliminacionRegistro( $ID_MODULO, $ID_GESTOR, $TABLA, $ID_REGISTRO ){

		$this->init( null, $ID_MODULO, $ID_GESTOR, $TABLA );

		// Archivos

		$query_registro = DB::table( 'contenido_'.$this->tabla )->where( 'id', $ID_REGISTRO );
		$this->registro = $query_registro->first();

		// Defino gestor, para get_dropzone_data()
		$this->gestor = Gestor::where('id',$this->id_gestor)->with('campos')->first();
		$this->gestor->campos_compartidos();
		$this->gestor = $this->gestor->configurarCamposSelect(); // Organiza

		$dropzone = $this->get_dropzone_data();
		$this->remove_files_enum_in_field( $dropzone );

		// Registro
		$query_registro->delete();

		return redirect()->route('modulo',[$this->id_modulo]);

	}

	public function doMoverRegistro( $ID_MODULO, $ID_GESTOR, $TABLA, $ID_REGISTRO, $DIRECCION ){

		$this->init( null, $ID_MODULO, $ID_GESTOR, $TABLA );

		$registro = DB::table( 'contenido_'.$this->tabla )->where('id', $ID_REGISTRO)->first();

		if( $registro ){

			$registros = DB::table( 'contenido_'.$this->tabla )->where('id_gestor',$ID_GESTOR)->where('id_subcategoria',$registro->id_subcategoria)->orderBy('orden')->get();

			if( $DIRECCION == 0 ){ // intercambia con el anterior
				$this->get_positions_and_swap( $registros, $ID_REGISTRO, $DIRECCION );

			}else if( $DIRECCION == 1 ){ // intercambia con el proximo
				$this->get_positions_and_swap( $registros, $ID_REGISTRO, $DIRECCION );

			}

		}

		return redirect()->route('modulo',[$this->id_modulo]);
			

	}

	public function doActivarRegistro( $ID_MODULO, $ID_GESTOR, $TABLA, $ID_REGISTRO, $ACTIVAR ){

		$this->init( null, $ID_MODULO, $ID_GESTOR, $TABLA );

		DB::table( 'contenido_'.$this->tabla )->where( 'id', $ID_REGISTRO )->update( array('activo' => $ACTIVAR ) );

		return redirect()->route('modulo',[$this->id_modulo]);

	}








	// -------------------------------------------------
	// Funciones privadas
	// -------------------------------------------------


	private function init( $request = null, $id_modulo = null, $id_gestor = null, $tabla = null ){

		$this->request = $request;
		$this->id_modulo = $id_modulo;
		$this->id_gestor = $id_gestor;
		$this->tabla = $tabla;

	}

	// 1) lo prepara dentro de: $this->save_fields.. 2) Prepara los nombres de los archivos dentro de: $this->files_to_move_normalized
	private function prepare_fields_to_save(){

		foreach( $this->gestor->campos as $c ){
			/*if( $this->request->input( $c->nombre ) ){
				$this->save_fields[$c->nombre] = $this->request->input( $c->nombre );*/
			if( $this->request->file( $c->nombre ) ){
				// Si cambio la imagen
				if( ISSET($this->fileNombre)){
					$this->save_fields[$c->nombre] = $this->fileNombre;
				}
			}elseif( $c->tipo == 'file' ){

				if( !ISSET($this->files_to_move_normalized[ $c->nombre ]) ){
					$this->files_to_move_normalized[ $c->nombre ] = [];
				}

				if( $this->request->multiupload ){
					// agrega src
					$multiupload = json_decode($this->request->multiupload);
					$agregados_array = $multiupload->{$c->nombre}->agregados;
					$agregados_array = array_map(function ($object) use($c) {
						if( ISSET($object->new_name) ){
							return $this->if_exists_change_name($object->new_name,$c->gestores_contenido,$c->nombre);
						}else{
							if( !ISSET($object->name) ){
								return dd( $object );							
							}
							return $object->name;
						}
					}, $agregados_array);
					
					$add_to_src = '';
					if( count($agregados_array) > 0 ){
						$add_to_src = implode(',', $agregados_array);
					}
					// mantiene existentes
					if( ISSET($this->registro) ){
						$this->save_fields[$c->nombre] = $this->registro->{$c->nombre} . ',';
					}
					// agrega nuevos
					$this->save_fields[$c->nombre] = $add_to_src;

					// borra src
					$borrados_array = $multiupload->{$c->nombre}->borrados;
					if( count($borrados_array) > 0 ){
						foreach( $borrados_array as $borrado ){
							$this->save_fields[$c->nombre] = str_replace($borrado,'',$this->save_fields[$c->nombre]);
						}

						$this->save_fields[$c->nombre] = $this->enum_remove_empty( $this->save_fields[$c->nombre] );

					}
					
				}
			}elseif( $c->tipo == 'checkbox' ){
				if( $this->request->input( $c->nombre ) == 0 ){
					$this->save_fields[$c->nombre] = 0;
				}else{
					$this->save_fields[$c->nombre] = 1;
				}
			}else{
				// Es un input normal, puede tener contenido o estar vacio
				$this->save_fields[$c->nombre] = $this->request->input( $c->nombre );
			}
		}

		// Tambien guarda formato y subcategoria. Estos campos se genera automaticamente, por eso se hace manual aca.
		if( $this->request->formato > 0 ){ 
			$this->save_fields['formato'] = intval($this->request->formato);
		}
		if( $this->request->id_subcategoria > 0 ){ 
			$this->save_fields['id_subcategoria'] = intval($this->request->id_subcategoria);
		}
		if( $this->request->id_gestor ){
			$this->save_fields['id_gestor'] = intval($this->request->id_gestor);
		}


	}

	// borra y crea archivos
	private function manage_files(){

		// File
		foreach( $this->request->file() as $campoNombre => $file ) {

			if( $file[0] ){

				// Get id
				$ultimoId = DB::table( 'contenido_'.$this->tabla )->orderBy('id', 'desc')->first();
				if( $ultimoId ){
					$ultimoId = $ultimoId->id;
					$ultimoId++;
				}else{
					$ultimoId = 1;
				}

				// Verifica carpeta
				$dir = $this->get_upload_folder($TABLA, $campoNombre);

				// Crea el archivo
				$this->fileNombre = $ultimoId . '_' .$this->request->nombre . '.' . $file[0]->getClientOriginalExtension();
				$file[0]->move( $dir, $this->fileNombre);

			}
		}


	}

	private function enum_remove_empty( $enum ){
		$arr_clean = explode(',', $enum);
		$arr_clean = array_filter($arr_clean);
		$result = implode(',', $arr_clean);
		return $result;
	}

	private function move_temp_files_to_folders(){

		// Mueve archivos temporales a la carpeta correspondiente ( archivos subidos don dropzone )
		if( $this->files_to_move_normalized ){
			foreach($this->files_to_move_normalized as $dropzoneNombre=>$files_agregados){
				
				$dir = $this->get_upload_folder($this->tabla, $dropzoneNombre);
				$temp = public_path() . '/upload/temp/';

				foreach($files_agregados as $agregado){
					if( (ISSET($agregado["new_name"]) && File::exists($temp.$agregado["old_name"]))){
						$mover = File::move( $temp.$agregado["old_name"], $dir.$agregado["new_name"] );
					}
				}
			}
		}

	}

	private function if_exists_change_name( $file_name, $gestor, $tipo ){
		
		$file_version = 0;
		$fileNombre = $file_name;
		while( $file_version == 0 || $existe ){
			$filePath = public_path().'/upload/'. $gestor . '/' . $tipo . '/' . $fileNombre;
			$existe = File::exists($filePath);
			if ( $existe ) {
				$file_version++;
				$fileNombre = $file_version . '_' . $file_name;
			}else{
				break;
			}
		}

		$this->files_to_move_normalized[ $tipo ][] = [ "new_name" => $fileNombre, "old_name" => $file_name ];
		return $fileNombre;
	}

	private function remove_files_enum_in_field( $dropzone ){

		foreach( $dropzone as $d){
			if( ISSET($d['value']) ){
				$array_files = explode(',',$d['value']);

				foreach($array_files as $file){
					//$filename = $d['path'] . $file;
					$filename = public_path().'/upload'.'/'.$this->tabla . '/' . $d['campo'] . '/' . $file;

					if (File::exists($filename)) {
						File::delete($filename);
					}
				}
			}
		}
	}

	private function get_dropzone_data(){
		$dropzone = [];
		$array_campos_file = $this->gestor->campos->where('tipo','file')->lists('nombre');

		foreach($array_campos_file as $c){
			$dropzone[ $c ] = [
				'campo' => $c,
				'value' => $this->registro->{$c},
				'path' => route('upload_table', array( $this->gestor->contenido, $c )) . '/'
			];
		}
		return $dropzone;
	}

	// Formatea los campos que utilizan los FILE.
	private function format_admin_file_fields( $gestores ){
		foreach( $gestores as $g ){
			$array_campos_file = $g->campos->where('tipo','file')->lists('nombre');
			$g->data = DB::table( 'contenido_'.$g->contenido )->orderBy('orden','asc')->get();
			// Agrega el type del file, se fija si tiene campo SRC!
			foreach( $g->data as $d ){
				foreach( $array_campos_file as $campo ){
					if( $d->{$campo} ){
						$array_val = explode(',',$d->{$campo});
						if( count($array_val) > 1 ){
							$d->{$campo} = $array_val;
						}else{
							$exploded = explode('.',$d->{$campo});
							//$d->{$campo} = $array_src;
							if( count($exploded) ){
								$d->{'type_'.$campo} = $exploded[count($exploded)-1];
							}
						}
					}
				}
			}

			$g->data = array_reverse( $g->data );
		}
	}

	private function get_upload_folder($TABLA, $campo){

		$folder = public_path().'/upload' . '/'. $TABLA;
		if(!is_dir( $folder )){
				File::makeDirectory( $folder );
		}

		$dir = public_path().'/upload' . '/'. $TABLA . '/' . $campo . '/';
		if(!is_dir( $dir )){
				File::makeDirectory( $dir );
		}
		return $dir;
	}

	private function get_last_order_value(){
		// Ordenar a lo ultimo
		$ultimoRegistro = DB::table( 'contenido_'.$this->tabla )->orderBy('orden','desc')->first();

		$orden = 0;
		if( $ultimoRegistro && $ultimoRegistro->orden ){
			$orden = $ultimoRegistro->orden;
		}
		return $orden;

	}

	private function swap_order_and_save( $registroActual, $registroAfectado ){
			DB::table( 'contenido_'.$this->tabla )->where( 'id', $registroActual->id )->update( array('orden' => $registroAfectado->orden) );
			DB::table( 'contenido_'.$this->tabla )->where( 'id', $registroAfectado->id )->update( array('orden' => $registroActual->orden) );
	}

	private function get_positions_and_swap( $registros, $id_registro, $direccion){

		foreach( $registros as $i => $r ){
			if( $r->id == $id_registro && $r->orden > 0){
				$indiceActual = $i;
				if( $i > 0 && $direccion == 1 ){
					$indiceAfectado = $i - 1;
				}elseif( $i < (count($registros)-1) && $direccion == 0){
					$indiceAfectado = $i + 1;
				}
			}
		}

		if( ISSET( $indiceActual ) && ISSET( $indiceAfectado ) ){
			return $this->swap_order_and_save( $registros[ $indiceActual ], $registros[ $indiceAfectado ] );
		}

	}

	private function getCurrentModule( $ID ){
		// Trae datos del modulo, gestores y de los campos ( los custom )
		$moduloActual = Modulo::where('modulo.id', $ID)->with('gestores','gestores.campos')->first();

		foreach($moduloActual->gestores as $gestor){
			$gestor->campos_compartidos();
		}

		$this->format_admin_file_fields( $moduloActual->gestores );

		return $moduloActual;
	}

}
