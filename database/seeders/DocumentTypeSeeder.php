<?php

namespace Database\Seeders;

use App\Models\Document_type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Lelet',
            'Recept',
            'Zarojelentes',
            'Beutalo',
            'Laboreredmeny',
            'Kepalkoto vizsgalat',
            'Korlap',
            'Egyeb',
        ];

        foreach ($types as $type) {
            Document_type::updateOrCreate(
                ['type' => $type],
                ['type' => $type]
            );
        }
    }
}
