<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNegocioCategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('negocio_categoria', function (Blueprint $table) {
            $table->string('rel_neg_nit', 15);
            $table->unsignedInteger('rel_cat_id');
            $table->boolean('estado');
            $table->timestamps();
            
            $table->foreign('rel_neg_nit')->references('neg_nit')->on('negocios');
            $table->foreign('rel_cat_id')->references('cat_id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('negocio_categoria');
    }
}
