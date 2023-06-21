<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            
            // User
            [
                'first_name' => 'User',
                'last_name' => 'User',
                'username' => 'user',
                'email' => 'user@sample.com',
                'password' => Hash::make('111'),
                'role' => 'user',
                'status' => 'active',
            ],
            
            // Nurse
            [
                'first_name' => 'Nurse',
                'last_name' => 'Nurse',
                'username' => 'nurse',
                'email' => 'nurse@sample.com',
                'password' => Hash::make('111'),
                'role' => 'nurse',
                'status' => 'active',
            ],

            // Doctor
            [
                'first_name' => 'Doctor',
                'last_name' => 'Doctor',
                'username' => 'doctor',
                'email' => 'doctor@sample.com',
                'password' => Hash::make('111'),
                'role' => 'doctor',
                'status' => 'active',
            ],    
            
            // Admin
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@sample.com',
                'password' => Hash::make('111'),
                'role' => 'admin',
                'status' => 'active',
            ],      
            
            // Super Admin
            [
                'first_name' => 'Super Admin',
                'last_name' => 'Super Admin',
                'username' => 'super_admin',
                'email' => 'super_admin@sample.com',
                'password' => Hash::make('111'),
                'role' => 'super_admin',
                'status' => 'active',
            ],
            
        ]);
    }
}
