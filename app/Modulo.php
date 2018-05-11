<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'modulo';
	public $gestor_contenido;
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

	public function gestores(){
		return $this->hasMany('App\Gestor','id_modulo')->orderBy('orden');
	}

	/*public function scopeGestoresHermanos(){
		$contenido = $this->gestor_contenido;
  	return $this->hasMany('App\Gestor','id_modulo')->where('contenido', $contenido)->orderBy('orden');
	}*/

	public function scopeGestoresHermanos( $contenido ){
		return $this->hasMany('App\Gestor','id_modulo')->where('contenido', $contenido)->orderBy('orden');
	}

/*
	public function scopeHermanos( $q ){
		return $q->with(array('gestores' => function($query) use($q){
			$query->where('contenido',$q->gestor->contenido);
	 	}))->whereHas('gestores', function($query) use($q){
			$query->where('contenido',$q->gestor->contenido);
		});
	}*/
/*
	public function scopeGestoresher( $q, $gestor ){
		return $q->hasMany('App\Gestor','id_modulo');//->where('contenido',$gestor);
	}
*/
}
