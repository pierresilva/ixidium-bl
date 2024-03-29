<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->string('name')->unique()->comment('Nombre del rol');
            $table->string('slug')->unique()->comment('Código del rol');
            $table->text('description')->nullable()->comment('Descripción del rol');
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
        Schema::drop('roles');
    }
}
