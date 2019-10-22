<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->string('log_name')->nullable()->comment('Nombre del Log');
            $table->text('description')->comment('Texto que indica la acción realizada');
            $table->integer('subject_id')->nullable()->comment('ID del modelo');
            $table->string('subject_type')->nullable()->comment('Nombre del modelo');
            $table->integer('causer_id')->nullable()->comment('ID del usuario');
            $table->string('causer_type')->nullable()->comment('Tipo de usuario');
            $table->text('properties')->nullable()->comment('Campos que se vieron afectados');
            $table->timestamps(3);

            $table->index('log_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('activity_log');
    }
}
