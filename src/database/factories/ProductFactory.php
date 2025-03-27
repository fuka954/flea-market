<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Condition;
use App\Models\User;
use App\Models\Category;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'brand' => $this->faker->company,
            'description' => $this->faker->sentence,
            'image' => 'images/product/sample.jpg',
            'condition_id' => Condition::inRandomOrder()->first()->id ?? Condition::factory(),
            'price' => $this->faker->numberBetween(300, 50000),
            'sold_flag' => 0,
            'sell_user' => User::inRandomOrder()->first()->id ?? User::factory(),
            'buy_user' => function (array $attributes) {
                return $attributes['sold_flag'] ? (User::inRandomOrder()->first()->id ?? User::factory()) : null;
            },
        ];
    }

    public function withCategories($count = 2)
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            $categories = Category::inRandomOrder()->limit($count)->pluck('id');
            if ($categories->isEmpty()) {
                $categories = Category::factory()->count($count)->create()->pluck('id');
            }
            $product->categories()->attach($categories);
        });
    }
}
