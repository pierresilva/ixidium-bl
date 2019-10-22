<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id')->comment('Identificador de la tabla');
            $table->string('type')->comment('Tipo de notificación');
            $table->morphs('notifiable');
            $table->text('data')->comment('Texto vinculado a la notifiacación');
            $table->timestamp('read_at', 3)->nullable()->comment('Fecha en la que se visualizó la notificación');
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
        Schema::dropIfExists('notifications');
    }
}
