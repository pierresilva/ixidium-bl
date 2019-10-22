<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->string('name')->unique()->comment('Nombre del usuario');
            $table->string('email')->unique()->comment('Correo del usuario');
            $table->string('password')->comment('Contraseña del usuario (Codificada SASH1)');
            $table->string('status')->default('active')->comment('Estado del usuario');
            $table->rememberToken()->comment('');
            $table->timestamp('expire_at', 3)->comment('Fecha de expiraciòn del usuario');
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

        Schema::dropIfExists('users');

    }
}
