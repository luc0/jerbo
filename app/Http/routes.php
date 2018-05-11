<?php

	Route::get('/', 'HomeController@index');





//---------------------------------------------------------------
// Admin
//---------------------------------------------------------------
	Route::get('admin',array( 'as' => 'admin' , 'uses' => 'AdminController@admin'));
	Route::get('admin/{modulo}',array( 'as' => 'modulo' , 'uses' => 'AdminController@modulo'));

	Route::get('admin/{modulo}/subcategorias',array( 'as' => 'modulo_subcategoria' , 'uses' => 'AdminController@moduloSubcategoria'));

	// get Alta Subcategoria
	Route::get('admin/{modulo}/subcategoria/crear',array( 'as' => 'alta_subcategoria' , 'uses' => 'SubcategoriaController@getAltaRegistro'));
	// get Modificacion Subcategoria
	Route::get('admin/{modulo}/subcategoria/{registro}/editar',array( 'as' => 'modificacion_subcategoria' , 'uses' => 'SubcategoriaController@getModificacionRegistro'));

	// get Alta
	Route::get('admin/{modulo}/{gestor}/crear',array( 'as' => 'alta_registro' , 'uses' => 'AdminController@getAltaRegistro'));
	// get Modificacion
	Route::get('admin/{modulo}/{gestor}/{tabla}/{registro}/editar',array( 'as' => 'modificacion_registro' , 'uses' => 'AdminController@getModificacionRegistro'));


//---------------------------------------------------------------
// API Admin (ponerle seguridad, identificacion requerida)
//---------------------------------------------------------------

	// REST API ( sirve para el Jquery )
	Route::group(array('prefix' => 'api', 'before' => 'auth.admin' ), function() {
		// Sirve para hacer route('api')
		Route::get('/', array('as' => 'api'));

		Route::post('file/upload', array('as' => 'api_file_upload', 'uses' => 'FileController@upload'));
		Route::post('file/delete', array('as' => 'api_file_delete', 'uses' => 'FileController@delete'));

		// do Alta
		Route::post('api/alta/{modulo}/{gestor}/{tabla}',array( 'as' => 'api_alta_registro' , 'uses' => 'AdminController@doAltaRegistro'));
		// do Modificacion
		Route::post('api/{modulo}/{gestor}/{tabla}/{registro}/editar',array( 'as' => 'api_modificacion_registro' , 'uses' => 'AdminController@doModificacionRegistro'));

		//Route::get('modelo/subcategoria/gestor/{id_gestor}', array('as' => 'api_subcategorias_de_gestor', 'uses' => 'SubcategoriaController@getSubcategoriasGestor'));
		//Route::resource('modelo/{modelo}/{condicion_campo}/{condicion_valor}', 'ApiController',
		Route::resource('gestor', 'GestorController',
			['only' => ['index', 'store', 'update', 'destroy', 'show']]);

	});

	// do Alta Subcategoria
	Route::post('api/{modulo}/subcategoria/crear',array( 'as' => 'do_alta_subcategoria' , 'uses' => 'SubcategoriaController@doAltaRegistro'));
	// do Modificacion Subcategoria
	Route::post('api/{modulo}/subcategoria/{registro}/editar',array( 'as' => 'do_modificacion_subcategoria' , 'uses' => 'SubcategoriaController@doModificacionRegistro'));
	// do Eliminar Subcategoria
	Route::get('api/{modulo}/subcategoria/{registro}/eliminar',array( 'uses' => 'SubcategoriaController@doEliminacionRegistro'));

	// do Alta
	Route::post('api/{modulo}/{gestor}/{tabla}/crear',array( 'as' => 'do_alta_registro' , 'uses' => 'AdminController@doAltaRegistro'));
	// do Modificacion
	Route::post('api/{modulo}/{gestor}/{tabla}/{registro}/editar',array( 'as' => 'do_modificacion_registro' , 'uses' => 'AdminController@doModificacionRegistro'));
	// do Eliminar
	Route::get('api/{modulo}/{gestor}/{tabla}/{registro}/eliminar',array( 'uses' => 'AdminController@doEliminacionRegistro'));
	// do Mover
	Route::get('api/{modulo}/{gestor}/{tabla}/{registro}/{direccion}/mover',array( 'as' => 'do_mover_registro', 'uses' => 'AdminController@doMoverRegistro'));
	// do Activar/Desactivar
	Route::get('api/{modulo}/{gestor}/{tabla}/{registro}/{activar}/activar',array( 'as' => 'do_activar_registro', 'uses' => 'AdminController@doActivarRegistro'));




	//Estaticos

	Route::get('/upload/{tabla}/{field}/{file}',array( 'as' => 'upload' ));

	Route::get('/upload/{tabla}/{field}',array( 'as' => 'upload_table' ));









//---------------------------------------------------------------
// Sitio	{modulo} / {gestor} / {subcategoria} / {publicacion_slug}
//---------------------------------------------------------------

	Route::get('{modulo}/{gestor}/{subcategoria}/{id_subcategoria}', array( 'as' => 'listado_publicaciones' , 'uses' => 'HomeController@getAllPublicaciones'));

	Route::get('{modulo}/{gestor}/{subcategoria}/{id_subcategoria}/{articulo}/{id_articulo}', array( 'as' => 'publicacion' , 'uses' => 'HomeController@getPublicacion'));

	Route::get('contacto', array( 'as' => 'estatico_contacto' , 'uses' => 'EstaticoController@getContacto'));

	Route::post('contacto', array( 'as' => 'enviar_email' , 'uses' => 'EstaticoController@doContacto'));


//---------------------------------------------------------------
// Errores
//---------------------------------------------------------------
Route::get('archivo_inexistente', array( 'as' => 'no_file' , 'uses' => 'ErrorController@getNoFile'));

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
