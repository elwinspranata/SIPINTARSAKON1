<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ViolationTypeSeeder::class,
            VitaminTypeSeeder::class,
        ]);

        // Admin User
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@sipintar.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Guru User
        User::factory()->create([
            'name' => 'Guru Pengajar',
            'email' => 'guru@sipintar.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        // Create some classes
        $classes = ['X E.1', 'X E.2', 'XI F.1', 'XI F.2', 'XII IPA 1', 'XII IPS 1'];
        foreach ($classes as $className) {
            $class = \App\Models\SchoolClass::create(['name' => $className]);
            
            // Create 5 students per class
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\Student::create([
                    'name' => "Siswa {$i} {$className}",
                    'nisn' => '12345' . $class->id . $i,
                    'class_id' => $class->id,
                    'gender' => $i % 2 == 0 ? 'P' : 'L',
                ]);
            }
        }
    }
}
