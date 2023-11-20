<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Loop for Admitted Patients and Outpatients
        foreach (range(1, 100) as $index) {
            $startDate = ($index <= 50) ? '2022-01-01' : '2023-01-01';
            $endDate = ($index <= 50) ? '2022-12-30' : now()->toDateString();
            if($index <= 25 || $index >= 75){
                $type = 'admitted_patient';
            } else {
                $type = 'outpatient';
            }
            $dateTime = $faker->dateTimeBetween($startDate, $endDate);
            $date = $dateTime->format('Y-m-d');
            $time = $faker->time('H:i:s');
            $gender = $faker->randomElement(['male', 'female']);
            $birthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $additionalDays = $faker->numberBetween(1, 40);
            $newDate = (new \DateTime($date))->modify("+$additionalDays days")->format('Y-m-d');

            $attributes = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'gender' => $gender,
                'birthdate' => $birthdate,
                'type' => $type,
                'created_at' => $date . ' ' . $time,
                'updated_at' => $date . ' ' . $time,
            ];

            // Add specific fields for admitted patients
            if ($type === 'admitted_patient') {
                $attributes += [
                    'admitted_date' => $date,
                    'admitted_time' => $time,
                    'discharged_date' => $newDate,
                    'discharged_time' => $time,
                ];
            }

            // Add specific fields for outpatients
            if ($type === 'outpatient') {
                $attributes += [
                    'date' => $date,
                    'time' => $time,
                ];
            }

            DB::table('patients')->insert($attributes);
        }
    }
}
