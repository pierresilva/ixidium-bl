<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepoReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_reports', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->string('name')->nullable()->comment('Indica el nombre del reporte');
            $table->string('connection')->nullable()->comment('Indica la conexión del reporte');
            $table->string('module')->nullable()->comment('Indica el módulo del reporte');
            $table->text('description')->nullable()->comment('Indica la descripción del reporte');
            $table->time('start_at', 3)->nullable()->comment('Indica la fecha inicio del reporte');
            $table->time('end_at', 3)->nullable()->comment('Indica la fecha fin del reporte');
            $table->longText('sql')->nullable()->comment('Indica el SQL del reporte');
            $table->longText('fields')->nullable()->comment('Indica los campos del reporte');
            $table->longText('options')->nullable()->comment('Indica las opciondes del reporte');
            $table->timestamps(3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repo_reports');
    }
}
