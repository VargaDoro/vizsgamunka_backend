<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Document_type;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = User::where('role', 'patient')->get();
        $doctors  = User::where('role', 'doctor')->get();
        $defaultType = Document_type::query()->orderBy('id')->first();

        if ($patients->isEmpty() || $doctors->isEmpty() || !$defaultType) {
            throw new \Exception('Nincs eleg beteg, orvos vagy dokumentum tipus a dokumentumok seedelesehez.');
        }

        foreach ($patients as $patient) {
            Document::create([
                'doctor_id'  => $doctors->random()->id,
                'patient_id' => $patient->id,
                'document_type_id' => $defaultType->id,
                'file_path'  => 'documents/test.pdf',
                'created_at' => now(),
            ]);
        }
    }
}

        /*
        $doctor1 = User::where('email', 'kovacs.bela@clinic.hu')->first();
        $doctor2 = User::where('email', 'szabo.anna@clinic.hu')->first();
        $doctor3 = User::where('email', 'toth.gergely@clinic.hu')->first();

        $patient1 = User::where('email', 'patient1@test.com')->first();

        
        $patient2 = User::where('email', 'patient2@test.com')->first();
        $patient3 = User::where('email', 'patient3@test.com')->first();
        $patient4 = User::where('email', 'patient4@test.com')->first();

        $documents = [
            [
                'doctor_id'  => $doctor1->id,
                'patient_id' => $patient1->id,
                'type'       => 'Lelet',
                'file_path'  => 'documents/lelet_patient1_1.pdf',
                'created_at' => '2026-03-02 10:00:00',
            ],
            [
                'doctor_id'  => $doctor1->id,
                'patient_id' => $patient2->id,
                'type'       => 'Laboreredmeny',
                'file_path'  => 'documents/laboreredmeny_patient2_1.pdf',
                'created_at' => '2026-03-06 11:30:00',
            ],
            [
                'doctor_id'  => $doctor2->id,
                'patient_id' => $patient1->id,
                'type'       => 'Zarojelentes',
                'file_path'  => 'documents/zarojelentes_patient1_2.pdf',
                'created_at' => '2026-03-11 09:00:00',
            ],
            [
                'doctor_id'  => $doctor2->id,
                'patient_id' => $patient3->id,
                'type'       => 'Beutalo',
                'file_path'  => 'documents/beutalo_patient3_1.pdf',
                'created_at' => '2026-03-16 14:00:00',
            ],
            [
                'doctor_id'  => $doctor3->id,
                'patient_id' => $patient2->id,
                'type'       => 'Korlap',
                'file_path'  => 'documents/korlap_patient2_1.pdf',
                'created_at' => '2026-02-21 08:30:00',
            ],
            [
                'doctor_id'  => $doctor3->id,
                'patient_id' => $patient4->id,
                'type'       => 'Kepalkoto vizsgalat',
                'file_path'  => 'documents/kepalkoto_patient4_1.pdf',
                'created_at' => '2026-03-21 15:00:00',
            ],
        ];


        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
*/