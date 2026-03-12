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
        User::create([
            'name' => 'Dr. Kovács Béla',
            'email' => 'kovacs.bela@clinic.hu',
            'password' => Hash::make('password123'),
            'role' => 'doctor',

            'license_number' => 'LIC1001',
            'specialization' => 'Kardiológus',
            'phone_number' => '+36 30 123 4567',
            'office_location_id' => 1,
        ]);

        User::create([
            'name' => 'Dr. Szabó Anna',
            'email' => 'szabo.anna@clinic.hu',
            'password' => Hash::make('password123'),
            'role' => 'doctor',

            'license_number' => 'LIC1002',
            'specialization' => 'Bőrgyógyász',
            'phone_number' => '+36 30 987 6543',
            'office_location_id' => 2,
        ]);

        User::create([
            'name' => 'Dr. Tóth Gergely',
            'email' => 'toth.gergely@clinic.hu',
            'password' => Hash::make('password123'),
            'role' => 'doctor',

            'license_number' => 'LIC1003',
            'specialization' => 'Pszichológus',
            'phone_number' => '+36 30 444 1122',
            'office_location_id' => 3,
        ]);
    }
}
