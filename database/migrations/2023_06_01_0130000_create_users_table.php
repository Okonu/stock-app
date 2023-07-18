<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('level')->nullable();
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('company_type_id');
            $table->string('business_email');
            $table->string('telephone');
            $table->string('business_registration_files');
            $table->string('business_identification_number');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('company_type_id')->references('id')->on('company_types');
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
        Schema::dropIfExists('users');
    }
};
