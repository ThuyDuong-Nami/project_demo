<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Liior\Faker\Prices;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->addProvider(new Prices($this->faker));
        return [
//            'image' => $this->faker->image('public/storage/products',400, 300),
            'name'  => $this->faker->unique()->name,
            'description' => $this->faker->paragraph(4,true),
            'price' => $this->faker->price(),
            'quantities' => $this->faker->numberBetween(10, 20),
        ];
    }
}
