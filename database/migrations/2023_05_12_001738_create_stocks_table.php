<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('warehouse_id');
            $table->unsignedInteger('bay_id');
            $table->unsignedInteger('owner_id');
            $table->unsignedInteger('garden_id');
            $table->unsignedInteger('grade_id');
            $table->unsignedInteger('package_id');
            $table->string('invoice');
            $table->integer('qty');
            $table->string('year');
            $table->text('remark');
            $table->tinyInteger('mismatch')->default(0);
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('bay_id')->references('id')->on('bays');
            $table->foreign('owner_id')->references('id')->on('owners');
            $table->foreign('garden_id')->references('id')->on('gardens');
            $table->foreign('grade_id')->references('id')->on('grades');
            $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
