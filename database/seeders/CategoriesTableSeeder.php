<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();
        $categories = [
            'Первые блюда',
            'Вторые блюда',
            'Закуски',
            'Салаты',
            'Соусы, кремы',
            'Напитки',
            'Десерты',
            'Выпечка',
            'Безглютеновая выпечка',
            'Торты',
            'Пасха',
            'Заготовки на зиму',
            'Блюда в мультиварке',
            'Блюда в хлебопечке',
            'Хлеб',
            'Тесто',
            'Блины и оладьи',
            'Постные блюда',
            'Полезные мелочи'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
