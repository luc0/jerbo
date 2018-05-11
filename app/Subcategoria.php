<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subcategoria';

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

	public function gestor(){
		return $this->belongsTo('App\Gestor','id_gestor');
	}

}
