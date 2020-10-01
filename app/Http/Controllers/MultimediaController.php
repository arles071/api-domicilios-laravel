<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Multimedia;
use App\Multimedia_producto;
use DB;

class MultimediaController extends Controller
{
    public function getMultimedia($id){
        $imagenes = Multimedia::select('mul_nombre', 'mul_id')->where('mul_id_producto', '=', $id)->get();
        //$multimedia = Multimedia::get();
        //var_dump($imagenes);
        if(is_object($imagenes) && !$imagenes->isEmpty()){
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Consulta exitosa',
                'imagenes' => $imagenes
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No se encontraron datos'
            ];
        }
        
        return response()->json($data, $data['code']);
    }
    //registro la imagen y creao la relación
    public function insertMultimedia(Request $request){

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
       
        
        $validator = \Validator::make($params_array, [
            'mul_nombre' => 'required',
            'mpro_prod_id' => 'required'
        ]);
        if($validator->fails()){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Faltan datos'
            ];
        } else {

        $imagen = new Multimedia();
        $imagen->mul_id_producto = $params->mpro_prod_id;
        $imagen->mul_nombre = $params->mul_nombre;
        $imagen->mul_url = "";
        if($imagen->save()){
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Registro exitoso',
                'imagen' => $imagen
            ];
        }
        
        }
        return response()->json($data, $data['code']);
    }
    public function deleteMultimedia($id){
        $imagen = Multimedia::where('mul_id', "=", $id)->first();
        if(is_object($imagen)){
            $nombreImagen = $imagen->mul_nombre;
            //echo $nombreImagen . "\n";
            $ruta = storage_path().'\app\images/'.$nombreImagen;
            if (@getimagesize($ruta)) {
                unlink($ruta);
                $multimedia = Multimedia::where('mul_id', $id);
                $multimedia->delete();
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Imagen eliminada'
                ];
            }else{
                $multimedia = Multimedia::where('mul_id', $id);
                $multimedia->delete();
                $data = [
                    'code' => 400,
                    'status' => 'success',
                    'message' => 'Imagen no existe'
                ];
            }
            return response()->json($data, $data['code']);
        }
    }
}
        
        
       