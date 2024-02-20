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
        $users = [
            // Admin
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'admin',
                'status' => 'activated',
            ],      

            // Super Admin
            [
                'first_name' => 'Maria Carla',
                'last_name' => 'Castro',
                'email' => 'ma.castro@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'super_admin',
                'status' => 'activated',
            ],

            // Supply Officer
            [
                'first_name' => 'Mae',
                'last_name' => 'Noarin',
                'email' => 'maenoarin@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'supply_officer',
                'status' => 'activated',
            ],

            // Pharmacist
            [
                'first_name' => 'Katrina',
                'last_name' => 'Folloso',
                'email' => 'katfolloso@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'pharmacist',
                'status' => 'activated',
            ],

            // Cashier
            [
                'first_name' => 'Roselle',
                'last_name' => 'Dato',
                'email' => 'rosdato@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'cashier',
                'status' => 'activated',
            ],

            // Staff
            [
                'first_name' => 'Hazel',
                'last_name' => 'Villar',
                'email' => 'hazvillar@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'staff',
                'status' => 'activated',
            ],

            [
                'first_name' => 'Lorelie',
                'last_name' => 'Sarion',
                'email' => 'lorsarion@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'staff',
                'status' => 'activated',
            ],

            [
                'first_name' => 'Gretchen',
                'last_name' => 'Yutuc',
                'email' => 'greyutuc@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'staff',
                'status' => 'activated',
            ],
        ];

        // Insert data into the 'users' table
        DB::table('users')->insert($users);
    }
}
