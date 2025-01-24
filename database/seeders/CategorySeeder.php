<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorías fijas
        $fixedCategories = ['Gasolina', 'Suscripciones', 'Comida fuera', 'Alimentación', 'Casa', 'Transporte Público', 'Ocio', 'Nómina', 'Regalo', 'Otros'];
    
        foreach ($fixedCategories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }
    
        $user = User::first();
        if ($user) {
            $userCategories = ['Deporte', 'Viajes', 'Educación'];
    
            foreach ($userCategories as $category) {
                Category::firstOrCreate([
                    'name' => $category,
                    'user_id' => $user->id,
                ]);
            }
        }

        Category::factory()->count(5)->create();  
    }
}
