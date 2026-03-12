<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficeLocation;

class OfficeLocationSeeder extends Seeder
{
    public function run(): void
    {
        OfficeLocation::create([
            'room_number' => '13',
            'building' => '2. emelet',
        ]);

        OfficeLocation::create([
            'room_number' => '5',
            'building' => '1. emelet',
        ]);

        OfficeLocation::create([
            'room_number' => '101',
            'building' => 'Földszint',
        ]);
    }
}