<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tariff;
use App\Models\Ration;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Отображение списка заказов
    public function index()
    {
        $orders = Order::with('tariff')->orderBy('created_at', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    // Форма создания заказа
    public function create()
    {
        $tariffs = Tariff::all();
        // schedule types можно передать как константы
        $scheduleTypes = [
            'EVERY_DAY' => 'Ежедневно',
            'EVERY_OTHER_DAY' => 'Через день (1 рацион)',
            'EVERY_OTHER_DAY_TWICE' => 'Через день (2 рациона, если возможно)',
        ];
        return view('orders.create', compact('tariffs', 'scheduleTypes'));
    }

    // Обработка формы создания заказа
    public function store(Request $request)
    {
        // Валидация данных
        $validated = $request->validate([
            'client_name'   => 'required|string|max:255',
            'client_phone'  => ['required', 'regex:/^\D*7\d{10}$/'], // можно скорректировать, здесь ожидается, что номер начинается с 7
            'tariff_id'     => 'required|exists:tariffs,id',
            'schedule_type' => 'required|in:EVERY_DAY,EVERY_OTHER_DAY,EVERY_OTHER_DAY_TWICE',
            'comment'       => 'nullable|string',
            // Интервалы дат – ожидается массив с элементами, содержащими from и to
            'intervals'   => 'required|array|min:1',
            'intervals.*.from' => 'required|date',
            'intervals.*.to'   => 'required|date|after_or_equal:intervals.*.from',
        ]);

        // Приводим номер телефона к формату – оставляем только цифры
        $client_phone = preg_replace('/\D/', '', $validated['client_phone']);

        // Проверяем уникальность номера телефона
        if (Order::where('client_phone', $client_phone)->exists()) {
            return back()->withErrors(['client_phone' => 'Заказ с таким номером телефона уже существует.'])->withInput();
        }

        // Начинаем транзакцию
        DB::beginTransaction();
        try {
            // Создаем заказ
            $order = Order::create([
                'client_name'   => $validated['client_name'],
                'client_phone'  => $client_phone,
                'tariff_id'     => $validated['tariff_id'],
                'schedule_type' => $validated['schedule_type'],
                'comment'       => $validated['comment'] ?? null,
                // first_date и last_date определим после создания рационов
            ]);

            // Получаем тариф
            $tariff = Tariff::find($validated['tariff_id']);

            // Собираем все даты доставки из переданных интервалов
            $deliveryDates = [];

            foreach ($validated['intervals'] as $interval) {
                $from = Carbon::parse($interval['from']);
                $to   = Carbon::parse($interval['to']);

                // В зависимости от типа расписания рассчитываем даты доставки для этого интервала
                switch ($order->schedule_type) {
                    case 'EVERY_DAY':
                        // Каждая дата в интервале
                        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
                            $deliveryDates[] = $date->copy();
                        }
                        break;

                    case 'EVERY_OTHER_DAY':
                        // Начинаем с даты "from", далее каждые 2 дня
                        for ($date = $from->copy(); $date->lte($to); $date->addDays(2)) {
                            $deliveryDates[] = $date->copy();
                        }
                        break;

                    case 'EVERY_OTHER_DAY_TWICE':
                        // Каждые 2 дня, но добавляем 2 рациона в один день (если позволяет интервал)
                        for ($date = $from->copy(); $date->lte($to); $date->addDays(2)) {
                            // Если дата не последняя в интервале, добавляем два рациона
                            if ($date->copy()->addDay()->lte($to)) {
                                // Добавляем рацион для данного дня и для следующий день – логика доставки на 2 дня питания
                                $deliveryDates[] = $date->copy();
                                // Если следующий день не совпадает с уже добавленной датой, можно добавить отдельно рацион для следующего дня
                                // Но согласно условию, рацион создается по дате доставки, поэтому в этот же день будет два рациона.
                                $deliveryDates[] = $date->copy();
                            } else {
                                // Если дата последняя, добавляем только один рацион
                                $deliveryDates[] = $date->copy();
                            }
                        }
                        break;
                }
            }

            // Сортируем и убираем возможную неоднозначность – здесь возможно, что одни и те же даты встречаются несколько раз
            // (если интервалы пересекаются) – это допустимо, так как по условию на одну дату может быть несколько рационов.
            usort($deliveryDates, function($a, $b) {
                return $a->timestamp - $b->timestamp;
            });

            // Запишем first_date и last_date заказа (минимальная и максимальная дата доставки)
            $firstDate = count($deliveryDates) ? min(array_map(function($d){ return $d->format('Y-m-d'); }, $deliveryDates)) : null;
            $lastDate  = count($deliveryDates) ? max(array_map(function($d){ return $d->format('Y-m-d'); }, $deliveryDates)) : null;

            $order->first_date = $firstDate;
            $order->last_date = $lastDate;
            $order->save();

            // Создаем рацион(ы) питания для каждой даты доставки
            foreach ($deliveryDates as $deliveryDate) {
                $delivery = $deliveryDate->format('Y-m-d');
                // Если тариф требует приготовления за день до, cooking_date = delivery_date - 1 день, иначе совпадает
                $cooking = $tariff->cooking_day_before ? Carbon::parse($delivery)->subDay()->format('Y-m-d') : $delivery;
                Ration::create([
                    'order_id'      => $order->id,
                    'delivery_date' => $delivery,
                    'cooking_date'  => $cooking,
                ]);
            }

            DB::commit();
            return redirect()->route('orders.show', $order->id)->with('success', 'Заказ успешно создан.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ошибка при создании заказа: ' . $e->getMessage()])->withInput();
        }
    }

    // Отображение деталей заказа
    public function show($id)
    {
        $order = Order::with(['tariff', 'rations'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }
}
