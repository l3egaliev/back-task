<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Детали заказа #{{ $order->id }}</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1>Детали заказа #{{ $order->id }}</h1>
        <div class="mb-3">
            <strong>Клиент:</strong> {{ $order->client_name }}
        </div>
        <div class="mb-3">
            <strong>Телефон:</strong> {{ $order->client_phone }}
        </div>
        <div class="mb-3">
            <strong>Тариф:</strong> {{ $order->tariff->ration_name }} (готовить {{ $order->tariff->cooking_day_before ? 'за день до доставки' : 'в день доставки' }})
        </div>
        <div class="mb-3">
            <strong>Тип расписания:</strong> {{ $order->schedule_type }}
        </div>
        <div class="mb-3">
            <strong>Комментарий:</strong> {{ $order->comment }}
        </div>
        <div class="mb-3">
            <strong>Дата создания:</strong> {{ $order->created_at }}
        </div>
        <div class="mb-3">
            <strong>Доставка с:</strong> {{ $order->first_date }} по {{ $order->last_date }}
        </div>

        <hr>
        <h2>Рационы питания</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Дата приготовления</th>
                        <th>Дата доставки</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->rations as $ration)
                    <tr>
                        <td>{{ $ration->id }}</td>
                        <td>{{ $ration->cooking_date }}</td>
                        <td>{{ $ration->delivery_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Вернуться к списку заказов</a>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
