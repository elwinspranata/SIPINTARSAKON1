<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/extracted_teachers.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error("JSON file not found at {$jsonPath}");
            return;
        }

        $data = json_decode(File::get($jsonPath), true);
        
        $this->command->info("Seeding " . count($data) . " teachers and staff...");
        
        $count = 0;
        foreach ($data as $teacherData) {
            $name = $teacherData['name'];
            
            // Skip placeholders or empty names
            if (!$name || trim($name) === '' || strtoupper(trim($name)) === 'KARYAWAN') {
                continue;
            }

            // Generate email from name
            // Remove titles like S.Pd, M.Pd etc for cleaner email
            $nameForEmail = preg_replace('/,.*$/', '', $name); // Remove everything after comma
            $nameForEmail = preg_replace('/\b(S\.Pd|M\.Pd|Drs|Hj|SE|S\.Ag|S\.IP|A\.Md|S\.Kom|S\.Pd\.I)\b/i', '', $nameForEmail);
            
            $cleanName = Str::slug(trim($nameForEmail), '');
            if (empty($cleanName)) {
                $cleanName = Str::slug($name, '');
            }
            
            $email = $cleanName . '@sipintar.com';
            
            // Check if user already exists by name
            $existingUser = User::where('name', $name)->first();
            
            if (!$existingUser) {
                // If not exists by name, check if email is taken
                $baseEmail = $cleanName;
                $i = 1;
                while (User::where('email', $email)->exists()) {
                    $email = $baseEmail . $i . '@sipintar.com';
                    $i++;
                }
            }

            User::updateOrCreate(
                ['name' => $name],
                [
                    'email' => $existingUser ? $existingUser->email : $email,
                    'password' => $existingUser ? $existingUser->password : bcrypt('password'),
                    'role' => 'guru',
                    'staff_id' => $teacherData['no'],
                    'is_approved' => true,
                ]
            );
            $count++;
        }

        $this->command->info("Finished seeding {$count} teachers.");
    }
}
