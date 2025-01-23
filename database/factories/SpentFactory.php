<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Month;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Spent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),  
            'month_id' => Month::factory(),        
            'amount' => $this->faker->randomFloat(2, 50, 2000),  
            'description' => $this->faker->sentence(),
        ];
    }
}
