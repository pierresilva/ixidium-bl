<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('sigas', function (Blueprint $table) {
      $table->increments('id');
      $table->string('document_type');
      $table->string('first_name');
      $table->string('second_name')->nullable();
      $table->string('fist_surname');
      $table->string('second_surname')->nullable();
      $table->timestamp('birthday')->nullable();
      $table->string('gender')->nullable();
      $table->boolean('grace_period')->default(false);
      $table->string('category')->nullable();
      $table->unsignedInteger('contributor_id')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('sigas');
  }
}
