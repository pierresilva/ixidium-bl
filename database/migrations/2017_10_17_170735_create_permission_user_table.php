<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionUserTable extends Migration
{
    public function up()
    {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->integer('permission_id')->unsigned()->index()->comment('Identificador de la tabla (permissions)');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index()->comment('Identificador de la tabla (users)');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('permission_user');
    }

}
