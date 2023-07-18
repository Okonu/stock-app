<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordResetFieldsToSeekers extends Migration
{
    public function up()
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->string('otp')->nullable();
            $table->string('password_reset_token')->nullable();
            // You can add additional fields if needed
        });
    }

    public function down()
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->dropColumn('otp');
            $table->dropColumn('password_reset_token');
            // Drop additional fields if added in the up() method
        });
    }
}

