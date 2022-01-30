<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('op'); //tipo de entrega
            $table->integer('origin_id');
            $table->integer('item_id');
            $table->integer('amount');
            $table->integer('customer_id');
            $table->double('purchase', 15, 2);
            $table->double('discount', 6, 3);
            $table->double('delivery_fee', 6, 3);
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
        Schema::dropIfExists('orders');
    }
}
