<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
// Cargando clases
use App\Http\Middleware\ApiAuthUsuarioMiddleware;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
}); */
Route::get('/', function () {
   return "hola desde la api de domicilios";
});

Route::get('/clientes', 'ClientesController@index')->name('clientes');

Route::get('/clientes/{id}', 'ClientesController@show');

Route::post('/clientes', 'ClientesController@store'); // registro de clientes

Route::post('/login', 'ClientesController@login');

Route::put('/clientes', 'ClientesController@update')->middleware(ApiAuthMiddleware::class);

Route::resource('suscripcion', 'SuscripcionesController');
Route::post('/getProductos', 'SuscripcionesController@getProductos');
Route::get('/getCategorias', 'SuscripcionesController@getCategorias')->middleware(ApiAuthUsuarioMiddleware::class);
Route::get('/getSubCategoria/{id}', 'SuscripcionesController@getSubCategoriasNeg')->middleware(ApiAuthUsuarioMiddleware::class);
Route::get('/updateCategorias/{id}', 'SuscripcionesController@updateRelCatNegocio')->middleware(ApiAuthUsuarioMiddleware::class);
Route::post('/transacion', 'SuscripcionesController@getTransacion');
Route::resource('usuario', 'UsuarioController');
Route::post('/usuario/login', 'UsuarioController@login');
Route::resource('producto', 'ProductoController');
Route::get('getImagenProducto', 'ProductoController@getImagenProducto');
Route::resource('negocio', 'NegocioController');
Route::get('/ciudades', 'SuscripcionesController@getCiudades');
Route::get('/allCategorias', 'SuscripcionesController@allCategorias');
Route::post('/producto/upload', 'ProductoController@upload');
Route::post('/producto/setUpload', 'ProductoController@setUpload');
Route::get('/producto/image/{filename}', 'ProductoController@getImagen');

//multimedia de un producto
Route::get('/multimedia/producto/{id}', 'MultimediaController@getMultimedia');
Route::post('/multimedia/producto', 'MultimediaController@insertMultimedia');
Route::delete('/multimedia/producto/{id}', 'MultimediaController@deleteMultimedia');