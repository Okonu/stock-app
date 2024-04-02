<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id');
            $table->foreignId('warehouse_bay_id');
            $table->foreignId('owner_id');
            $table->foreignId('garden_id');
            $table->foreignId('grade_id');
            $table->foreignId('package_id');
            $table->string('invoice');
            $table->integer('qty');
            $table->string('year');
            $table->text('remark');
            // $table->tinyInteger('mismatch')->default(0);
            $table->string('mismatch')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
