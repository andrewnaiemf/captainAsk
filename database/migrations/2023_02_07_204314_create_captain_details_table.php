<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaptainDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captain_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('captain_id')->nullable()->constrained('users')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->string('car_license_front');
            $table->string('car_license_back');
            $table->string('captain_license');
            $table->string('car_form');
            $table->string('insurance_documentation');
            $table->softDeletes();
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
        Schema::dropIfExists('captain_details');
    }
}
