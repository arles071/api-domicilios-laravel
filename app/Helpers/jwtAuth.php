<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\BD; //Habilito la consulta a la base de datos
use App\Cliente;

class JwtAuth{
    public $key;
    public function __construct() {
        $this->key = 'esta_es_una_clave_super_secreta-07121';
    }

    public function signup($email, $password, $getToken = null){
        // Buscar si existe el usuario con sus credenciales
        $cliente = Cliente::where([
            'cli_correo'     =>  $email,
            'cli_contrasena'  =>  $password
        ])->first(); //first coge el primer objeto y listo.

         //Comprobar si son correctas (Objeto)
         $signup = false;
         if(is_object($cliente)){
             $signup = true;
         }

         // Generar el token con los datos del usuario identificado
        if($signup){
            $token = array(
                'cli_id'   =>  $cliente->cli_id, // hace referencia al id del usuario
                'cli_nombres' =>  $cliente->cli_nombres,
                'cli_apellidos'  =>  $cliente->cli_apellidos,
                'cli_correo'   => $cliente->cli_correo,
                'iat'       => time(), // tiempo que fue creado el toquen
                'exp'       =>  time() + (7 * 24 * 60 * 60) //tiempo que caducara el toquen en este caso en una semana caduca el toquen
            );
            
            $jwt = JWT::encode($token, $this->key, 'HS256'); //libreria de jwt el key esta en el constructor y es una clave secreta que creamos nosotros mismos algoritmo de encriptacion 'HS256'
            $decoded = JWT::decode($jwt, $this->key, ['HS256']); //decodifico la variable
            
            if(is_null($getToken)){
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = array(
              'status' => 'error',
              'message' =>  'Login incorrecto'
            );
        }
    // Devolvel los datos del usuario decodificado o el token, en funcion de un parametro
        
        return $data;
  
    }


    // validamos si el toquen existe es verdadero
    public function checkToken($jwt, $getIdentify = false){
        $auth = false;

        try{
            $jwt = str_replace('"','', $jwt); // quito las commilas
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        }
        catch (\UnexpectedValueException $e){
            $auth = false;
        }
        catch (\DomainException $e){
            $auth = false;
        }
        
        if(!empty($decoded) && is_object($decoded) && isset($decoded->cli_id)){
            $auth = true;
        } else {
            $auth = false;
        }
        if($getIdentify){
            return $decoded;
        }
        return $auth;
 
        
    }
}


?>