<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRationsTable extends Migration
{
    public function up()
    {
        Schema::create('rations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->date('cooking_date');
            $table->date('delivery_date');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rations');
    }
}
