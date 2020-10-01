<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaciones', function (Blueprint $table) {
            $table->increments('tra_id');
            $table->dateTime('tra_fechaInicio');
            $table->dateTime('tra_fechaHasta');
            $table->float('tra_valor');
            $table->unsignedInteger('tra_suscripcion');
            $table->timestamps();
            $table->foreign('tra_suscripcion')->references('sus_id')->on('suscripciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaciones');
    }
}
