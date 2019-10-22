<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('Identificador de la tabla');
            $table->string('queue')->index()->comment('');
            $table->longText('payload')->comment('Estructura del correo');
            $table->unsignedTinyInteger('attempts')->comment('Adjuntos vinculados al correo');
            $table->unsignedInteger('reserved_at')->nullable()->comment('Fecha de reserva');
            $table->unsignedInteger('available_at')->comment('Fecha disponibilidad');
            $table->unsignedInteger('created_at')->comment('Fecha de creación');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
