<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Suscripcione;
use App\transacione;

class SuscripcionesModel extends Model
{
    private $id_usuario;
    private $nombre_usuario;
    private $apellido_usuario;
    private $correo;
    private $contrasena;
    private $nit;
    private $nombre_negocio;
    private $direccion_negocio;
    private $idCiudad;

    public function __construct($id_usuario, $nombre_usuario, $apellido_usuario, $correo, $contrasena, $nit, $nombre_negocio, $direccion_negocio, $idCiudad){
        $this->id_usuario = $id_usuario;
        $this->nombre_usuario = $nombre_usuario;
        $this->apellido_usuario = $apellido_usuario;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->nit = $nit;
        $this->nombre_negocio = $nombre_negocio;
        $this->direccion_negocio = $direccion_negocio;
        $this->idCiudad = $idCiudad;
    }
    
    public function usuario(){
        $usu = DB::table('usuarios')->select('usu_id')->where('usu_id', '=', $this->id_usuario)->first();
        
        if(is_object($usu) && !empty($usu)){
            echo "Usuario ya registrado ";
            return false;
            
        } else {
            $pass = hash('sha256', $this->contrasena);
            $usuario = DB::table('usuarios')->insert([
                'usu_id' => $this->id_usuario,
                'usu_nombres' => $this->nombre_usuario,
                'usu_apellidos' => $this->apellido_usuario,
                'usu_correo' => $this->correo,
                'usu_contrasena' => $pass,
                'usu_rol_id' => 1,
                'usu_foto' => ''
               ]);
            if($usuario){
                //echo "Registro exitoso  ". $usuario;
                return true;
            }  
        }  
    }

    // registrar el negocio
    public function registrarNegocio(){
        $logo = "https://i.eldiario.com.ec/fotos-manabi-ecuador/2015/10/20151007102105_cadena-de-restaurantes-ofrece-comid.jpg";
        $negocio = DB::table('negocios')->select('neg_nit')->where('neg_nit', '=', $this->nit)->first();
        if(is_object($negocio)){
            echo "negocio ya registrado ";
            //return false;
        } else {
        DB::table('negocios')->insert([
            'neg_nit' => $this->nit,
            'neg_nombre' => $this->nombre_negocio,
            'neg_direccion' => $this->direccion_negocio,
            'neg_ciudad' => $this->idCiudad,
            'neg_usu_id' => $this->id_usuario,
            'neg_logo' => $logo
        ]);
        return true;
        }
    }

    //registrar la suscripcion
    public function registrarSuscripcion(){
        $suscripcion = DB::table('suscripciones')->select('sus_id')->where('sus_negocio', '=', $this->nit)->first();
        if(is_object($suscripcion)){
            echo "El negocio ya tiene una suscripcion";
        } else {
        $sus = Suscripcione::create([
            'sus_negocio' => $this->nit,
            'sus_estado' => 'pendiente',
            'sus_fechaVencimiento' => '2020-08-10'
        ]);
        return $sus->id;
        }
    }
    public static function getTransacion($id_sus){
        if(empty($id_sus)){
            echo "El id se la transacion esta vacio";
        }else {
            
            //echo " transacion exitosa";
           $fechaActual = date("Y-m-d H:i:s"); //fecha actual
            $diasMes = date('t'); //dias que tiene el mes actual
            $nuevafecha = strtotime ( '+'.$diasMes.' day' , strtotime ( $fechaActual ) ) ; //sumo los dias a la fecha actual
            $nuevafecha = date ( 'Y-m-j H:i:s' , $nuevafecha ); //guardo la nueva fecha
            
            transacione::create([
                'tra_fechaInicio' => $fechaActual,
                'tra_fechaHasta' => $nuevafecha,
                'tra_valor' => 25000,
                'tra_suscripcion' => $id_sus
            ]);
            return $nuevafecha;
            
        }
        
    }
    public static function editSuscripcion($id_sus, $nuevafecha){
        //echo " hola ".$id_sus;
        $datos = array(
            'sus_estado' => 'Activo',
            'sus_fechaVencimiento' => $nuevafecha
        );
        $user_update = Suscripcione::where('sus_id', $id_sus)->update($datos);
    }
    

}
