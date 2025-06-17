@extends('layouts.auth')

@section('title')
    Создание рецепта
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Создание рецепта</h1>
            </div>

            @if($errors->any())
                @foreach($errors->all() as $error)
                    <p class="red">{{ $error }}</p>
                @endforeach
            @endif

            @if(session()->has('success'))
                <p class="green">{{ session('success') }}</p>
            @endif

            <form action="{{ route('user.receipt.store') }}" id="receipt-form" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title">Название рецепта:</label>
                    <input type="text" class="auth_form_control" id="title" name="title" value="{{ old('title') }}"
                           required>
                </div>

                <div class="form-group">
                    <label for="period_id">Период:</label>
                    <select class="auth_form_control" id="period_id" name="period_id" required>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}"
                                    @if(old('period_id') == $period->id) selected @endif>{{ $period->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="category_id">Категория блюда:</label>
                    <select class="auth_form_control" id="category_id" name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    @if(old('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="country_code">Страна происхождения:</label>
                    <select class="auth_form_control" id="country_code" name="country_code" required>
                        @foreach($countries as $country)
                            <option value="{{ $country['code'] }}"
                                    @if(old('country_code') == $country['code']) selected @endif>{{ $country['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="cuisine_id">Кухня:</label>
                    <select class="auth_form_control" id="cuisine_id" name="cuisine_id" required>
                        @foreach($cuisines as $cuisine)
                            <option value="{{ $cuisine->id }}">{{ $cuisine->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Фото превью:</label>
                    <input type="file" class="auth_form_control" id="image" name="image" required>
                </div>

                <div class="form-group">
                    <label for="description">Описание:</label>
                    <textarea class="auth_form_control form_textarea_medium" id="description" name="description"
                              required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-3 mt-5">
                    <label for="product-search" class="form-label">Поиск продукта:</label>
                    <input type="text" id="product-search" class="auth_form_control"
                           placeholder="Введите название продукта...">
                    <div id="results-container" style="display: none;">
                        <ul id="product-list"></ul>
                    </div>
                </div>

                <table class="data_table">
                    <thead class="table-success">
                    <tr>
                        <th>Продукт</th>
                        <th>Вес, г</th>
                        <th>Кал</th>
                        <th>Б</th>
                        <th>Ж</th>
                        <th>У</th>
                        <th>Удалить</th>
                    </tr>
                    </thead>
                    <tbody id="product-table">

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"><b>Итого:</b></td>
                        <td id="total-cal">0</td>
                        <td id="total-protein">0</td>
                        <td id="total-fat">0</td>
                        <td id="total-carb">0</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Итого (на 100 г):</b></td>
                        <td id="total-cal-100">0</td>
                        <td id="total-protein-100">0</td>
                        <td id="total-fat-100">0</td>
                        <td id="total-carb-100">0</td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>

                <textarea id="ingredients-data" name="ingredients" style="display: none;">{{ old('ingredients') }}</textarea>

                <input type="hidden" class="auth_form_control" id="latitude" name="latitude"
                       value="{{ old('latitude') }}" readonly>
                <input type="hidden" class="auth_form_control" id="longitude" name="longitude"
                       value="{{ old('longitude') }}" readonly>

                <div id="map" style="height: 400px;"></div>

                <div class="form-group">
                    <label for="instructions">Инструкции:</label>
                    <textarea id="instructions" name="instructions" class="form-control"
                              required>{{ old('instructions') }}</textarea>
                </div>

                <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ auth()->id() }}"
                       readonly>

                <button type="submit" class="btn btn_auth mt-5">Создать рецепт</button>
            </form>
        </div>
    </section>
@endsection


@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            CKEDITOR.replace('instructions', {
                height: 700
            });
            ymaps.ready(function () {
                if (document.getElementById('map')) {
                    // Инициализация карты
                    let map = new ymaps.Map('map', {
                        center: [51.505, -0.09],
                        zoom: 13
                    });

                    // Создание начального маркера
                    let marker = new ymaps.Placemark([51.505, -0.09], {
                        hintContent: 'Ваше местоположение',
                        balloonContent: 'Ваше местоположение'
                    });

                    // Добавление маркера на карту
                    map.geoObjects.add(marker);

                    // Обработка клика по карте
                    map.events.add('click', function (e) {
                        // Получение координат клика
                        let coords = e.get('coords');

                        // Обновление полей ввода
                        document.getElementById('latitude').value = coords[0];
                        document.getElementById('longitude').value = coords[1];

                        // Удаление старого маркера
                        map.geoObjects.remove(marker);

                        // Создание нового маркера
                        marker = new ymaps.Placemark(coords, {
                            hintContent: 'Новое местоположение',
                            balloonContent: 'Новое местоположение'
                        });

                        // Добавление нового маркера на карту
                        map.geoObjects.add(marker);
                    });
                }
            });
        });
    </script>
    <script src="{{ asset('js/product.js') }}"></script>

    <script>
        $(document).ready(function () {
            let oldIngredients = @json(session('ingredients', []));
            console.log(oldIngredients);
            if (oldIngredients && oldIngredients.length > 0) {
                oldIngredients.forEach(function (ingredient) {
                    addProductToTable({
                        name: ingredient.name,
                        calories: ingredient.calories,
                        protein: ingredient.protein,
                        fat: ingredient.fat,
                        carbs: ingredient.carbs
                    });
                });
            }
        });
    </script>

@endsection
