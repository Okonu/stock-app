<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seekers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create('seeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('date_of_birth')->nullable();
            $table->string('id_number')->nullable();
            $table->string('school')->nullable();
            $table->string('year_of_completion')->nullable();
            $table->string('resume')->nullable();
            $table->unsignedBigInteger('highest_level_id')->nullable();
            $table->unsignedBigInteger('seeker_id')->nullable();
            $table->timestamps();

            $table->foreign('seeker_id')->references('id')->on('seekers')->onDelete('cascade');
            $table->foreign('highest_level_id')->references('id')->on('highest_levels')->onDelete('cascade');
        });

        Schema::create('seeker_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skill_id');
            $table->unsignedBigInteger('seeker_id');
            $table->timestamps();

            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
            $table->foreign('seeker_id')->references('id')->on('seekers')->onDelete('cascade');
        });

        Schema::create('seeker_interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interest_id');
            $table->unsignedBigInteger('seeker_id');
            $table->timestamps();

            $table->foreign('interest_id')->references('id')->on('interests')->onDelete('cascade');
            $table->foreign('seeker_id')->references('id')->on('seekers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seeker_interests');
        Schema::dropIfExists('seeker_skills');
        Schema::dropIfExists('seekers');
    }
};
