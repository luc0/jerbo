<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class QuienesSomos extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'contenido_quienes_somos';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id_categoria', 'titulo', 'contenido'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = ['id_categoria'];

}
