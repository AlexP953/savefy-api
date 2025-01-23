<?php

namespace Database\Seeders;

use App\Models\Month;
use App\Models\User;
use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            $months = [
                'January', 'February', 'March', 'April', 'May', 'June', 
                'July', 'August', 'September', 'October', 'November', 'December'
            ];

            foreach ($months as $monthName) {
                Month::firstOrCreate([
                    'user_id' => $user->id,
                    'year' => now()->year,  
                    'month' => $monthName,
                ]);
            }
        }
    }
}
