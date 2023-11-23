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
        $qualification = ['Doctor of Medicine (MD)', 'Doctor of Osteopathic Medicine (DO)','Surgeon'];
        $imageName = 'noprofile.jpeg';
        

        foreach (range(1, 6) as $index) {

            $firstName = $faker->firstName;
            $middleName = $faker->lastName;
            $lastName = $faker->lastName;
            $birthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $date = $faker->date($format = 'Y-m-d', $max = 'now');
            $time = $faker->time($format = 'h:i A');
            $age = $faker->numberBetween(1, 70);
            $years = $faker->numberBetween(1, 30);
            $gender = $faker->randomElement(['male', 'female']);
            $digits = $faker->numerify('#########');
            $phone = '+639' . $digits;

            $userId = DB::table('users')->insertGetId([
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'email' => $firstName . '.'. $lastName . '@gmail.com',
                'role' => 'doctor',
                'password' => Hash::make('111'), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('doctors')->insert([
                'account_id' => $userId,
                'gender' => $gender,
                'age' => $age,
                'birthdate' => $birthdate,
                'specialties' => $specialties[array_rand($specialties)],
                'image_name' => $imageName,
                'image_data' => 'images/' . $imageName,
                'employment_date' => $date,
                'qualification' => $qualification[array_rand($qualification)],
                'years_of_experience' => $years,
                'phone' => $phone,
                'street' => $faker->streetAddress,
                'brgy' => $faker->city,
                'city' => $faker->city,
                'province' => $faker->city,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


        }
    }
}
