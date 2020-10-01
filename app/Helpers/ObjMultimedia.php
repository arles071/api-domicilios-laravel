<?php
namespace App\Helpers;
class ObjMultimedia {
    private $idProducto;
    private $nombre;
    private $cantidad;
    private $valor;
    private $imagenes = array();

    public function setIdProducto($idProducto){
        $this->idProducto = $idProducto;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }
    public function setValor($valor){
        $this->valor = $valor;
    }
    public function setImagenes($imagenes){
        $this->imagenes = $imagenes;
    }
    public function getNombre(){
        return $this->nombre;
    }
}

?>