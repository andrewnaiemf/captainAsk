<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsTypeInTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string("start_lat")->change();
            $table->string("start_lng")->change();
            $table->string("end_lat")->change();
            $table->string("end_lng")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string("start_lat")->change();
            $table->string("start_lng")->change();
            $table->string("end_lat")->change();
            $table->string("end_lng")->change();        });
    }
}
