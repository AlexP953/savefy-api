<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\Category;
use App\Models\Month;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Income::factory()->count(5)->create();
    }
}
