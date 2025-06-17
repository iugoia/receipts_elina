@extends('layouts.main')

@section('title')
    Рецепт | {{ $receipt->title }}
@endsection

@section('content')
    <section class="receipt">
        <div class="container receipt_container">
            <div class="receipt_img">
                <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="{{ $receipt->title }}">
            </div>

            <div class="receipt_actions">
                <form action="{{ route('user.receipt.favorite.store', $receipt) }}" method="post">
                    @csrf
                    <button class="favorites_button" type="submit">
                        <div class="favorites_btnText">
                            @if($isFavorite)
                                Удалить из избранных
                            @else
                                В избранное
                            @endif
                        </div>
                        <svg width="17" height="14" fill="none" xmlns="http://www.w3.org/2000/svg"
                             class="favorites_icon__ne6Ch">
                            <path
                                d="M12.1 0c-1.392 0-2.728.618-3.6 1.595C7.628.618 6.292 0 4.9 0 2.436 0 .5 1.846.5 4.196c0 2.884 2.72 5.234 6.84 8.805L8.5 14l1.16-1.007C13.78 9.43 16.5 7.08 16.5 4.196 16.5 1.846 14.564 0 12.1 0Z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="favorites_count">{{ $favoritesCount }}</span>
                    </button>
                </form>
            </div>

            <h1 class="receipt_title">{{$receipt->title}}</h1>

            <p class="receipt_author">
                Автор: <a href="{{ route('profile.show', $receipt->user) }}">{{ $receipt->user->name }}</a>
            </p>

            <p class="receipt_author">
                Страна: {{ $receipt->getCountry() }}
            </p>

            <p class="receipt_author">
                Исторический период: {{ $receipt->period->title }}
            </p>

            <p class="receipt_author">
                Кухня: {{ $receipt->cuisine->name }}
            </p>

            <p class="receipt_desc">{{$receipt->description}}</p>

            @php
                $ingredients = $receipt->ingredients;
            @endphp

            <h2 class="mt-5">Продукты для рецепта</h2>

            <div class="input_fields">
                <p>Грамм</p>
                <input type="number" id="weight_input" min="1" value="100" class="form_control auth_form_control">

                <p>Калории</p>
                <input type="number" id="calories_input" min="1" class="form_control auth_form_control">
            </div>

            <div class="receipt_ingredients" id="ingredients_list">
                @foreach ($ingredients as $ingredient)
                    <div class="receipt_ingredients_item" data-name="{{ $ingredient['name'] }}"
                         data-weight="{{ $ingredient['weight'] }}"
                         data-calories="{{ $ingredient['calories'] }}"
                         data-protein="{{ $ingredient['protein'] }}"
                         data-fat="{{ $ingredient['fat'] }}"
                         data-carbs="{{ $ingredient['carbs'] }}">
                        <div class="ingredient_name">{{ $ingredient['name'] }}</div>
                        <div class="ingredient_kbju">
                            <span><strong>К:</strong> {{ $ingredient['calories'] }} ккал</span>
                            <span><strong>Б:</strong> {{ $ingredient['protein'] }} г</span>
                            <span><strong>Ж:</strong> {{ $ingredient['fat'] }} г</span>
                            <span><strong>У:</strong> {{ $ingredient['carbs'] }} г</span>
                        </div>
                        <div class="ingredient_weight">{{ $ingredient['weight'] }} г</div>
                    </div>
                @endforeach
            </div>

            <div class="receipt_kbju" id="total_kbju">
                <h3>КБЖУ на весь рецепт</h3>
                <p><strong>Калории:</strong> <span id="total_calories">0</span> ккал</p>
                <p><strong>Белки:</strong> <span id="total_protein">0</span> г</p>
                <p><strong>Жиры:</strong> <span id="total_fat">0</span> г</p>
                <p><strong>Углеводы:</strong> <span id="total_carbs">0</span> г</p>
            </div>


            <div class="receipt_content">
                <h2>Пошаговый рецепт</h2>
                <div class="instructions">
                    {!! $receipt->instructions !!}
                </div>
            </div>
        </div>
    </section>

    @if($comments->count() > 0)

        <section class="reviews-section">
            <div class="container">
                <h2>Отзывы о рецепте</h2>

                <div class="reviews-list">
                    @foreach($comments as $comment)
                        <div class="review-card" id="comment-{{ $comment->id }}">
                            <div class="review-header">
                                <div class="review-user">
                                    <div class="user-avatar">{{ mb_substr($comment->name, 0, 1, 'UTF-8') }}</div>
                                    <span class="user-name">{{ $comment->name }}</span>
                                </div>
                                <span class="review-date">{{ $comment->created_at->format('d.m.Y') }}</span>
                            </div>

                            <div class="review-text">
                                <p>{{ $comment->text }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    @endif


    @if(!$isUserHasComment)
        <section class="form" id="comment">
            <div class="container">
                <div class="form_content">
                    <h2>Если вам понравился рецепт оставьте отзыв!</h2>
                    <div class="mini_hr"></div>
                    <p class="form_subtitle"></p>
                    @if(session()->has('success'))
                        <p class="green">{{ session()->get('success') }}</p>
                    @endif
                    <form action="{{ route('user.receipt.comment.store', $receipt) }}" class="form_form" method="post">
                        <input type="text" name="name" class="form_control" placeholder="Ваше имя*"
                               @if(isLogged()) value="{{ user()->name }}" @endif>
                        @error('name')
                        <p class="red">{{ $message }}</p>
                        @enderror
                        <textarea name="text" cols="30" rows="10" placeholder="Отзыв*"
                                  class="form_control form_textarea_medium">{{ old('text') }}</textarea>
                        @error('text')
                        <p class="red">{{ $message }}</p>
                        @enderror
                        <button class="btn btn_primary" type="submit">Оставить отзыв</button>
                    </form>
                </div>
            </div>
        </section>
    @endif
@endsection

@section('custom_js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Парсим ингредиенты из PHP
            const ingredients = @json($ingredients).map(i => ({
                ...i,
                weight: parseFloat(i.weight) || 0,
                calories: parseFloat(i.calories) || 0,
                protein: parseFloat(i.protein) || 0,
                fat: parseFloat(i.fat) || 0,
                carbs: parseFloat(i.carbs) || 0,
            }));

            // Выводим подробные данные об ингредиентах для отладки
            console.log("Ingredients:", JSON.stringify(ingredients, null, 2));

            const weightInput = document.getElementById("weight_input");
            const caloriesInput = document.getElementById("calories_input");
            const ingredientsList = document.getElementById("ingredients_list");

            const totalCaloriesEl = document.getElementById("total_calories");
            const totalProteinEl = document.getElementById("total_protein");
            const totalFatEl = document.getElementById("total_fat");
            const totalCarbsEl = document.getElementById("total_carbs");

            function updateIngredients(newWeight, updateCaloriesInput = true) {
                // Проверяем, что newWeight валиден
                if (isNaN(newWeight) || newWeight <= 0) {
                    console.warn("Invalid weight input:", newWeight);
                    ingredientsList.innerHTML = "<div>Ошибка: введите корректный вес блюда.</div>";
                    return;
                }

                // Сумма исходных весов ингредиентов
                const totalWeight = ingredients.reduce((sum, i) => sum + i.weight, 0);

                if (totalWeight <= 0) {
                    console.error("Total weight is zero or negative:", totalWeight);
                    ingredientsList.innerHTML = "<div>Ошибка: сумма весов ингредиентов равна нулю.</div>";
                    return;
                }

                // Коэффициент масштабирования
                const scaleFactor = newWeight / totalWeight;
                console.log("Scale factor:", scaleFactor);

                ingredientsList.innerHTML = "";

                let totalCalories = 0,
                    totalProtein = 0,
                    totalFat = 0,
                    totalCarbs = 0;

                ingredients.forEach(i => {
                    // Пропускаем ингредиенты с нулевым весом
                    if (i.weight <= 0) {
                        console.warn(`Skipping ingredient ${i.name} due to invalid weight:`, i.weight);
                        ingredientsList.innerHTML += `
                    <div class="receipt_ingredients_item">
                        <div class="ingredient_name">${i.name}</div>
                        <div class="ingredient_kbju">Ошибка: вес ингредиента равен 0</div>
                    </div>
                `;
                        return;
                    }

                    // Новый вес ингредиента
                    const itemWeight = i.weight * scaleFactor;

                    // Питательные вещества на 1 грамм
                    const caloriesPerGram = i.calories / i.weight;
                    const proteinPerGram = i.protein / i.weight;
                    const fatPerGram = i.fat / i.weight;
                    const carbsPerGram = i.carbs / i.weight;

                    // Пересчёт питательных веществ
                    const itemCalories = itemWeight * caloriesPerGram;
                    const itemProtein = itemWeight * proteinPerGram;
                    const itemFat = itemWeight * fatPerGram;
                    const itemCarbs = itemWeight * carbsPerGram;

                    totalCalories += itemCalories;
                    totalProtein += itemProtein;
                    totalFat += itemFat;
                    totalCarbs += itemCarbs;

                    // Вывод ингредиента
                    console.log(`Ingredient: ${i.name}, New Weight: ${itemWeight.toFixed(1)}g`);
                    ingredientsList.innerHTML += `
                <div class="receipt_ingredients_item">
                    <div class="ingredient_name">${i.name}</div>
                    <div class="ingredient_kbju">
                        <span><strong>К:</strong> ${Math.round(itemCalories)} ккал</span>
                        <span><strong>Б:</strong> ${itemProtein.toFixed(1)} г</span>
                        <span><strong>Ж:</strong> ${itemFat.toFixed(1)} г</span>
                        <span><strong>У:</strong> ${itemCarbs.toFixed(1)} г</span>
                    </div>
                    <div class="ingredient_weight">${Math.round(itemWeight)} г</div>
                </div>
            `;
                });

                // Обновление итогов
                totalCaloriesEl.textContent = Math.round(totalCalories);
                totalProteinEl.textContent = totalProtein.toFixed(1);
                totalFatEl.textContent = totalFat.toFixed(1);
                totalCarbsEl.textContent = totalCarbs.toFixed(1);

                // Обновляем поле калорий, если нужно
                if (updateCaloriesInput) {
                    caloriesInput.value = Math.round(totalCalories);
                }

                // Обновляем поле веса
                weightInput.value = Math.round(newWeight);
            }

            // Обработчик ввода веса
            weightInput.addEventListener("input", () => {
                const weight = parseFloat(weightInput.value);
                console.log("Input weight:", weight);
                if (!isNaN(weight) && weight > 0) {
                    updateIngredients(weight);
                }
            });

            // Обработчик ввода калорий
            caloriesInput.addEventListener("input", () => {
                const targetCalories = parseFloat(caloriesInput.value);
                console.log("Input calories:", targetCalories);
                if (!isNaN(targetCalories) && targetCalories > 0) {
                    // Сумма исходных калорий
                    const totalWeight = ingredients.reduce((sum, i) => sum + i.weight, 0);
                    const totalCalories = ingredients.reduce((sum, i) => sum + i.calories, 0);

                    if (totalCalories <= 0) {
                        console.error("Total calories is zero or negative:", totalCalories);
                        ingredientsList.innerHTML = "<div>Ошибка: сумма калорий ингредиентов равна нулю.</div>";
                        return;
                    }

                    // Рассчитываем новый вес блюда
                    const scaleFactor = targetCalories / totalCalories;
                    const newWeight = totalWeight * scaleFactor;
                    console.log("Calculated weight from calories:", newWeight);

                    updateIngredients(newWeight, false); // Не обновляем caloriesInput
                }
            });

            // Первый вызов
            const initialWeight = parseFloat(weightInput.value);
            console.log("Initial weight:", initialWeight);
            if (!isNaN(initialWeight) && initialWeight > 0) {
                updateIngredients(initialWeight);
            } else {
                ingredientsList.innerHTML = "<div>Введите корректный вес блюда.</div>";
            }
        });

        // Обработка скролла к комментарию
        document.addEventListener("DOMContentLoaded", function () {
            if ({{ session('scrollToComment') ? 'true' : 'false' }}) {
                const element = document.getElementById('comment');
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>
@endsection
