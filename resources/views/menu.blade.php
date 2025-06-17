@extends('layouts.main')

@section('title')
    Идеи для праздника
@endsection

@section('content')
    <style>
        header {
            color: #ffffff;
        }

        .burger span {
            background: #fafafa;
        }

        .burger.active span {
            background: black;
        }

        footer {
            margin-top: 0 !important;
        }
    </style>
    <section class="cuisine_header" style="background-image: url('/img/menu_bg.jpg');">
        <div class="container">
            <h1>Идеи для праздника</h1>
        </div>
    </section>

    <section class="menu_content">
        <div class="container">
            <div class="intro-text">
                <p>Добро пожаловать в инструмент для создания меню! С помощью этого сервиса вы сможете сформировать
                    меню, выбрав категорию, которая соответствует вашему событию или предпочтениям, используя рецепты из
                    нашей базы данных.</p>

                <p><strong>Что еще можно сделать?</strong></p>
                <ul>
                    <li><strong>Создайте личный кабинет:</strong> Если вы авторизуетесь, все сформированные меню будут
                        сохранены в вашем личном кабинете.
                    </li>
                    <li><strong>Скачайте меню в формате Word:</strong> В личном кабинете вы сможете скачать
                        сформированное меню в формате Word для печати или сохранения.
                    </li>
                </ul>

                <p>Таким образом, вы сможете не только создать меню для вашего мероприятия, но и всегда иметь доступ к
                    нему!</p>
            </div>

            <div class="menu_form" id="form">
                <div class="form-instructions intro-text">
                    <h2>Как создать меню?</h2>
                    <p>Следуйте этим простым шагам, чтобы подобрать рецепты для вашего мероприятия:</p>
                    <p><strong>Выберите категорию:</strong> Нажмите на одну категорию, которая лучше всего
                        подходит.</p>
                    <p><strong>Обратите внимание:</strong> Можно выбрать только одну категорию, соответствующую
                        рецептам в базе.</p>
                    <p><strong>Как использовать:</strong> Выберите параметры, кликните по одной категории и нажмите
                        "Отправить". Наш искусственный интеллект подберет подходящие рецепты.</p>
                </div>
                <div class="form-container">
                    @if(session()->has('error'))
                        <p class="red mt-2">{{ session('error') }}</p>
                    @endif
                    <form action="{{ route('menu.search') }}" method="post">
                        @csrf
                        <div class="flex">
{{--                            <div class="form-group">--}}
{{--                                <label for="period_id">Период:</label>--}}
{{--                                <select class="auth_form_control" id="period_id" name="period_id">--}}
{{--                                    <option value="">Выберите период</option>--}}
{{--                                    @foreach($periods as $period)--}}
{{--                                        <option value="{{ $period->id }}">{{ $period->title }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="cuisine_id">Кухня:</label>--}}
{{--                                <select class="auth_form_control" id="cuisine_id" name="cuisine_id">--}}
{{--                                    <option value="">Выберите кухню</option>--}}
{{--                                    @foreach($cuisines as $cuisine)--}}
{{--                                        <option value="{{ $cuisine->id }}">{{ $cuisine->name }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <label>Категория:</label>
                                <div class="tags-container">
                                    <div class="tag" data-value="Веганское">Веганское</div>
                                    <div class="tag" data-value="Вегетарианское">Вегетарианское</div>
                                    <div class="tag" data-value="Низкокалорийное">Низкокалорийное</div>
                                    <div class="tag" data-value="Безглютеновое">Безглютеновое</div>
                                    <div class="tag" data-value="На день рождения">На день рождения</div>
                                    <div class="tag" data-value="Новогоднее">Новогоднее</div>
                                    <div class="tag" data-value="На свадьбу">На свадьбу</div>
                                    <div class="tag" data-value="Детское">Детское</div>
                                    <div class="tag" data-value="Завтрак">Завтрак</div>
                                    <div class="tag" data-value="Обед">Обед</div>
                                    <div class="tag" data-value="Ужин">Ужин</div>
                                    <div class="tag" data-value="Закуски">Закуски</div>
                                </div>
                                <input type="hidden" name="text" id="tags-input" required>
                            </div>
                        </div>
                        <button class="btn btn_auth" type="submit">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @if(session()->has('receipts'))
        <section class="menu_receipts">
            <div class="container">
                <form action="{{ route('menu.store') }}" method="post">
                    @csrf
                    <ul class="receipts_list">
                        @foreach(session('receipts') as $receipt)
                            <li class="receipt_item">
                                <div class="receipt_img">
                                    <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="receipt">
                                </div>
                                <div class="receipt_content">
                                    <p class="receipt_title">{{ $receipt->title }}</p>
                                    <p class="receipt_ingredients_name">Ингредиенты</p>
                                    <ul class="receipt_ingredients_list">
                                        @foreach($receipt->ingredients as $ingredient)
                                            <li class="receipt_ingredients_item">{{ $ingredient['name'] }}
                                                - {{ $ingredient['weight'] }} г
                                            </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ route('receipt.show', $receipt) }}" class="btn btn_primary"
                                    >Подробнее</a>
                                </div>

                                <div class="receipt_select">
                                    <label class="checkbox_label">
                                        <span>Сохранить рецепт?</span>
                                        <input type="checkbox" name="selected_receipts[]" value="{{ $receipt->id }}"
                                               class="receipt_checkbox">
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if(isLogged())
                        <div class="form_menu mt-5 mb-5">
                            <h2>Сохранить меню?</h2>
                            <input type="hidden" name="text" value="{{ session('text') }}">
                            <button class="btn btn_primary" type="submit">Сохранить</button>
                        </div>
                    @endif
                </form>
            </div>
        </section>
    @endif
@endsection

@section('custom_js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tagsContainer = document.querySelector('.tags-container');
            const tagsInput = document.querySelector('#tags-input');

            // Обработчик клика по тегу
            tagsContainer.querySelectorAll('.tag').forEach(tag => {
                tag.addEventListener('click', () => {
                    // Деактивируем все теги
                    tagsContainer.querySelectorAll('.tag').forEach(t => t.classList.remove('active'));
                    // Активируем выбранный тег
                    tag.classList.add('active');
                    // Обновляем скрытое поле
                    tagsInput.value = tag.dataset.value;
                    tagsInput.dispatchEvent(new Event('input')); // Для валидации
                });
            });

            // Валидация формы
            const form = document.querySelector('.menu_form form');
            form.addEventListener('submit', (e) => {
                if (!tagsInput.value) {
                    e.preventDefault();
                    alert('Пожалуйста, выберите одну категорию.');
                }
            });

            // Диагностика ингредиентов
            const receipts = @json(session('receipts', []));
            receipts.forEach(receipt => {
                console.log(`Ingredients for receipt ${receipt.id}:`, JSON.stringify(receipt.ingredients, null, 2));
            });
        });
    </script>
@endsection
