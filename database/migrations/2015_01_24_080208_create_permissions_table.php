<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->string('name')->comment('Nombre del permiso');
            $table->string('slug')->unique()->comment('Código del permiso');
            $table->text('description')->nullable()->comment('Descripción del permiso');
            $table->timestamps(3);
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permissions');
    }
}
