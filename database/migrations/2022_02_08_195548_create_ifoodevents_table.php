<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIfoodeventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ifoodevents', function (Blueprint $table) {
            $table->id();
            $table->string('event_id');
            $table->string('order_id');
            $table->string('full_code');
            $table->json('metadata')->nullable();
            $table->dateTime('event_creation');
            $table->integer('user_id');
            $table->integer('company_id');
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
        Schema::dropIfExists('ifoodevents');
    }
}
