<?php

namespace Database\Seeders;

use App\Models\ViolationType;
use Illuminate\Database\Seeder;

class ViolationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $violations = [
            // Ringan - Kerapian
            ['category' => 'Ringan', 'sub_category' => 'Kerapian', 'name' => 'Kancing baju tidak dikancingkan', 'points' => 5],
            ['category' => 'Ringan', 'sub_category' => 'Kerapian', 'name' => 'Rambut panjang / tidak rapi', 'points' => 5],
            ['category' => 'Ringan', 'sub_category' => 'Kerapian', 'name' => 'Seragam tidak sesuai ketentuan', 'points' => 5],
            
            // Ringan - Kehadiran
            ['category' => 'Ringan', 'sub_category' => 'Kehadiran', 'name' => 'Terlambat masuk sekolah', 'points' => 5],
            ['category' => 'Ringan', 'sub_category' => 'Kehadiran', 'name' => 'Bolos jam pelajaran', 'points' => 10],
            ['category' => 'Ringan', 'sub_category' => 'Kehadiran', 'name' => 'Tidak mengikuti upacara', 'points' => 10],
            
            // Ringan - Kelakuan
            ['category' => 'Ringan', 'sub_category' => 'Kelakuan', 'name' => 'Membuang sampah sembarangan', 'points' => 5],
            ['category' => 'Ringan', 'sub_category' => 'Kelakuan', 'name' => 'Makan / minum saat KBM', 'points' => 5],
            ['category' => 'Ringan', 'sub_category' => 'Kelakuan', 'name' => 'Berkelahi ringan / bercanda berlebihan', 'points' => 10],
            
            // Sedang
            ['category' => 'Sedang', 'sub_category' => 'Kedisiplinan', 'name' => 'Pinjam meminjam barang berharga tanpa izin', 'points' => 20],
            ['category' => 'Sedang', 'sub_category' => 'Etika', 'name' => 'Bullying ringan (verbal)', 'points' => 25],
            ['category' => 'Sedang', 'sub_category' => 'Etika', 'name' => 'Ujaran kebencian / SARA', 'points' => 30],
            ['category' => 'Sedang', 'sub_category' => 'Kerapian', 'name' => 'Penggunaan aksesoris berlebihan (laki-laki)', 'points' => 15],
            
            // Berat - Level 1
            ['category' => 'Berat', 'sub_category' => 'Level 1', 'name' => 'Membawa / menggunakan HP saat KBM tanpa izin', 'points' => 50],
            ['category' => 'Berat', 'sub_category' => 'Level 1', 'name' => 'Merokok di lingkungan sekolah', 'points' => 50],
            ['category' => 'Berat', 'sub_category' => 'Level 1', 'name' => 'Menyimpan media pornografi', 'points' => 50],
            
            // Berat - Level 2
            ['category' => 'Berat', 'sub_category' => 'Level 2', 'name' => 'Membawa senjata tajam', 'points' => 75],
            ['category' => 'Berat', 'sub_category' => 'Level 2', 'name' => 'Penyalahgunaan Narkoba / Miras', 'points' => 100],
            ['category' => 'Berat', 'sub_category' => 'Level 2', 'name' => 'Tindakan Asusila', 'points' => 100],
            ['category' => 'Berat', 'sub_category' => 'Level 2', 'name' => 'Mencuri', 'points' => 75],
            ['category' => 'Berat', 'sub_category' => 'Level 2', 'name' => 'Tawuran', 'points' => 100],
            ['category' => 'Berat', 'sub_category' => 'Level 2', 'name' => 'Melawan / menganiaya guru/staf', 'points' => 100],
        ];

        foreach ($violations as $violation) {
            ViolationType::create($violation);
        }
    }
}
