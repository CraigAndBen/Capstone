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

        // 2019 Loop for Admitted Patients and Outpatients
        foreach (range(1, 100) as $index) {
            $startDate = '2020-01-01';
            $endDate = '2020-12-30';
            $type = ($index <= 50) ? 'admitted_patient' : 'outpatient';
            $dateTime = $faker->dateTimeBetween($startDate, $endDate);
            $date = $dateTime->format('Y-m-d');
            $time = $faker->time('H:i:s');
            $gender = $faker->randomElement(['male', 'female']);
            $birthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $guardianBirthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $additionalDays = $faker->numberBetween(1, 40);
            $doctorId = $faker->numberBetween(9, 14);
            $newDate = (new \DateTime($date))->modify("+$additionalDays days")->format('Y-m-d');
            $roomNum = $faker->numberBetween(1, 100);
            $bedNum = $faker->numberBetween(1, 100);
            $medicalCondition = $faker->randomElement(['Common Cold', 'Influenza (Flu)', 'Allergies', 'Acne', 'Migraines']);
            $relationship = $faker->randomElement(['parent', 'legal Guardian', 'spouse', 'siblings', 'grandparent', 'aunt/Uncle', 'cousin', 'extended family member', 'foster Parent', 'close friend']);
            $guardianFirstName = $faker->firstName;
            $guardianLastName = $faker->lastName;
            $digits1 = $faker->numerify('#########');
            $phone = '+639' . $digits1;
            $digits2 = $faker->numerify('#########');
            $guardianPhone = '+639' . $digits2;

            $attributes = [
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'street' => $faker->streetAddress,
                'brgy' => $faker->city,
                'city' => $faker->city,
                'province' => $faker->city,
                'phone' => $phone,
                'gender' => $gender,
                'birthdate' => $birthdate,
                'type' => $type,
                'physician' => $doctorId,
                'room_number' => $roomNum,
                'bed_number' => $bedNum,
                'medical_condition' => $medicalCondition,
                'guardian_first_name' => $guardianFirstName,
                'guardian_last_name' => $guardianLastName,
                'guardian_birthdate' => $guardianBirthdate,
                'guardian_phone' => $guardianPhone,
                'guardian_email' => $guardianFirstName . '.' . '@gmail.com',
                'relationship' => $relationship,
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

            $diagnose = $faker->randomElement(['Coronary Artery Disease', 'Pneumonia', 'Stroke', 'Renal Failure', 'Gastrointestinal Bleeding']);

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

        // 2020 Loop for Admitted Patients and Outpatients
        foreach (range(1, 100) as $index) {
            $startDate = '2021-01-01';
            $endDate = '2021-12-30';
            $type = ($index <= 50) ? 'admitted_patient' : 'outpatient';
            $dateTime = $faker->dateTimeBetween($startDate, $endDate);
            $date = $dateTime->format('Y-m-d');
            $time = $faker->time('H:i:s');
            $gender = $faker->randomElement(['male', 'female']);
            $birthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $guardianBirthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $additionalDays = $faker->numberBetween(1, 40);
            $doctorId = $faker->numberBetween(9, 14);
            $newDate = (new \DateTime($date))->modify("+$additionalDays days")->format('Y-m-d');
            $roomNum = $faker->numberBetween(1, 100);
            $bedNum = $faker->numberBetween(1, 100);
            $medicalCondition = $faker->randomElement(['Common Cold', 'Influenza (Flu)', 'Allergies', 'Acne', 'Migraines']);
            $relationship = $faker->randomElement(['parent', 'legal Guardian', 'spouse', 'siblings', 'grandparent', 'aunt/Uncle', 'cousin', 'extended family member', 'foster Parent', 'close friend']);
            $guardianFirstName = $faker->firstName;
            $guardianLastName = $faker->lastName;
            $digits1 = $faker->numerify('#########');
            $phone = '+639' . $digits1;
            $digits2 = $faker->numerify('#########');
            $guardianPhone = '+639' . $digits2;

            $attributes = [
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'street' => $faker->streetAddress,
                'brgy' => $faker->city,
                'city' => $faker->city,
                'province' => $faker->city,
                'phone' => $phone,
                'gender' => $gender,
                'birthdate' => $birthdate,
                'type' => $type,
                'physician' => $doctorId,
                'room_number' => $roomNum,
                'bed_number' => $bedNum,
                'medical_condition' => $medicalCondition,
                'guardian_first_name' => $guardianFirstName,
                'guardian_last_name' => $guardianLastName,
                'guardian_birthdate' => $guardianBirthdate,
                'guardian_phone' => $guardianPhone,
                'guardian_email' => $guardianFirstName . '.' . '@gmail.com',
                'relationship' => $relationship,
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

            $diagnose = $faker->randomElement(['Coronary Artery Disease', 'Pneumonia', 'Stroke', 'Renal Failure', 'Gastrointestinal Bleeding']);

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

        // 2021 Loop for Admitted Patients and Outpatients
        foreach (range(1, 100) as $index) {
            $startDate = '2022-01-01';
            $endDate = '2022-12-30';
            $type = ($index <= 50) ? 'admitted_patient' : 'outpatient';
            $dateTime = $faker->dateTimeBetween($startDate, $endDate);
            $date = $dateTime->format('Y-m-d');
            $time = $faker->time('H:i:s');
            $gender = $faker->randomElement(['male', 'female']);
            $birthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $guardianBirthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $additionalDays = $faker->numberBetween(1, 40);
            $doctorId = $faker->numberBetween(9, 14);
            $newDate = (new \DateTime($date))->modify("+$additionalDays days")->format('Y-m-d');
            $roomNum = $faker->numberBetween(1, 100);
            $bedNum = $faker->numberBetween(1, 100);
            $medicalCondition = $faker->randomElement(['Common Cold', 'Influenza (Flu)', 'Allergies', 'Acne', 'Migraines']);
            $relationship = $faker->randomElement(['parent', 'legal Guardian', 'spouse', 'siblings', 'grandparent', 'aunt/Uncle', 'cousin', 'extended family member', 'foster Parent', 'close friend']);
            $guardianFirstName = $faker->firstName;
            $guardianLastName = $faker->lastName;
            $digits1 = $faker->numerify('#########');
            $phone = '+639' . $digits1;
            $digits2 = $faker->numerify('#########');
            $guardianPhone = '+639' . $digits2;

            $attributes = [
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'street' => $faker->streetAddress,
                'brgy' => $faker->city,
                'city' => $faker->city,
                'province' => $faker->city,
                'phone' => $phone,
                'gender' => $gender,
                'birthdate' => $birthdate,
                'type' => $type,
                'physician' => $doctorId,
                'room_number' => $roomNum,
                'bed_number' => $bedNum,
                'medical_condition' => $medicalCondition,
                'guardian_first_name' => $guardianFirstName,
                'guardian_last_name' => $guardianLastName,
                'guardian_birthdate' => $guardianBirthdate,
                'guardian_phone' => $guardianPhone,
                'guardian_email' => $guardianFirstName . '.' . '@gmail.com',
                'relationship' => $relationship,
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

            $diagnose = $faker->randomElement(['Coronary Artery Disease', 'Pneumonia', 'Stroke', 'Renal Failure', 'Gastrointestinal Bleeding']);

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

        // 2022 Loop for Admitted Patients and Outpatients
        foreach (range(1, 100) as $index) {
            $startDate = '2023-01-01';
            $endDate = '2023-12-30';
            $type = ($index <= 50) ? 'admitted_patient' : 'outpatient';
            $dateTime = $faker->dateTimeBetween($startDate, $endDate);
            $date = $dateTime->format('Y-m-d');
            $time = $faker->time('H:i:s');
            $gender = $faker->randomElement(['male', 'female']);
            $birthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $guardianBirthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $additionalDays = $faker->numberBetween(1, 40);
            $doctorId = $faker->numberBetween(9, 14);
            $newDate = (new \DateTime($date))->modify("+$additionalDays days")->format('Y-m-d');
            $roomNum = $faker->numberBetween(1, 100);
            $bedNum = $faker->numberBetween(1, 100);
            $medicalCondition = $faker->randomElement(['Common Cold', 'Influenza (Flu)', 'Allergies', 'Acne', 'Migraines']);
            $relationship = $faker->randomElement(['parent', 'legal Guardian', 'spouse', 'siblings', 'grandparent', 'aunt/Uncle', 'cousin', 'extended family member', 'foster Parent', 'close friend']);
            $guardianFirstName = $faker->firstName;
            $guardianLastName = $faker->lastName;
            $digits1 = $faker->numerify('#########');
            $phone = '+639' . $digits1;
            $digits2 = $faker->numerify('#########');
            $guardianPhone = '+639' . $digits2;

            $attributes = [
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'street' => $faker->streetAddress,
                'brgy' => $faker->city,
                'city' => $faker->city,
                'province' => $faker->city,
                'phone' => $phone,
                'gender' => $gender,
                'birthdate' => $birthdate,
                'type' => $type,
                'physician' => $doctorId,
                'room_number' => $roomNum,
                'bed_number' => $bedNum,
                'medical_condition' => $medicalCondition,
                'guardian_first_name' => $guardianFirstName,
                'guardian_last_name' => $guardianLastName,
                'guardian_birthdate' => $guardianBirthdate,
                'guardian_phone' => $guardianPhone,
                'guardian_email' => $guardianFirstName . '.' . '@gmail.com',
                'relationship' => $relationship,
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

            $diagnose = $faker->randomElement(['Coronary Artery Disease', 'Pneumonia', 'Stroke', 'Renal Failure', 'Gastrointestinal Bleeding']);

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

        // 2023 Loop for Admitted Patients and Outpatients
        foreach (range(1, 100) as $index) {
            $startDate = '2024-01-01';
            $endDate = now()->toDateString();
            $type = ($index <= 50) ? 'admitted_patient' : 'outpatient';
            $dateTime = $faker->dateTimeBetween($startDate, $endDate);
            $date = $dateTime->format('Y-m-d');
            $time = $faker->time('H:i:s');
            $gender = $faker->randomElement(['male', 'female']);
            $birthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $guardianBirthdate = $faker->date($format = 'Y-m-d', $max = 'now');
            $additionalDays = $faker->numberBetween(1, 40);
            $doctorId = $faker->numberBetween(9, 14);
            $newDate = (new \DateTime($date))->modify("+$additionalDays days")->format('Y-m-d');
            $roomNum = $faker->numberBetween(1, 100);
            $bedNum = $faker->numberBetween(1, 100);
            $medicalCondition = $faker->randomElement(['Common Cold', 'Influenza (Flu)', 'Allergies', 'Acne', 'Migraines']);
            $relationship = $faker->randomElement(['parent', 'legal Guardian', 'spouse', 'siblings', 'grandparent', 'aunt/Uncle', 'cousin', 'extended family member', 'foster Parent', 'close friend']);
            $guardianFirstName = $faker->firstName;
            $guardianLastName = $faker->lastName;
            $digits1 = $faker->numerify('#########');
            $phone = '+639' . $digits1;
            $digits2 = $faker->numerify('#########');
            $guardianPhone = '+639' . $digits2;

            $attributes = [
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'street' => $faker->streetAddress,
                'brgy' => $faker->city,
                'city' => $faker->city,
                'province' => $faker->city,
                'phone' => $phone,
                'gender' => $gender,
                'birthdate' => $birthdate,
                'type' => $type,
                'physician' => $doctorId,
                'room_number' => $roomNum,
                'bed_number' => $bedNum,
                'medical_condition' => $medicalCondition,
                'guardian_first_name' => $guardianFirstName,
                'guardian_last_name' => $guardianLastName,
                'guardian_birthdate' => $guardianBirthdate,
                'guardian_phone' => $guardianPhone,
                'guardian_email' => $guardianFirstName . '.' . '@gmail.com',
                'relationship' => $relationship,
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

            $diagnose = $faker->randomElement(['Coronary Artery Disease', 'Pneumonia', 'Stroke', 'Renal Failure', 'Gastrointestinal Bleeding']);

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
