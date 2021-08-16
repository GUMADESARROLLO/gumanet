<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmpMetaExlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_meta_exl', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mes','15')->nullable();
            $table->string('anno','4')->nullable();
            $table->string('ruta','10')->nullable();
            $table->string('codigo','10')->nullable();
            $table->string('cliente','100')->nullable();
            $table->string('articulo','255')->nullable();
            $table->text('descripcion')->nullable();
            $table->double('valor','12','4')->nullable();
            $table->double('unidad','12','4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmp_meta_exl');
    }
}
