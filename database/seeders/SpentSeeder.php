<?php

namespace Database\Seeders;

use App\Models\Spent;
use App\Models\Category;
use App\Models\Month;
use Illuminate\Database\Seeder;

class SpentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fixedCategories = Category::whereNull('user_id')->get(); 
        if ($fixedCategories->isEmpty()) {
            return response()->json(['message' => 'No fixed categories found'], 500);
        }

        $fixedCategories->each(function ($category) {
            for ($i = 0; $i < 5; $i++) {  
                Spent::create([
                    'amount' => rand(10, 500),  
                    'description' => 'Gasto en ' . $category->name,  
                    'category_id' => $category->id,  
                    'month_id' => Month::inRandomOrder()->first()->id,  
                ]);
            }
        });
    }
}
