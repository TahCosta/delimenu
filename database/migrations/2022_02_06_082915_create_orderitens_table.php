<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderitensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderitens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order_id');
            $table->string('item_id');
            $table->integer('product_id')->nullable();
            $table->boolean('is_complement');
            $table->integer('complement_for')->nullable();
            $table->integer('ammount');
            $table->integer('price');
            $table->text('observations')->nullable();
            $table->integer('user_id');
            $table->integer('company_id')->nullable();
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
        Schema::dropIfExists('orderitens');
    }
}
