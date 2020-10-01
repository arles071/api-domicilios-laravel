<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suscripcione extends Model
    
{
    protected $table = 'suscripciones';

    protected $fillable = [
        'sus_id', 'sus_negocio', 'sus_estado', 'sus_fechaVencimiento'
    ];
    public function negocio(){
        return $this->belongsTo(Negocio::class, 'sus_negocio', 'neg_nit');
    }
 

}
