<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->integer('permission_id')->unsigned()->index()->comment('Identificador de la tabla (permissions)');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->integer('role_id')->unsigned()->index()->comment('Identificador de la tabla (roles)');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::drop('permission_role');
    }
}
