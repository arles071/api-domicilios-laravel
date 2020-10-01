<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('prod_id');
            $table->unsignedInteger('prod_idSub');
            $table->string('prod_nombre', 50);
            $table->integer('prod_cantidad');
            $table->float('prod_valor');
            $table->string('prod_nitNegocio', 15);
            $table->timestamps();
            $table->foreign('prod_idSub')->references('sub_id')->on('sub_categorias');
            $table->foreign('prod_nitNegocio')->references('neg_nit')->on('negocios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
