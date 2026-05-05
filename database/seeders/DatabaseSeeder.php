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
            StudentExcelSeeder::class,
            TeacherSeeder::class,
        ]);

        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@sipintar.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_approved' => true,
            ]
        );

        // Guru User
        User::firstOrCreate(
            ['email' => 'guru@sipintar.com'],
            [
                'name' => 'Guru Pengajar',
                'password' => bcrypt('password'),
                'role' => 'guru',
                'is_approved' => true,
            ]
        );
    }
}
