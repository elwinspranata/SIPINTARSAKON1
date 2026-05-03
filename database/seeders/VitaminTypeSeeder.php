<?php

namespace Database\Seeders;

use App\Models\VitaminType;
use Illuminate\Database\Seeder;

class VitaminTypeSeeder extends Seeder
{
    public function run(): void
    {
        $vitamins = [
            // Akademik
            ['category' => 'Akademik', 'name' => 'Juara kelas (Ranking 1-3)', 'points' => 30],
            ['category' => 'Akademik', 'name' => 'Nilai ujian sempurna (100)', 'points' => 20],
            ['category' => 'Akademik', 'name' => 'Mewakili sekolah di olimpiade', 'points' => 25],
            ['category' => 'Akademik', 'name' => 'Aktif bertanya / menjawab di kelas', 'points' => 5],
            ['category' => 'Akademik', 'name' => 'Mengerjakan tugas tambahan', 'points' => 10],

            // Non-Akademik
            ['category' => 'Non-Akademik', 'name' => 'Juara lomba olahraga', 'points' => 25],
            ['category' => 'Non-Akademik', 'name' => 'Juara lomba seni / budaya', 'points' => 25],
            ['category' => 'Non-Akademik', 'name' => 'Hafal Juz 30 / Kitab Suci', 'points' => 30],
            ['category' => 'Non-Akademik', 'name' => 'Aktif di kegiatan OSIS', 'points' => 15],
            ['category' => 'Non-Akademik', 'name' => 'Menjadi pembina upacara', 'points' => 10],

            // Sosial
            ['category' => 'Sosial', 'name' => 'Membantu teman yang kesulitan', 'points' => 5],
            ['category' => 'Sosial', 'name' => 'Aksi sosial / bakti sosial', 'points' => 15],
            ['category' => 'Sosial', 'name' => 'Menjaga kebersihan lingkungan', 'points' => 5],
            ['category' => 'Sosial', 'name' => 'Melaporkan tindakan bullying', 'points' => 10],
            ['category' => 'Sosial', 'name' => 'Menjadi mediator perdamaian', 'points' => 15],
        ];

        foreach ($vitamins as $vitamin) {
            VitaminType::create($vitamin);
        }
    }
}
