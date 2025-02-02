<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffsTable extends Migration
{
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('ration_name');
            $table->boolean('cooking_day_before')->default(false);
            $table->timestamps();
        });

        // Можно добавить сидер или отдельную миграцию для заполнения тарифов,
        // например:
        DB::table('tariffs')->insert([
            ['ration_name' => 'Стандарт', 'cooking_day_before' => false, 'created_at' => now(), 'updated_at' => now()],
            ['ration_name' => 'Премиум', 'cooking_day_before' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('tariffs');
    }
}
