<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\jwtAuthUsuario;

class UsuarioController extends Controller
{

    public function __construct(){
        $this->middleware('apiusuario.auth', ['except' => [
            'index',
            'show',
            'login'
        ]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo "hola soy el usuario";
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        
        $usuarioIdentificado = new JwtAuthUsuario();
        $token = $request->header('Authorization', null);
        $usuario = $usuarioIdentificado->checkToken($token, true);

        echo "editando usuario ".$usuario->neg_nit;
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

    //me logeo como usuario para tener permisos
    public function login(Request $request){
        $jwtAuthUsuario = new \App\Helpers\JwtAuthUsuario();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = \Validator::make($params_array, [
            'usu_correo'     => 'required|email',
            'usu_contrasena'  => 'required'  
        ]);

        if($validate->fails()){
            // La valicación ha fallado
                $signup = array(
                    'status'    =>  'error',
                    'code'      =>  404,
                    'message'   =>  'El usuario no se a podido validar',
                    'errors'    =>  $validate->errors()
                );
        } else {
              
            //paso 3 Cifrar la password
                $pass = hash('sha256', $params->usu_contrasena);
               
            
            //paso 4 Devolver token o datos  
                $signup = $jwtAuthUsuario->signup($params->usu_correo, $pass);
                if(!empty($params->gettoken)){
                    $signup = $jwtAuthUsuario->signup($params->usu_correo, $pass, true);
                }

               
            }
           
            return response()->json($signup, 200);
    }
}
