<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Gestor extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gestor';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	//protected $fillable = ['id_categoria', 'titulo', 'texto'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = ['id_categoria'];
	public $campos_file = [];

	public function campos(){
		return $this->hasMany('App\Campo','id_gestor');
	}
	/*public function campos_file(){
		return $this->hasMany('App\Campo','id_gestor')->where('tipo','file');
	}*/
	public function subcategorias(){
		return $this->hasMany('App\Subcategoria','id_gestor');
	}
	public function modulos(){
		return $this->belongsTo('App\Modulo','id_modulo');
	}

	//-----------------------------------
	// Para los gestores que comparten campos ( y tabla ) con otros gestores ( Es decir, custom = 0 )
	//-----------------------------------
		public function subcategorias_compartidas($CONTENIDO){
			$gestoresCompartidos = Gestor::where('contenido',$CONTENIDO)->with('subcategorias')->get();
			$subcategorias = [];

			foreach($gestoresCompartidos as $gc){
				foreach($gc->subcategorias as $sc){
					$subcategorias[] = $sc;
				}
			}

			$this->subcategorias = $subcategorias;
		}


		// Trae datos de los campos ( Que no son custom )
		public function campos_compartidos(){
			if($this->custom == 0){
				$campos = Campo::where('gestores_contenido','=',$this->contenido)->orderBy('orden')->get();
				unset($this->campos);
				$this->campos = $campos;
			}else{
				// TODO: Tuve que hacer esto, porque no me hacia la relacion de una. ( Hacerlo mejor )
				$this->campos = Campo::where('id_gestor','=',$this->id)->orderBy('orden')->get();
			}
		}



	//-----------------------------------------
	// Organiza las opciones de los select
	//-----------------------------------------
	public function configurarCamposSelect(){
		foreach($this->campos as $c){
			if($c->tipo == 'select' && $c->opciones){
				$c->opciones = explode(',',$c->opciones);
			}
		}
		return $this;
	}

	public function listFileFields(){
		foreach($this->campos as $c){
			if($c->tipo == 'file'){
				$this->campos_file[] = $c->nombre;
			}
		}
		return $this;
	}



}


/*

REF PROPS:

	CONTENIDO:
		De que tabla obtiene los registros. "contenido_{valor}"

	CUSTOM:
		0 -> utiliza campos compartidos con otros gestores definidos en "contenido"
		1 -> utiliza campos especificos para ese gestor nada mas.

	CON_CATEGORIA:
		0 -> no editable
		1 -> editable

	FORMATO:
		0 -> no editable
		1 -> editable


*/