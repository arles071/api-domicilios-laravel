<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNegociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('negocios', function (Blueprint $table) {
            $table->string('neg_nit',15)->primary();
            $table->string('neg_nombre',30);
            $table->UnsignedInteger('neg_ciudad');
            $table->string('neg_direccion', 50);
            $table->string('neg_usu_id',10);
            $table->text('neg_logo');
            $table->timestamps();
            $table->foreign('neg_ciudad')->references('id')->on('ciudades');
            $table->foreign('neg_usu_id')->references('usu_id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('negocios');
    }
}
