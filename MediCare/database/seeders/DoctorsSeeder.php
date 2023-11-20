<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DoctorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        $specialties = ['Cardiology', 'Dermatology', 'Orthopedics', 'Pediatrics', 'Ophthalmology'];

        foreach (range(1, 6) as $index) {

            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $userId = DB::table('users')->insertGetId([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $firstName . '.'. $lastName . '@gmail.com',
                'role' => 'doctor',
                'password' => Hash::make('111'), // You may want to use Hash::make() in a real scenario
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $userId = DB::table('doctors')->insertGetId([
                'account_id' => $userId,
                'specialties' => $specialties[array_rand($specialties)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);


        }
    }
}
