<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captain_car_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('captain_id');
            $table->string('color')->nullable();
            $table->string('model')->nullable();
            $table->string('arabic_number')->nullable();
            $table->string('arabic_letters')->nullable();
            $table->string('english_number')->nullable();
            $table->string('english_letters')->nullable();
            $table->foreign('captain_id')->references('id')->on('users');
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
        Schema::dropIfExists('captain_car_details');
    }
}
