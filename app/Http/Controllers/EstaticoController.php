<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modulo;
use App\Gestor;
use App\Subcategoria;
use App\Helpers\Formatear;
use App\Http\Controllers\HomeController;
use JavaScript;
use Mail;
use DB;

class EstaticoController extends Controller {

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */

	public function getContacto(){

		$campos_contacto = [
			'text' => [
				'Nombre'=> [
					'validation' => 'required',
					'msg' => 'Debes escribir un nombre'
				],
				'Apellido'=> [
					'validation' => 'required',
					'msg' => 'Debes escribir un apellido'
				],
				'Email'=> [
					'validation' => 'email',
					'msg' => 'No es un email'
				]
			],
			'textarea' => [
				'Consulta'=> [
					'validation' => 'required',
					'msg' => 'Debes escribir una consulta'
				]
			]
		];

		JavaScript::put([
			'rutaEmail' => route('enviar_email')
    	]);

		$modules = $this->modules_with_contenido_categorias();

		// gestores
		$gestores_array = Gestor::where('contenido','nota')->get();
		foreach( $gestores_array as $g ){
			$g->campos_compartidos();	
		}
		
		$this->format_file_fields( $gestores_array, null, 'novedad' );

		$hay_novedades = false;
		$gestores_con_data = collect([]);

		foreach( $gestores_array as $g ){
			if( $g->data ){
				$hay_novedades = true;
				$gestores_con_data[] = $g;
			}
		}


		return view('estaticos.contacto')
			->with('modules',$modules)
			->with('campos_contacto',$campos_contacto)
			->with('gestores',$gestores_con_data)
			->with('hide_slider',true);

	}

	public function doContacto(Request $request){

		$data = [
			'to_email' => 'info@funcei.org.ar',
			'to_name' => 'web funcei',
			'from_name' => $request->get('Nombre') . ' ' . $request->get('Apellido'),
			'from_email' => $request->get('Email'),
			'msg' => $request->get('Consulta')
		];

		$envio = Mail::send('emails.contacto', $data, function($message) use ($data){
			$message->to($data['to_email'], $data['to_name'])
							->subject('Contacto - funcei.org.ar');
		});

		if( count(Mail::failures()) > 0 ) {
			return response()->json([
				'success' => false,
				'msg' => 'fallo email'
			]);
		}

		return response()->json([
			'success' => true,
			'test' => $data
		]);

	}

}
