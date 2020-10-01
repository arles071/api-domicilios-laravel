<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SuscripcionesModel;
use DB;
use App\Suscripcione;
use App\Negocio;
use App\Categoria;
use App\Sub_categoria;
use App\Multimedia;

class SuscripcionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$suscropcion = Negocio::get();
        //var_dump($suscropcion->usuario);
        $negociosActivos = DB::table('suscripciones')->join('negocios', 'neg_nit', '=', 'sus_negocio')->join('negocio_categoria', 'rel_neg_nit', '=', 'neg_nit')->join('categorias', 'rel_cat_id', '=', 'cat_id')->select('neg_nit', 'neg_nombre', 'neg_direccion', 'cat_nombre')->where('sus_estado', '=', 'Activo')->get();
        if(is_object($negociosActivos)){   
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'negocios'    =>  $negociosActivos
            );
        } else {
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'messaje'    =>  'No hay datos'
            );
        }
        //var_dump($negociosActivos);
        return response()->json($data, $data['code']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /* Registro la suscripcion, para poder realizar la suscripcion necesito 
    registrar el negocio y un usuario */
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validator = \Validator::make($params_array, [
            'usu_id' => 'required',
            'usu_nombres' => 'required',
            'usu_apellidos' => 'required',
            'usu_correo' => 'required',
            'usu_contrasena' => 'required',
            'neg_nit' => 'required',
            'neg_nombre' => 'required',
            'neg_direccion' => 'required',
            'neg_ciudad' => 'required'
        ]);
        if($validator->fails()){
            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Faltan datos',
                'errors'    =>  $validator->errors()
            );
            return response()->json($data, $data['code']);
        } else {
            //crear la suscripcion

            //registrar el usuario
            $suscripcionModel = new SuscripcionesModel($params->usu_id,           $params->usu_nombres,$params->usu_apellidos,$params->usu_correo, $params->usu_contrasena, $params->neg_nit, $params->neg_nombre, $params->neg_direccion, $params->neg_ciudad);
            $usuario = $suscripcionModel->usuario();

            if($usuario){
                //echo "registro exitoso";
                //registrar el negocio
                $negocio = $suscripcionModel->registrarNegocio();

                if($negocio){
                    $id_sus = $suscripcionModel->registrarSuscripcion();
                    if(!empty($id_sus)){
                        $data = array(
                            'code' => 200,
                            'status' => 'pendiente',
                            'id_suscripcion' => $id_sus
                        );
                        return response()->json($data, $data['code']);
                        
                    }
                   
                }
            } else {
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  404,
                    'message'   =>  'Usuario ya existe'
                );
                return response()->json($data, $data['code']);
            }
            
            
            //registrar la suscripcion
            //var_dump($params);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
 //Registrar transacion
    public function getTransacion(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validator = \Validator::make($params_array, [
            'id_suscripcion' => 'required'
        ]);
        if($validator->fails()){
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'messaje'    =>  'Datos incorrectos'
            );
        }else {
            $nuevaFecha = SuscripcionesModel::getTransacion($params->id_suscripcion);
            SuscripcionesModel::editSuscripcion($params->id_suscripcion, $nuevaFecha);
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'pago'    =>  'pago exitoso'
            );
        }
        return response()->json($data, $data['code']);  
    }

    public function getCategorias(Request $request){
        //Comprobar si el usuario esta identificado.
        $token = $request->header('Authorization');
        $jwtAuthUsuario = new \JwtAuthUsuario();
        $checkToken = $jwtAuthUsuario->checkToken($token);
        if($checkToken){  
            //sacar usuario identificado
            $usuario = $jwtAuthUsuario->checkToken($token, true);
            $categoriaNegocio = DB::table('negocios')
        ->join('negocio_categoria', 'rel_neg_nit', '=', 'neg_nit')->join('categorias', 'rel_cat_id', '=', 'cat_id')->select('cat_id', 'cat_nombre')->where('neg_nit', '=', $usuario->neg_nit)->where('estado', '=', 1)->get();
        if(is_object($categoriaNegocio) && !$categoriaNegocio->isEmpty()){   
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'categorias'    =>  $categoriaNegocio
            );
        } else {
            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'messaje'    =>  'No hay datos'
            );
        }
        }
     
        //var_dump($negociosActivos);
        return response()->json($data, $data['code']);
    }
    public function getProductos(Request $request){

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validator = \Validator::make($params_array, [
            'nit' => 'required'
        ]);
        if($validator->fails()){
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'messaje'    =>  'Datos incorrectos'
            );
        } else {
        
        $productosNegocio = DB::table('productos')
            ->join('sub_categorias', 'sub_id', '=', 'prod_idSub')->join('categorias', 'sub_cat_id', '=', 'cat_id')->select('cat_nombre', 'sub_nombre', 'prod_id', 'prod_nombre', 'prod_cantidad', 'prod_valor')->where('prod_nitNegocio', '=', $params->nit)->get();
        
        if(is_object($productosNegocio)){ 
            
            $listaProductos = array();
                if(count($productosNegocio) != 0){
                    foreach($productosNegocio as $pro){
                
                        $imagenes = Multimedia::where('mul_id_producto', $pro->prod_id)->get();
                        $img = [];  
                        if(is_object($imagenes)){
                            foreach($imagenes as $image){  
                                $imgs = [
                                    'img' => $image->mul_nombre,
                                    'id' => $image->mul_id
                                ];
                                array_push($img, $imgs);
                            }
                        }
                        $lista = [
                            'nombre' => $pro->prod_nombre,
                            'subCategoria' => $pro->sub_nombre,
                            'cantidad' => $pro->prod_cantidad,
                            'valor' => $pro->prod_valor,
                            'id' => $pro->prod_id,
                            'imagenes' => $img
                        ];
                        array_push($listaProductos, $lista);
                }
                $data = array(
                    'status'    =>  'success',
                    'code'      =>  200,
                    'productos'    =>  $listaProductos
                );
            } else {
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  400,
                    'messaje'    =>  'No hay datos'
                );
            }
                
        } else {
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'messaje'    =>  'No hay datos'
            );
        }
    }
        //var_dump($negociosActivos);
        return response()->json($data, $data['code']);
    }

    public function getCiudades(){
        $ciudades = DB::table('ciudades')->get();
        if(is_object($ciudades)){
            $data = array(
                'status'    =>  'success',
                'code'      =>  200,
                'ciudades'    =>  $ciudades
            );
        } else {
            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'messaje'    =>  'No hay datos'
            );
        }
        return response()->json($data, $data['code']);
        
    }

    public function allCategorias(){
        $categorias = Categoria::get();
        if(is_object($categorias)){
            $data = array(
                'code' => 200,
                'status' => 'success',
                'categorias' => $categorias
            );
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'messaje' => 'No se encontraron categorias'
            );
        }
        return response()->json($data, $data['code']);
    }
    public function getSubCategoriasNeg(Request $request, $id){
        //Comprobar si el usuario esta identificado.
        $token = $request->header('Authorization');
        $jwtAuthUsuario = new \JwtAuthUsuario();
        $checkToken = $jwtAuthUsuario->checkToken($token);
        if($checkToken){  
            //sacar usuario identificado
            $usuario = $jwtAuthUsuario->checkToken($token, true);
            $sub = Sub_categoria::where('sub_cat_id', '=', $id)->get();
            if(is_object($sub)){
                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'subCategoria' => $sub
                );
            } else {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'messaje' => 'No se encontraron sub categorias'
                );
            }
            return response()->json($data, $data['code']);

        }
    }


    //editar negocio_categorias
    public function updateRelCatNegocio(Request $request, $id){
        //Comprobar si el usuario esta identificado.
        $token = $request->header('Authorization');
        $jwtAuthUsuario = new \JwtAuthUsuario();
        $checkToken = $jwtAuthUsuario->checkToken($token);

        if($checkToken){ 
            $usuario = $jwtAuthUsuario->checkToken($token, true); 
            $consulta = DB::table('negocio_categoria')->where('rel_neg_nit',$usuario->neg_nit )->where('rel_cat_id', $id)->first();
            if(is_object($consulta)){
               //echo $consulta->estado;
                $query = array(
                    'estado'=> !$consulta->estado,
                );
                    $resultado = DB::table('negocio_categoria')->where('rel_neg_nit','1111111-1')->where('rel_cat_id', $id)->update($query);
                    if($resultado){
                        $data = array(
                            'code' => 200,
                            'status' => 'success',
                            'messaje' => 'cambio exitoso'
                        );
                    }else {
                        $data = array(
                            'code' => 400,
                            'status' => 'error',
                            'messaje' => 'error al intentar editar'
                        );
                    }
                
            } else {
                //realizar una relacion
                $relNegCat = DB::table('negocio_categoria')->insert([
                    'rel_neg_nit' => $usuario->neg_nit,
                    'rel_cat_id' => $id,
                    'estado' => 1
                   ]);
                if($relNegCat){
                    //echo "Registro exitoso  ". $usuario;
                    $data = array(
                        'code' => 200,
                        'status' => 'success',
                        'messaje' => 'cambio exitoso'
                    );
                } else {
                    $data = array(
                        'code' => 400,
                        'status' => 'error',
                        'messaje' => 'error al intentar editar'
                    );
                }

            }
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'messaje' => 'usuario no identificado'
            );
        }

        return response()->json($data, $data['code']);
    }

   






    
}
