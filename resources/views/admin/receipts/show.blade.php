@extends('layouts.auth')
@section('title')
    {{ $receipt->title }}
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="receipt_container">
                <div class="auth_main_title_div">
                    <h1>{{ $receipt->title }}</h1>
                </div>
                <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="{{ $receipt->title }}" class="admin_receipt_img">
                <div class="receipt_description_page">
                    {{ $receipt->description }}
                </div>
                <div class="form-group">
                    <label for="period_id" class="mb-2"><strong>Период:</strong></label>
                    <select class="auth_form_control" id="period_id" name="period_id" disabled>
                        @foreach($periods as $period)
                            <option
                                value="{{ $period->id }}" {{ $period->id == $receipt->period_id ? 'selected' : '' }}>
                                {{ $period->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_id" class="mb-2"><strong>Категория блюда:</strong></label>
                    <select class="auth_form_control" id="category_id" name="category_id" disabled>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $receipt->period_id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="country_code" class="mb-2"><strong>Страна происхождения:</strong></label>
                    <select class="auth_form_control" id="country_code" name="country_code" disabled>
                        @foreach($countries as $country)
                            <option value="{{ $country['code'] }}" {{ $country['code'] == $receipt->country_code ? 'selected' : '' }}>{{ $country['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="cuisine_id" class="mb-2"><strong>Кухня:</strong></label>
                    <select class="auth_form_control" id="cuisine_id" name="cuisine_id" disabled>
                        @foreach($cuisines as $cuisine)
                            <option value="{{ $cuisine->id }}" {{ $cuisine->id == $receipt->cuisine_id ? 'selected' : '' }}>{{ $cuisine->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="mb-2"><strong>Ингредиенты:</strong></label>
                    <div id="ingredients-container">
                        <table class="data_table">
                            <thead class="table-success">
                            <tr>
                                <th>Продукт</th>
                                <th>Вес, г</th>
                                <th>Кал</th>
                                <th>Б</th>
                                <th>Ж</th>
                                <th>У</th>
                            </tr>
                            </thead>
                            <tbody id="product-table">
                            @foreach($receipt->ingredients as $index => $ingredient)
                                <tr>
                                    <td>{{ $ingredient['name'] }}</td>
                                    <td>{{ $ingredient['weight'] }}</td>
                                    <td>{{ $ingredient['calories'] }}</td>
                                    <td>{{ $ingredient['protein'] }}</td>
                                    <td>{{ $ingredient['fat'] }}</td>
                                    <td>{{ $ingredient['carbs'] }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2"><b>Итого:</b></td>
                                <td id="total-cal">0</td>
                                <td id="total-protein">0</td>
                                <td id="total-fat">0</td>
                                <td id="total-carb">0</td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Итого (на 100 г):</b></td>
                                <td id="total-cal-100">0</td>
                                <td id="total-protein-100">0</td>
                                <td id="total-fat-100">0</td>
                                <td id="total-carb-100">0</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <label for="instructions" class="mb-4"><strong>Инструкции:</strong></label>
                    <div class="instructions">
                        {!! $receipt->instructions !!}
                    </div>
                </div>
                <input type="hidden" class="auth_form_control" id="latitude" name="latitude"
                       value="{{ old('latitude', $receipt->latitude) }}" readonly>
                <input type="hidden" class="auth_form_control" id="longitude" name="longitude"
                       value="{{ old('longitude', $receipt->longitude) }}" readonly>
                <div id="map" style="height: 400px;" class="mb-5"></div>

                @if($receipt->status == 'new')
                    <form action="{{ route('admin.receipt.update', $receipt) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="flex gap-20">
                            <button type="submit" name="status" value="success" class="btn btn_auth">Одобрить</button>
                            <button type="button" id="reject-button" class="btn btn_auth btn_danger">Отклонить</button>
                        </div>
                    </form>

                    <div class="modal">
                        <div class="modal_content">
                            <div class="modal_header">
                                <p class="modal_heading">Отклонить заявку?</p>
                            </div>

                            <div class="modal_body">
                                <form method="post" action="{{ route('admin.receipt.update', $receipt) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="rejection_reason">Причина отклонения:</label>
                                        <textarea class="auth_form_control mt-2" id="rejection_reason"
                                                  name="reason"></textarea>
                                    </div>
                                    <div class="flex gap-20 mt-4">
                                        <a href="#" class="btn btn_auth btn_info close_modal">Закрыть</a>
                                        <button type="submit" name="status" value="rejected" class="btn btn_auth">
                                            Подтвердить отклонение
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

<style>
    .auth_main {
        padding-bottom: 100px;
    }
</style>

@section('custom_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        $(document).ready(function () {
            $('#reject-button').click(function () {
                $('.modal').show();
            });
            $('.close_modal').click(function (e) {
                e.preventDefault()
                $('.modal').hide()
            });

            ymaps.ready(function () {
                if (document.getElementById('map')) {
                    // Инициализация карты
                    let map = new ymaps.Map('map', {
                        center: [{{ $receipt->latitude }}, {{ $receipt->longitude }}],
                        zoom: 13
                    });

                    // Создание маркера
                    let marker = new ymaps.Placemark([{{ $receipt->latitude }}, {{ $receipt->longitude }}], {
                        hintContent: 'Ваше местоположение',
                        balloonContent: 'Ваше местоположение'
                    });

                    // Добавление маркера на карту
                    map.geoObjects.add(marker);
                }
            });
            updateTotals();
        });

        function updateTotals() {
            let totalWeight = 0, totalCal = 0, totalProtein = 0, totalFat = 0, totalCarbs = 0;

            $("#product-table tr").each(function () {
                let weight = parseFloat($(this).find("td:nth-child(2)").text()) || 0;
                let calories = parseFloat($(this).find("td:nth-child(3)").text()) || 0;
                let protein = parseFloat($(this).find("td:nth-child(4)").text()) || 0;
                let fat = parseFloat($(this).find("td:nth-child(5)").text()) || 0;
                let carbs = parseFloat($(this).find("td:nth-child(6)").text()) || 0;

                totalWeight += weight;
                totalCal += calories;
                totalProtein += protein;
                totalFat += fat;
                totalCarbs += carbs;
            });

            $("#total-cal").text(totalCal.toFixed(1));
            $("#total-protein").text(totalProtein.toFixed(1));
            $("#total-fat").text(totalFat.toFixed(1));
            $("#total-carb").text(totalCarbs.toFixed(1));

            let factor = totalWeight > 0 ? 100 / totalWeight : 0;
            $("#total-cal-100").text((totalCal * factor).toFixed(1));
            $("#total-protein-100").text((totalProtein * factor).toFixed(1));
            $("#total-fat-100").text((totalFat * factor).toFixed(1));
            $("#total-carb-100").text((totalCarbs * factor).toFixed(1));
        }
    </script>
@endsection
