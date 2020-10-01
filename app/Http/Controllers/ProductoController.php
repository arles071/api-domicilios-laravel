<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\jwtAuthUsuario;
use App\Helpers\ObjMultimedia;
use App\Producto;
use App\Multimedia;

class ProductoController extends Controller
{

    public function __construct(){
        $this->middleware('apiusuario.auth', ['except' => [
            'index',
            'show',
            'upload',
            'getImagen',
            'setUpload',
            'getImagenProducto'
        ]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    
    //registro un nuevo producto
    public function store(Request $request)
    {
        //echo "registro el producto";
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        
        $validator = \Validator::make($params_array, [
            'prod_idSub' => 'required',
            'prod_nombre' => 'required',
            'prod_cantidad' => 'required',
            'prod_valor' => 'required'
        ]);

        if($validator->fails()){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Faltan datos'
            ];
        } else {
            $usuarioIdentificado = new JwtAuthUsuario();
            $token = $request->header('Authorization', null);
            $usuario = $usuarioIdentificado->checkToken($token, true);

            $producto = new Producto();
            $producto->prod_nombre = $params->prod_nombre;
            $producto->prod_idSub = $params->prod_idSub;
            $producto->prod_cantidad = $params->prod_cantidad;
            $producto->prod_valor = $params->prod_valor;
            $producto->prod_nitNegocio = $usuario->neg_nit;
            $producto->save();
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Registro exitoso',
                'producto' => $producto
            ];
        }
        return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function upload(Request $request){
        $image = $request->file('file0');
        //var_dump($image);
        //return response()->json($image);

        $validate = \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);
        //Guardar en un disco
        if(!$image || $validate->fails()){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir la imagen'
            ];
        } else {
            $image_name = time().$image->getClientOriginalName();
            
            // guardo la imagen es el storage
            \Storage::disk('images')->put($image_name, \File::get($image));
            
            
            

            $data = [
                'code' => 200,
                'status' => 'success',
                'imagen' => $image_name
            ];
        }
        //Devolver datos
        return response()->json($data, $data['code']);
    }

    //optenemos la imagen
    public function getImagen($filename){
        // comprobar si existe la imagen
        $isset = \Storage::disk('images')->exists($filename);

        if($isset){

        // conseguir la imagen
            $file = \Storage::disk('images')->get($filename);
        // Devolvel la imagen
            return new Response($file, 200);
        } else {
        // Mostrar error
        $data = [
            'code' => 404,
            'status' => 'error',
            'message' => 'La imagen no existe'
        ];
        return response()->json($data, $data['code']);
        }


    }

    public function setUpload(Request $request){
        var_dump($request);
        //var_dump($request->input);

        
    /*
            $image_name = time().$image->getClientOriginalName();

            // guardo la imagen es el storage
            \Storage::disk('images')->put($image_name, \File::get($image));

            $data = [
                'code' => 200,
                'status' => 'success',
                'imagen' => $image_name
            ];*/
            //return response()->json('todo va muy bien');
    }
    public function getImagenProducto(){
        $listaProductos = array();
        $productos = Producto::where('prod_nitNegocio', '1111111-1')->get();
            
        if(is_object($productos)){

        if(count($productos) != 0){
            foreach($productos as $pro){
                
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
                $listaProductos = [
                    'nombre' => $pro->prod_nombre,
                    'imagenes' => $img
                ];
                //var_dump($imagenes);
                $data = json_encode($listaProductos);
                print_r($data);  
            }
        } else {
            echo 'no hay datos';
        }
            
        } 
        /*if(is_object($productos)){
            foreach($productos as $pro){
                $prod = new ObjMultimedia();
                $prod->setIdProducto($pro->prod_id);
                $prod->setNombre($pro->prod_nombre);
                $prod->setCantidad($pro->prod_cantidad);
                $imagenes = Multimedia::where('mul_id_producto', $pro->prod_id)->get();
                $arrayImagen = array();
                foreach($imagenes as $img){
                    //echo $img->mul_nombre;
                    array_push($arrayImagen, $img->mul_nombre);
                }
                $prod->setImagenes($arrayImagen);
                array_push($listaProductos, $prod);
                return response()->json($listaProductos);
            }
            
        }*/
        //$datos = json_decode($listaProductos, true);
        //print_r($datos);   
        
        
        
        //echo $prod->getNombre();
    }
}
