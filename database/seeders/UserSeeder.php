<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    public function run()
    {

      if (!Schema::hasTable('users')) {
        $this->command->warn('La tabla "users" no existe. Ejecuta las migraciones.');
        return;
    }

        $faker = Faker::create('es_ES');
        for ($i = 0; $i < 3; $i++) {
            DB::table('users')->insert([
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'password' => Hash::make('password123'),
                'rol' => 'user',
                'email' => $faker->unique()->safeEmail(),
            ]);
        }
        DB::table('users')->insert([
            'name' => 'alex',
            'surname' => 'peris',
            'password' => Hash::make('1234'),
            'rol' => 'admin',
            'email' => 'alexperis95@gmail.com',
        ]);
    }
}
