<?php

namespace Database\Factories;

use App\Models\Period;
use App\Models\Receipt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receipt>
 */
class ReceiptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ru_RU');

        $recipe = Receipt::create([
            'title'       => $faker->sentence(),
            'description' => $faker->text(),
            'ingredients' => json_encode([
                $faker->word,
                $faker->word,
                $faker->word
            ]),
            'instructions' => $faker->paragraph(),
            'latitude'    => $faker->latitude(),
            'longitude'   => $faker->longitude(),
            'period_id'   => $faker->randomElement(Period::pluck('id')->toArray())
        ]);

        $imageUrls = [
            'https://picsum.photos/800/600?random=' . rand(1, 1000),
            'https://picsum.photos/800/600?random=' . rand(1, 1000),
            'https://picsum.photos/800/600?random=' . rand(1, 1000)
        ];

        foreach ($imageUrls as $url) {
            $recipe->addMediaFromUrl($url)
                ->toMediaCollection('images');
        }

        return [
            'title'       => $faker->sentence(),
            'description' => $faker->text(),
            'ingredients' => json_encode([
                $faker->word,
                $faker->word,
                $faker->word
            ]),
            'instructions' => $faker->paragraph(),
            'latitude'    => $faker->latitude(),
            'longitude'   => $faker->longitude(),
            'period_id'   => $faker->randomElement(Period::pluck('id')->toArray())
        ];
    }
}
