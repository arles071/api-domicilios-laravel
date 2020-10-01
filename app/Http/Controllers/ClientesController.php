<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Cliente;

//USAR CUALQUIERA DE ESTAS DOS IMPORTACIONES PARA PODER USAR CONSULTAS SQL
//use Illuminate\Support\Facades\DB;
use DB;

class ClientesController extends Controller
{
    public function index(){
        $clientes = Cliente::all();
        
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'clientes' => $clientes,
        ]);
    }

    // funcion que optiene por get el cli_usu y busca en la base de datos si existe
    public function show($id){
        $cliente = DB::select('SELECT * FROM clientes WHERE cli_id = '.$id);
       
        if(!empty($cliente)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'cliente' => $cliente
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'messaje' => 'Usuario no encontrado'
            ];
        }
        return response()->json($data, $data['code']);
    }

    //funcion para registrar al cliente
    public function store(Request $request){
        
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
         
        //validar datos
        $validate = \Validator::make($params_array, [
            'cli_id' => 'required',
            'cli_nombres' => 'required',
            'cli_apellidos' => 'required',
            'cli_correo' => 'required',
            'cli_contrasena' => 'required'
        ]);

        if($validate->fails()){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Faltan datos'
            ];
        } else {

            //sifrar contraseña
            $pass = hash('sha256', $params->cli_contrasena);

            //guardar los datos del cliente en la base de datos
            $cliente = new Cliente();
            $cliente->cli_id = $params->cli_id;
            $cliente->cli_nombres = $params->cli_nombres;
            $cliente->cli_apellidos = $params->cli_apellidos;
            $cliente->cli_correo = $params->cli_correo;
            $cliente->cli_contrasena = $pass;
            $cliente->cli_foto = "";
            $cliente->save();
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Registro exitoso'
            ];
        }
        return response()->json($data, $data['code']);
    }
    public function login(Request $request){
        $jwtAuth = new \App\Helpers\JwtAuth();
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = \Validator::make($params_array, [
            'email'     => 'required|email',
            'password'  => 'required'  
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
                $pass = hash('sha256', $params->password);
            
            //paso 4 Devolver token o datos  
                $signup = $jwtAuth->signup($params->email, $pass);
                if(!empty($params->gettoken)){
                    $signup = $jwtAuth->signup($params->email, $pass, true);
                }

               
            }
            
            return response()->json($signup, 200);
    }
    public function update(Request $request){
        //Comprobar si el usuario esta identificado.
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if($checkToken){  
            //sacar usuario identificado
            $cliente = $jwtAuth->checkToken($token, true);
            var_dump($cliente);
            die();
        }
        echo 'hola este es un ejemplo';
        
    }
}
