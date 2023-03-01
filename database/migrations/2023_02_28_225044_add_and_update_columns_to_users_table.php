<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndUpdateColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('device_token')->nullable()->change();
            $table->string('f_name')->after('device_token');
            $table->renameColumn('name','l_name');
			$table->boolean('verified')->default(false)->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('device_token')->nullable(false)->change();
            $table->string('f_name')->after('device_token');
            $table->renameColumn('name','l_name');
			$table->boolean('verified')->default(false)->after('phone');;
        });
    }
}
