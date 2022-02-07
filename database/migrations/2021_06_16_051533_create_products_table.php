<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product');
            $table->string('img')->nullable();
            $table->text('description')->nullable();
            $table->string('pdv')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('type'); //produto, complemento, recheio
            $table->integer('stock')->nullable();
            $table->integer('low_stock')->nullable();
            $table->decimal('sell', 15, 2);
            $table->decimal('cost', 15, 2);
            $table->text('preparation')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('products');
    }
}
