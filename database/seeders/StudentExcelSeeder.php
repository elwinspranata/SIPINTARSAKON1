<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StudentExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/extracted_students.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error("JSON file not found at {$jsonPath}");
            return;
        }

        $data = json_decode(File::get($jsonPath), true);
        
        $this->command->info("Seeding " . count($data['classes']) . " classes...");
        
        $classMap = [];
        foreach ($data['classes'] as $classData) {
            $schoolClass = SchoolClass::updateOrCreate(
                ['name' => $classData['name']],
                [
                    'tingkat' => $classData['tingkat'],
                    'is_active' => true
                ]
            );
            $classMap[$classData['name']] = $schoolClass->id;
        }

        $this->command->info("Seeding " . count($data['students']) . " students...");
        
        $count = 0;
        $skipped = 0;
        foreach ($data['students'] as $studentData) {
            if (!isset($classMap[$studentData['class_name']])) {
                continue;
            }

            try {
                // If NISN is empty or just zeros, we identify by Name + Class
                if (empty($studentData['nisn']) || trim($studentData['nisn'], '0 ') === '') {
                    Student::updateOrCreate(
                        [
                            'name' => $studentData['name'],
                            'class_id' => $classMap[$studentData['class_name']],
                        ],
                        [
                            'gender' => $studentData['gender'],
                            'nisn' => $studentData['nisn'] ?: null
                        ]
                    );
                } else {
                    // Try to identify by NISN
                    Student::updateOrCreate(
                        ['nisn' => $studentData['nisn']],
                        [
                            'name' => $studentData['name'],
                            'class_id' => $classMap[$studentData['class_name']],
                            'gender' => $studentData['gender']
                        ]
                    );
                }
                $count++;
            } catch (\Exception $e) {
                $this->command->warn("Failed to seed student: {$studentData['name']} ({$studentData['nisn']}). Error: " . $e->getMessage());
                $skipped++;
            }
            
            if ($count % 100 == 0) {
                $this->command->info("Processed {$count} students...");
            }
        }

        $this->command->info("Finished seeding {$count} students. Skipped {$skipped}.");
    }
}
