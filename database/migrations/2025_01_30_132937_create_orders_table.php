<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_phone')->unique();
            $table->unsignedBigInteger('tariff_id');
            $table->enum('schedule_type', ['EVERY_DAY', 'EVERY_OTHER_DAY', 'EVERY_OTHER_DAY_TWICE']);
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->date('first_date')->nullable();
            $table->date('last_date')->nullable();

            $table->foreign('tariff_id')->references('id')->on('tariffs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
