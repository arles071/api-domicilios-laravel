<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\BD;
use App\usuario;
use App\Negocio;
class jwtAuthUsuario {
    public $key;
    public function __construct(){
        $this->key = "esta_es_una_clave_super_secreta-07121";
    }

    public function signup($email, $password, $getToken = null){
        // Buscar si existe el usuario con sus credenciales
        $usu = usuario::where([
            'usu_correo'     =>  $email,
            'usu_contrasena'  =>  $password
        ])->first(); //first coge el primer objeto y listo.
        

         //Comprobar si son correctas (Objeto)
         $signup = false;
         if(is_object($usu)){
             $negocio = Negocio::where([
                'neg_usu_id' => $usu->usu_id
            ])->first();
            if(is_object($negocio)){
                $signup = true;
            }
         }

         // Generar el token con los datos del usuario identificado
        if($signup){
            $token = array(
                'usu_id'   =>  $usu->usu_id, // hace referencia al id del usuario
                'usu_nombres' =>  $usu->usu_nombres,
                'usu_apellidos'  =>  $usu->usu_apellidos,
                'usu_correo'   => $usu->usu_correo,
                'neg_nit' => $negocio->neg_nit,
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
        
        if(!empty($decoded) && is_object($decoded) && isset($decoded->usu_id)){
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