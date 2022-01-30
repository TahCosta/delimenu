<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inputs', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->string('measure');
            $table->integer('type');
            $table->decimal('packsize', 15, 2);
            $table->decimal('pack_cost', 15, 2);
            $table->decimal('unity_cost', 15, 5);
            $table->text('preparation')->nullable();
            $table->integer('company_id')->nullable();
            $table->char('stock_control')->nullable();
            $table->integer('min_stock')->nullable();
            $table->integer('provider_id')->nullable();
            $table->integer('category_id')->nullable();
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
        Schema::dropIfExists('inputs');
    }
}
