<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $table = 'negocios';

    protected $fillable = [
        'neg_nit', 'neg_nombre', 'neg_direccion'
    ];

    public function suscripciones(){
        return $this->hasMany(Suscripcione::class, 'sus_negocio', 'neg_nit');
    }

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'neg_usu_id', 'usu_id');
    }
}
