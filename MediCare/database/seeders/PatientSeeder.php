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
        foreach (range(1, 200) as $index) {
            $startDate = ($index <= 100) ? '2022-01-01' : '2023-01-01';
            $endDate = ($index <= 100) ? '2022-12-30' : now()->toDateString();
            if($index <= 50 || $index >= 150){
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
            $doctorId = $faker->numberBetween(4, 9);
            $newDate = (new \DateTime($date))->modify("+$additionalDays days")->format('Y-m-d');

            $attributes = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'gender' => $gender,
                'birthdate' => $birthdate,
                'type' => $type,
                'physician' => $doctorId,
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

            $insertedPatientId = DB::table('patients')->insertGetId($attributes);

            $diagnose = $faker->randomElement(['Coronary Artery Disease', 'Pneumonia', 'Stroke', 'Renal Failure' ,'Gastrointestinal Bleeding']);

            $attributes = [
                'patient_id' => $insertedPatientId,
                'patient_type' => $type,
                'diagnose' => $diagnose,
                'date' => $date,
                'time' => $time,
                'created_at' => $date . ' ' . $time,
                'updated_at' => $date . ' ' . $time,
            ];

            DB::table('diagnoses')->insert($attributes);

            $medication = $faker->randomElement(['Aspirin', 'Morphine', 'Warfarin', 'Furosemide', 'Antibiotics']);
            $dosage = $faker->randomElement(['81 mg', '2 mg', '5 mg', '40 mg', '1 g']);
            $duration = $faker->randomElement(['7 days', 'As needed for pain', 'Until INR is in the therapeutic range', '3 days', '2 days']);

            $attributes = [
                'patient_id' => $insertedPatientId,
                'patient_type' => $type,
                'medication_name' => $medication,
                'dosage' => $dosage,
                'duration' => $duration,
                'date' => $date,
                'time' => $time,
                'created_at' => $date . ' ' . $time,
                'updated_at' => $date . ' ' . $time,
            ];

            DB::table('medications')->insert($attributes);
        }
    }
}
