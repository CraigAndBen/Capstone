<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NurseSeeder extends Seeder
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
            $birthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $date = $faker->date($format = 'Y-m-d', $max = 'now');
            $age = $faker->numberBetween(1, 70);
            $years = $faker->numberBetween(1, 30);
            $gender = $faker->randomElement(['male', 'female']);
            $qualification = $faker->randomElement(['Certified Nursing Assistant (CNA)', 'Licensed Practical Nurse (LPN) or Licensed Vocational Nurse (LVN)','Registered Nurse (RN)']);
            $shift = $faker->randomElement(['day', 'night','rotating shift']);
            $digits = $faker->numerify('#########');
            $phone = '+639' . $digits;

            $userId = DB::table('users')->insertGetId([
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'email' => $firstName . '.'. $lastName . '@gmail.com',
                'role' => 'nurse',
                'password' => Hash::make('12341234'), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('nurses')->insert([
                'account_id' => $userId,
                'gender' => $gender,
                'age' => $age,
                'birthdate' => $birthdate,
                'employment_date' => $date,
                'qualification' => $qualification,
                'years_of_experience' => $years,
                'phone' => $phone,
                'street' => $faker->streetAddress,
                'brgy' => $faker->city,
                'city' => $faker->city,
                'shift' => $shift,
                'province' => $faker->city,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


        }
    }
}
