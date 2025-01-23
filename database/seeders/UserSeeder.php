<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {

        $faker = Faker::create('es_ES');
        for ($i = 0; $i < 3; $i++) {
            $user = User::create([
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'password' => Hash::make('password123'),
                'email' => $faker->unique()->safeEmail(),
            ]);
            $user->assignRole('user'); 
        }

        $admin = User::firstOrCreate(
            ['email' => 'alexperis95@gmail.com'],
            [
                'name' => 'alex',
                'surname' => 'peris',
                'password' => Hash::make('1234'),
            ]
        );
        $admin->assignRole('admin');
    }
}
