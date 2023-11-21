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
        $imageName = 'noprofile.jpeg';

        foreach (range(1, 6) as $index) {

            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $userId = DB::table('users')->insertGetId([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $firstName . '.'. $lastName . '@gmail.com',
                'role' => 'doctor',
                'password' => Hash::make('111'), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('doctors')->insert([
                'account_id' => $userId,
                'specialties' => $specialties[array_rand($specialties)],
                'image_name' => $imageName,
                'image_data' => 'images/' . $imageName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


        }
    }
}
