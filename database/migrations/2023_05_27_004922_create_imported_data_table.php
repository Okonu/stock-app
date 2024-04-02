<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportedDataTable extends Migration
{
    public function up()
    {
        Schema::create('imported_data', function (Blueprint $table) {
            $table->id();
            $table->json('rows')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('imported_data');
    }
}

