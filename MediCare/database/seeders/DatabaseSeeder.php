<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // UserSeeder call
        $this->call(UserSeeder::class);
        $this->call(DoctorsSeeder::class);
        $this->call(PatientSeeder::class);
        $this->call(AppointmentSeeder::class);
        $this->call(NurseSeeder::class);
        $this->call(CashierSeeder::class);
        $this->call(PharmacistSeeder::class);
        $this->call(StaffSeeder::class);

        // \App\Models\User::factory(5)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
