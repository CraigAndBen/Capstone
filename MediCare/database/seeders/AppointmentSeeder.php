<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Loop for Admitted Patients and Outpatients
        foreach (range(1, 5) as $index) {
            $startDate = '2023-11-01';
            $endDate = now()->toDateString();
            $dateTime = $faker->dateTimeBetween($startDate, $endDate);
            $date = $dateTime->format('Y-m-d');
            $time = $faker->time('H:i:s');
            $type = 'user';
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $attributes = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'role' => $type,
                'email' => $firstName . '.' . $lastName . '@gmail.com',
                'password' => Hash::make('111'),
                'created_at' => $date . ' ' . $time,
                'updated_at' => $date . ' ' . $time,
            ];

            $insertedUserId = DB::table('users')->insertGetId($attributes);

            foreach (range(1, 2) as $index) {

                $type = ['Regular check-up', 'Follow-up appointment', 'Diagnostic appointment', 'specialist consultation'];
                $doctorId = $faker->numberBetween(4, 9);
                $specialties = ['Cardiology', 'Dermatology', 'Orthopedics', 'Pediatrics', 'Ophthalmology'];

                $attributes = [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'account_id' => $insertedUserId,
                    'doctor_id' => $doctorId,
                    'specialties' => $specialties[array_rand($specialties)],
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                    'appointment_type' => $type[array_rand($type)],
                    'status' => 'done',
                    'created_at' => $date . ' ' . $time,
                    'updated_at' => $date . ' ' . $time,
                ];
    
                DB::table('appointments')->insert($attributes);
            }
        }
    }
}
