<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    protected $table = "clientes";

    protected $fillable = [
        'cli_id', 'cli_nombres', 'cli_apellidos', 'cli_correo', 'cli_contrasena',
    ];
}
