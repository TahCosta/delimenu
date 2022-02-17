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
            $table->string('display_id')->nullable();
            $table->string('type'); //tipo do pedido
            $table->integer('origin_id')->nullable();
            $table->string('status'); //status do pedido
            $table->string('cancel_reason')->nullable(); 
            $table->dateTime('cancel_expiration')->nullable();
            $table->integer('delivery_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('paymethod_id')->nullable();
            $table->double('total', 15, 2); //valor com descontos e tx entrega
            $table->double('prepaid', 15, 2)->nullable(); //valor pago online
            $table->double('pending', 15, 2)->nullable(); //valor a ser pago na entrega
            $table->double('discount_store', 6, 3);
            $table->double('discount_delivery', 6, 3);
            $table->double('aditional_fees', 6, 3);
            $table->double('delivery_fee', 6, 3);
            $table->dateTime('preparation_start')->nullable();
            $table->dateTime('delivery_time')->nullable();
            $table->string('delivered_by')->nullable();
            $table->string('delivery_type')->nullable();
            $table->text('observations')->nullable();
            $table->text('extra_info')->nullable();
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
