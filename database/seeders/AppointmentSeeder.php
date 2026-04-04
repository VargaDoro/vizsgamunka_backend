<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor1 = User::where('email', 'kovacs.bela@clinic.hu')->first();
        $doctor2 = User::where('email', 'szabo.anna@clinic.hu')->first();
        $doctor3 = User::where('email', 'toth.gergely@clinic.hu')->first();
        $patient1 = User::where('email', 'patient1@test.com')->first();
        $patient2 = User::where('email', 'patient2@test.com')->first();

        $appointments = [
            [
                'doctor_id'        => $doctor1->id,
                'patient_id'       => $patient1->id,
                'appointment_time' => '2026-04-10 09:00:00',
                'status'           => 'booked',
            ],
            [
                'doctor_id'        => $doctor1->id,
                'patient_id'       => $patient2->id,
                'appointment_time' => '2026-04-10 10:00:00',
                'status'           => 'booked',
            ],
            [
                'doctor_id'        => $doctor2->id,
                'patient_id'       => $patient1->id,
                'appointment_time' => '2026-04-11 11:00:00',
                'status'           => 'booked',
            ],
            [
                'doctor_id'        => $doctor2->id,
                'patient_id'       => $patient2->id,
                'appointment_time' => '2026-04-14 14:30:00',
                'status'           => 'booked',
            ],
            [
                'doctor_id'        => $doctor3->id,
                'patient_id'       => $patient1->id,
                'appointment_time' => '2026-04-15 08:30:00',
                'status'           => 'booked',
            ],
            [
                'doctor_id'        => $doctor3->id,
                'patient_id'       => $patient2->id,
                'appointment_time' => '2026-04-16 13:00:00',
                'status'           => 'booked',
            ],
        ];

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }
    }
}
