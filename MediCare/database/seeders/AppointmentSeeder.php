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

            foreach (range(1, 10) as $index) {

                $startDate = '2023-01-01';
                $endDate = now()->toDateString();
                $dateTime = $faker->dateTimeBetween($startDate, $endDate);
                $date = $dateTime->format('Y-m-d');
                $randomTime = $faker->time('H:i:s');
                $gender = $faker->randomElement(['male', 'female']);
                $birthdate = $faker->date($format = 'Y-m-d', $max = 'now');
                // Convert the 24-hour time to 12-hour format with AM/PM
                $dateTime = new \DateTime($randomTime);
                $formattedTime = $dateTime->format('h:i A');
                $firstName = $faker->firstName;
                $lastName = $faker->lastName;
                $type = ['Regular check-up', 'Follow-up appointment', 'Diagnostic appointment', 'specialist consultation'];
                $reason = ['Physical examinations', 'blood pressure checks', 'ge-appropriate screenings like mammograms or colonoscopies', 'Follow-up after surgery,'];
                $doctorId = $faker->numberBetween(4, 9);
                $digits = $faker->numerify('#########');
                $phone = '+639' . $digits;

                $attributes = [
                    'first_name' => $firstName,
                    'middle_name' => $faker->lastName,
                    'last_name' => $lastName,
                    'street' => $faker->streetAddress,
                    'brgy' => $faker->city,
                    'city' => $faker->city,
                    'province' => $faker->city,
                    'gender' => $gender,
                    'birthdate' => $birthdate,
                    'phone' => $phone,
                    'email' => $firstName . '.' . $lastName . '@gmail.com',
                    'account_id' => $insertedUserId,
                    'doctor_id' => $doctorId,
                    'appointment_date' => $date,
                    'appointment_time' => $formattedTime,
                    'appointment_type' => $type[array_rand($type)],
                    'status' => 'done',
                    'reason' => $reason[array_rand($reason)],
                    'created_at' => $date . ' ' . $time,
                    'updated_at' => $date . ' ' . $time,
                ];
    
                DB::table('appointments')->insert($attributes);
            }
        }
    }
}
