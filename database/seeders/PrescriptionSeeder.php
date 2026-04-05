<?php

namespace Database\Seeders;

use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $doctor1 = User::where('email', 'kovacs.bela@clinic.hu')->first();
        $doctor2 = User::where('email', 'szabo.anna@clinic.hu')->first();
        $doctor3 = User::where('email', 'toth.gergely@clinic.hu')->first();

        $patient1 = User::where('email', 'patient1@test.com')->first();
        $patient2 = User::where('email', 'patient2@test.com')->first();
        $patient3 = User::where('email', 'patient3@test.com')->first();
        $patient4 = User::where('email', 'patient4@test.com')->first();

        $prescriptions = [
            [
                'doctor_id'     => $doctor1->id,
                'patient_id'    => $patient1->id,
                'medicine_name' => 'Amlodipin',
                'dosage'        => 'Napi 1x 5mg',
                'issued_at'     => '2026-03-01',
                'valid_until'   => '2026-06-01',
            ],
            [
                'doctor_id'     => $doctor1->id,
                'patient_id'    => $patient2->id,
                'medicine_name' => 'Metoprolol',
                'dosage'        => 'Napi 2x 50mg',
                'issued_at'     => '2026-03-05',
                'valid_until'   => '2026-05-05',
            ],
            [
                'doctor_id'     => $doctor2->id,
                'patient_id'    => $patient1->id,
                'medicine_name' => 'Hidrokortison krem',
                'dosage'        => 'Napi 2x vekonyan felvinni',
                'issued_at'     => '2026-03-10',
                'valid_until'   => '2026-04-10',
            ],
            [
                'doctor_id'     => $doctor2->id,
                'patient_id'    => $patient3->id,
                'medicine_name' => 'Doxiciklin',
                'dosage'        => 'Napi 1x 100mg',
                'issued_at'     => '2026-03-15',
                'valid_until'   => '2026-04-15',
            ],
            [
                'doctor_id'     => $doctor3->id,
                'patient_id'    => $patient2->id,
                'medicine_name' => 'Sertralin',
                'dosage'        => 'Napi 1x 50mg',
                'issued_at'     => '2026-02-20',
                'valid_until'   => '2026-08-20',
            ],
            [
                'doctor_id'     => $doctor3->id,
                'patient_id'    => $patient4->id,
                'medicine_name' => 'Alprazolam',
                'dosage'        => 'Napi 2x 0.25mg',
                'issued_at'     => '2026-03-20',
                'valid_until'   => '2026-06-20',
            ],
        ];

        foreach ($prescriptions as $prescription) {
            Prescription::create($prescription);
        }
    }
}
