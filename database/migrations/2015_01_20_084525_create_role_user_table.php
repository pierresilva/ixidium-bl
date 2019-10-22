<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->integer('role_id')->unsigned()->index()->comment('Identificador de la tabla (roles)');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index()->comment('Identificador de la tabla (users)');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('role_user');
    }
}
