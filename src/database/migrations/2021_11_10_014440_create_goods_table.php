<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity');
            $table->string('unit');
            $table->double('collectedMoney')->default(0);
            $table->double('fare');
            $table->double('fareOfCar')->default(0);
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('car_id')->nullable();
            $table->date('loadCarDay')->nullable();
            $table->date('confirmDay')->nullable();
            $table->date('confirmCarPayWareHouseDay')->nullable();
            $table->integer('user_confirm_id')->nullable();
            $table->integer('user_load_car_id')->nullable();
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
        Schema::dropIfExists('goods');
    }
}
