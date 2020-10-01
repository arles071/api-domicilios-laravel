<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->string('usu_id',10)->primary();
            $table->string('usu_nombres', 30);
            $table->string('usu_apellidos', 30);
            $table->string('usu_correo')->unique();
            $table->text('usu_contrasena');
            $table->rememberToken();
            $table->text('usu_foto');
            $table->unsignedInteger('usu_rol_id');
            $table->timestamps();
            $table->foreign('usu_rol_id')->references('rol_id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
