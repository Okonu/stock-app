<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('warehouse_id')->unsigned();
            $table->integer('bay_id')->unsigned();
            $table->integer('owner_id')->unsigned();
            $table->integer('garden_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->integer('package_id')->unsigned();
            $table->string('invoice');
            $table->integer('qty');
            $table->string('year');
            $table->text('remark');
            $table->timestamps();

            // $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('bay_id')->references('id')->on('bays')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
            $table->foreign('garden_id')->references('id')->on('gardens')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
