<?php

namespace Database\Factories;

use App\Models\Month;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthFactory extends Factory
{
    protected $model = Month::class;

    public function definition()
    {
        return [
            'user_id' => rand(1,4), 
            'year' => $this->faker->year(),
            'month' => $this->faker->monthName(),
            'total_income' => 0,
            'total_spent' => 0,
        ];
    }
}
