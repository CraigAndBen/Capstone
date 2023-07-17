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
            
            
            // Admin
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@sample.com',
                'password' => Hash::make('111'),
                'role' => 'admin',
                'status' => 'active',
            ],      
            
            // Super Admin
            [
                'first_name' => 'Super Admin',
                'last_name' => 'Super Admin',
                'email' => 'super_admin@sample.com',
                'password' => Hash::make('111'),
                'role' => 'super_admin',
                'status' => 'active',
            ],
            
        ]);
    }
}
