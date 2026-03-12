<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        User::create([
            'name' => 'Beteg1',
            'email' => 'patient1@test.com',
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Beteg2',
            'email' => 'patient2@test.com',
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);
    }
}
