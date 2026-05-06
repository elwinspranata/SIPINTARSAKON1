<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\File;

class ImportExcelStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import students from JSON generated from Excel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonPath = base_path('scratch/students_data.json');
        
        if (!File::exists($jsonPath)) {
            $this->error("File $jsonPath tidak ditemukan!");
            return Command::FAILURE;
        }

        $data = json_decode(File::get($jsonPath), true);
        
        $this->info("Ditemukan " . count($data) . " baris data siswa.");

        $classesMap = [];
        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($data as $row) {
            $className = trim($row['class_name']);
            $tingkat = trim($row['tingkat']);
            
            // Map class if not mapped yet
            if (!isset($classesMap[$className])) {
                $class = SchoolClass::firstOrCreate(
                    ['name' => $className],
                    ['tingkat' => $tingkat, 'is_active' => true]
                );
                $classesMap[$className] = $class->id;
                $this->info("Kelas $className disinkronisasi (ID: {$class->id}).");
            }
            
            $classId = $classesMap[$className];
            $nisn = $row['nisn'];
            $gender = $row['gender'];
            $name = $row['name'];

            if ($nisn) {
                $student = Student::where('nisn', $nisn)->first();
                if ($student) {
                    $student->update([
                        'name' => $name,
                        'class_id' => $classId,
                        'gender' => $gender
                    ]);
                    $updated++;
                } else {
                    Student::create([
                        'name' => $name,
                        'nisn' => $nisn,
                        'class_id' => $classId,
                        'gender' => $gender
                    ]);
                    $inserted++;
                }
            } else {
                // If NISN is missing, try to find by Name and Class
                $student = Student::where('name', $name)->where('class_id', $classId)->first();
                if ($student) {
                    // Update
                    $student->update(['gender' => $gender]);
                    $updated++;
                } else {
                    Student::create([
                        'name' => $name,
                        'nisn' => null,
                        'class_id' => $classId,
                        'gender' => $gender
                    ]);
                    $inserted++;
                }
            }
        }

        $this->info("Import Selesai!");
        $this->info("Baru (Insert): $inserted");
        $this->info("Diperbarui (Update): $updated");
        
        return Command::SUCCESS;
    }
}
