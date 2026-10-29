<?php

namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $polis = [
            [
                'code' => 'POLI-UMUM',
                'name' => 'Poli Umum',
                'queue_prefix' => 'A',
                'is_active' => true,
            ],
            [
                'code' => 'POLI-GIGI',
                'name' => 'Poli Gigi & Mulut',
                'queue_prefix' => 'B',
                'is_active' => true,
            ],
            [
                'code' => 'POLI-KIA',
                'name' => 'Poli KIA & Anak',
                'queue_prefix' => 'C',
                'is_active' => true,
            ],
            [
                'code' => 'POLI-INT',
                'name' => 'Poli Penyakit Dalam',
                'queue_prefix' => 'D',
                'is_active' => true,
            ],
        ];

        foreach ($polis as $poli) {
            Poli::updateOrCreate(
                ['code' => $poli['code']],
                $poli
            );
        }
    }
}
