<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

  public function run(): void
    {
        // Doctor 1
        User::create([
            'name' => 'Dr. Házi Orvos',
            'email' => 'doctor1@test.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
        ]);

        // Doctor 2
        User::create([
            'name' => 'Dr. Szakorvos',
            'email' => 'doctor2@test.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
        ]);
    }

}
