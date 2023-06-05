<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGeneralizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalizations', function (Blueprint $table) {
            $table->renameColumn('content', 'content_ar');
            $table->renameColumn('title', 'title_ar');

            $table->longText('content_en')->nullable();
            $table->string('title_en')->nullable();
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalizations', function (Blueprint $table) {
            $table->renameColumn('content_ar', 'content');
            $table->renameColumn('title_ar', 'title');

            $table->dropColumn('content_en');
            $table->dropColumn('title_en');
            $table->string('image');

        });
    }
}
