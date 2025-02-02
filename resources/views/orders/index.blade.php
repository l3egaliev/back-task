<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Список заказов</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1>Заказы</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">Создать новый заказ</a>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Клиент</th>
                        <th>Телефон</th>
                        <th>Тариф</th>
                        <th>Тип расписания</th>
                        <th>Создан</th>
                        <th>Доставка с - по</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->client_name }}</td>
                        <td>{{ $order->client_phone }}</td>
                        <td>{{ $order->tariff->ration_name }}</td>
                        <td>{{ $order->schedule_type }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ $order->first_date }} - {{ $order->last_date }}</td>
                        <td><a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">Просмотр</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
