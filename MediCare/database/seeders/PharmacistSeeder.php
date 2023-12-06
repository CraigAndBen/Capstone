<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PharmacistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 6) as $index) {

            $firstName = $faker->firstName;
            $middleName = $faker->lastName;
            $lastName = $faker->lastName;

            DB::table('users')->insert([
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'email' => $firstName . '.'. $lastName . '@gmail.com',
                'role' => 'pharmacist',
                'password' => Hash::make('111'), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
