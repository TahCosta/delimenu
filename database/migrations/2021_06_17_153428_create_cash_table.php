<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->text('description')->nullable();
            $table->decimal('op_value', 15, 2);
            $table->string('tipo_op'); //entrada/saída
            $table->integer('user_id');
            $table->integer('category_id')->nullable();
            $table->integer('provider_id')->nullable();
            $table->text('obs_op');
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
        Schema::dropIfExists('cash');
    }
}
