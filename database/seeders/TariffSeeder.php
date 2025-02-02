<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tariff;

class TariffSeeder extends Seeder
{
    public function run()
    {
        // Удаление записей из таблицы tariffs
        Tariff::query()->delete();

        // Добавление тестовых данных
        Tariff::create([
            'ration_name' => 'Basic Plan',
            'cooking_day_before' => false,
        ]);

        Tariff::create([
            'ration_name' => 'Premium Plan',
            'cooking_day_before' => true,
        ]);
    }
}