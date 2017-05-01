<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Resources extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('resources', function (Blueprint $table) {
      $table->increments('id')->unsigned();
      $table->string('country');
      $table->string('countryFlag');
      $table->string('sessions');
      $table->string('uptime');
      $table->string('downloadUrl');
      $table->string('rsettings')->nullable();
      $table->integer('resourceType')->unsigned();
      $table->foreign('resourceType')->references('id')->on('resourcetypes');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('resources');
  }

}
