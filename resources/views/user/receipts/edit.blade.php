@extends('layouts.auth')

@section('title')
    {{ $receipt->title }}
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Редактирование | {{ $receipt->title }}</h1>
                <a href="#" class="btn btn_auth btn_danger" id="delete-receipt">Удалить</a>
            </div>

            <div class="modal">
                <div class="modal_content">
                    <div class="modal_header">
                        <p class="modal_heading">Удаление рецепта</p>
                    </div>

                    <div class="modal_body">
                        <p>Вы действительно хотите удалить рецепт? Восстановить его будет невозможно</p>

                        <div class="flex gap-20 mt-3">
                            <a href="#" class="close_modal btn btn_auth btn_info">Закрыть</a>
                            <form action="{{ route('user.receipt.delete', $receipt) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn_auth btn_danger" type="submit">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if($errors->any())
                @foreach($errors->all() as $error)
                    <p class="red">{{ $error }}</p>
                @endforeach
            @endif

            @if(session()->has('success'))
                <p class="green">{{ session('success') }}</p>
            @endif

            <form action="{{ route('user.receipt.update', $receipt) }}" method="POST" id="receipt-form"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="title">Название рецепта:</label>
                    <input type="text" class="auth_form_control" id="title" name="title"
                           value="{{ old('title', $receipt->title) }}" required>
                </div>

                <div class="form-group">
                    <label for="period_id">Период:</label>
                    <select class="auth_form_control" id="period_id" name="period_id" required>
                        @foreach($periods as $period)
                            <option
                                value="{{ $period->id }}" {{ $period->id == $receipt->period_id ? 'selected' : '' }}>
                                {{ $period->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="category_id">Категория блюда:</label>
                    <select class="auth_form_control" id="category_id" name="category_id" required>
                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}" {{ $category->id == $receipt->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="country_code">Страна происхождения:</label>
                    <select class="auth_form_control" id="country_code" name="country_code" required>
                        @foreach($countries as $country)
                            <option value="{{ $country['code'] }}" {{ $country['code'] == $receipt->country_code ? 'selected' : '' }}>{{ $country['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="cuisine_id">Кухня:</label>
                    <select class="auth_form_control" id="cuisine_id" name="cuisine_id" required>
                        @foreach($cuisines as $cuisine)
                            <option value="{{ $cuisine->id }}" {{ $cuisine->id == $receipt->cuisine_id ? 'selected' : '' }}>{{ $cuisine->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Фото превью:</label>

                    <div>
                        <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="Фото превью" id="preview"
                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    </div>

                    <input type="file" class="auth_form_control" id="image" name="image" onchange="previewImage(event)">
                </div>

                <div class="form-group">
                    <label for="description">Описание:</label>
                    <textarea class="auth_form_control form_textarea_medium" id="description"
                              name="description">{{ old('description', $receipt->description) }}</textarea>
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

                <div class="form-group">
                    <label for="instructions">Инструкции:</label>
                    <textarea id="instructions" name="instructions" class="form-control"
                              required>{{ $receipt->instructions }}</textarea>
                </div>

                <input type="hidden" class="auth_form_control" id="latitude" name="latitude"
                       value="{{ old('latitude', $receipt->latitude) }}" readonly>
                <input type="hidden" class="auth_form_control" id="longitude" name="longitude"
                       value="{{ old('longitude', $receipt->longitude) }}" readonly>

                <div id="map" style="height: 400px;"></div>

                <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ auth()->id() }}"
                       readonly>

                <input type="hidden" id="ingredients-data" name="ingredients"
                       value="{{ json_encode($receipt->ingredients) }}">

                <button type="submit" class="btn btn_auth mt-5 mb-5">Сохранить</button>
            </form>
        </div>
    </section>
@endsection


@section('custom_js')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/product.js') }}"></script>
    <script>
        $(document).ready(function () {
            CKEDITOR.replace('instructions', {
                height: 700
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            ymaps.ready(function () {
                if (document.getElementById('map')) {
                    // Инициализация карты
                    let map = new ymaps.Map('map', {
                        center: [{{ $receipt->latitude }}, {{ $receipt->longitude }}],
                        zoom: 13
                    });

                    // Создание начального маркера
                    let marker = new ymaps.Placemark([{{ $receipt->latitude }}, {{ $receipt->longitude }}], {
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

            let openModal = document.getElementById('delete-receipt')
            openModal.addEventListener('click', (e) => {
                e.preventDefault()
                let modal = document.querySelector('.modal')
                modal.style.display = 'block'
            })
            let closeModal = document.querySelector('.close_modal')
            closeModal.addEventListener('click', (e) => {
                e.preventDefault()
                let modal = document.querySelector('.modal')
                modal.style.display = 'none'
            })
        });

        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                const preview = document.getElementById('preview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const newImage = document.createElement('img');
                    newImage.src = e.target.result;
                    newImage.style.maxWidth = '200px';
                    newImage.style.maxHeight = '200px';
                    newImage.style.objectFit = 'cover';
                    document.querySelector('.form-group').appendChild(newImage);
                }
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }

    </script>
@endsection
