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
        Spent::factory()->count(5)->create();
    }
}
