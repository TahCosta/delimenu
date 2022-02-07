<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('address_num')->nullable();
            $table->string('address_complement')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('state')->nullable();
            $table->string('document')->nullable();
            $table->string('fb')->nullable();
            $table->string('ig')->nullable();
            $table->string('other')->nullable();
            $table->string('phone')->nullable();
            $table->string('localizer')->nullable();
            $table->string('whatsapp')->nullable();
            $table->integer('order_count')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
