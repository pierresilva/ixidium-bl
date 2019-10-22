<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_models', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->string('fqn')->comment('Modelo');
            $table->integer('created_by')->unsigned()->nullable()->comment('Fecha de creación');
            $table->integer('updated_by')->unsigned()->nullable()->comment('Fecha de modificación');
            $table->integer('deleted_by')->unsigned()->nullable()->comment('Fecha de eliminación');
            $table->timestamps(3);
            $table->softDeletes('deleted_at', 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_models');
    }
}
