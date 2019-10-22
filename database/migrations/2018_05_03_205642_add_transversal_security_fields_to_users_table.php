<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransversalSecurityFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function(Blueprint $table) {
            $table->integer('ts_id')->unsigned()->nullable()->comment('ID del usuario en seguridad transversal');
            $table->string('first_name')->nullable()->comment('Nombres del usuario');
            $table->string('last_name')->nullable()->comment('Apellidos del usuario');
            $table->text('profiles')->nullable()->comment('Perfiles vinculados al usuario');
            $table->text('actions')->nullable()->comment('Acciones vinculados al usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            /*$table->dropColumn('actions');
            $table->dropColumn('profiles');
            $table->dropColumn('last_name');
            $table->dropColumn('first_name');
            $table->dropColumn('ts_id');*/
        });
    }
}
