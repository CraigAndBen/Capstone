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
                'email' => 'admin@sample.com',
                'password' => Hash::make('111'),
                'role' => 'admin',
                'status' => 'activated',
            ],      

            // Super Admin
            [
                'first_name' => 'Super Admin',
                'last_name' => 'Super Admin',
                'email' => 'super_admin@sample.com',
                'password' => Hash::make('111'),
                'role' => 'super_admin',
                'status' => 'activated',
            ],

            // Supply Officer
            [
                'first_name' => 'Supply Officer',
                'last_name' => 'Supply Officer',
                'email' => 'supply_officer@sample.com',
                'password' => Hash::make('111'),
                'role' => 'supply_officer',
                'status' => 'activated',
            ],
        ];

        // Insert data into the 'users' table
        DB::table('users')->insert($users);
    }
}
