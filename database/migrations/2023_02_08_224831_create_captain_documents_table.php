<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaptainDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captain_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('captain_id')->nullable()->constrained('users')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->enum('type',['Profile','Car_license_front','Car_license_back','Captain_license','Car_form','Insurance_documentation','personal_id_front','personal_id_back'])->default('Profile');
            $table->string('path');
            $table->enum('status',['New','Pending','Rejected','Accepted'])->default('New');
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
        Schema::dropIfExists('captain_documents');
    }
}
