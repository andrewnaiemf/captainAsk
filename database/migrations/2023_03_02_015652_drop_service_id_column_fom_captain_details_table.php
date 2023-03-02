<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropServiceIdColumnFomCaptainDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('captain_details', function (Blueprint $table) {
            $table->dropColumn('service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('captain_details', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id');
        });
    }
}
