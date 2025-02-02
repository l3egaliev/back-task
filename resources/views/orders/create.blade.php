<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Создание заказа</title>
    <!-- Подключение Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Создать заказ</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                       <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="client_name" class="form-label">ФИО клиента:</label>
                <input type="text" name="client_name" id="client_name" class="form-control" value="{{ old('client_name') }}" required>
            </div>
            <div class="mb-3">
                <label for="client_phone" class="form-label">Номер телефона (формат: 79991112233):</label>
                <input type="text" name="client_phone" id="client_phone" class="form-control" value="{{ old('client_phone') }}" required>
            </div>
            <div class="mb-3">
                <label for="tariff_id" class="form-label">Тариф:</label>
                <select name="tariff_id" id="tariff_id" class="form-select" required>
                    @foreach($tariffs as $tariff)
                        <option value="{{ $tariff->id }}" {{ old('tariff_id') == $tariff->id ? 'selected' : '' }}>
                            {{ $tariff->ration_name }} (готовить {{ $tariff->cooking_day_before ? 'за день до доставки' : 'в день доставки' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="schedule_type" class="form-label">Тип расписания доставки:</label>
                <select name="schedule_type" id="schedule_type" class="form-select" required>
                    @foreach($scheduleTypes as $key => $label)
                        <option value="{{ $key }}" {{ old('schedule_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Комментарий:</label>
                <textarea name="comment" id="comment" class="form-control">{{ old('comment') }}</textarea>
            </div>
            <hr>
            <h3>Интервалы дат доставки</h3>
            <div id="intervals">
                <!-- Один интервал по умолчанию -->
                <div class="interval mb-3">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <label class="form-label">От:</label>
                            <input type="date" name="intervals[0][from]" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">До:</label>
                            <input type="date" name="intervals[0][to]" class="form-control" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mb-3" onclick="addInterval()">Добавить интервал</button>
            <br>
            <button type="submit" class="btn btn-primary">Создать заказ</button>
        </form>
    </div>

    <script>
        function addInterval(){
            const container = document.getElementById('intervals');
            const index = container.children.length;
            const div = document.createElement('div');
            div.className = 'interval mb-3';
            div.innerHTML = `
                <div class="row g-2">
                    <div class="col-md-5">
                        <label class="form-label">От:</label>
                        <input type="date" name="intervals[${index}][from]" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">До:</label>
                        <input type="date" name="intervals[${index}][to]" class="form-control" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.parentElement.remove()">Удалить</button>
                    </div>
                </div>
            `;
            container.appendChild(div);
        }
    </script>
    <!-- Подключение Bootstrap JS (опционально, если нужны интерактивные элементы) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
