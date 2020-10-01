<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transacione extends Model
{
    protected $table = "transaciones";
    const UPDATED_AT = null;
    
    protected $fillable = [
        'tra_fechaInicio', 'tra_fechaHasta', 'tra_valor', 'tra_suscripcion'
    ];
}
