<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ViolationTypeController;
use App\Http\Controllers\Admin\VitaminTypeController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Pending approval page (authenticated but not approved)
Route::middleware(['auth'])->group(function () {
    Route::get('/approval/pending', function () {
        if (auth()->user()->is_approved) {
            return redirect()->route('dashboard');
        }
        return view('auth.pending-approval');
    })->name('approval.pending');
});

// Main authenticated + approved routes
Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Records (Pelanggaran & Vitamin)
    Route::get('/records/create', [RecordController::class, 'create'])->name('records.create');
    Route::post('/records/store', [RecordController::class, 'store'])->name('records.store');
    Route::get('/records', [RecordController::class, 'index'])->name('records.index');
    Route::get('/records/{record}/edit', [RecordController::class, 'edit'])->name('records.edit');
    Route::put('/records/{record}', [RecordController::class, 'update'])->name('records.update');
    Route::delete('/records/{record}', [RecordController::class, 'destroy'])->name('records.destroy');

    // Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::delete('/students/bulk-delete', [StudentController::class, 'bulkDestroy'])->name('students.bulkDestroy');
    Route::post('/students/reset-points', [StudentController::class, 'resetPoints'])->name('students.resetPoints');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/students/{student}/points', [StudentController::class, 'getPoints'])->name('students.points');
    Route::get('/students/{student}/letter', [StudentController::class, 'letter'])->name('students.letter');

    // Classes (Kelola Kelas)
    Route::get('/classes', [SchoolClassController::class, 'index'])->name('classes.index');
    Route::post('/classes', [SchoolClassController::class, 'store'])->name('classes.store');
    Route::put('/classes/{schoolClass}', [SchoolClassController::class, 'update'])->name('classes.update');
    Route::patch('/classes/{schoolClass}/toggle-status', [SchoolClassController::class, 'toggleStatus'])->name('classes.toggleStatus');
    Route::delete('/classes/{schoolClass}', [SchoolClassController::class, 'destroy'])->name('classes.destroy');
    Route::get('/classes/{schoolClass}/students', [SchoolClassController::class, 'getStudents'])->name('classes.students');
    Route::post('/classes/bulk-transfer', [SchoolClassController::class, 'bulkTransfer'])->name('classes.bulkTransfer');

    // Admin routes
    Route::middleware(['can:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Jenis Pelanggaran (Penyakit)
        Route::get('/violation-types', [ViolationTypeController::class, 'index'])->name('violation-types.index');
        Route::post('/violation-types', [ViolationTypeController::class, 'store'])->name('violation-types.store');
        Route::put('/violation-types/{violationType}', [ViolationTypeController::class, 'update'])->name('violation-types.update');
        Route::delete('/violation-types/{violationType}', [ViolationTypeController::class, 'destroy'])->name('violation-types.destroy');

        // Kategori Vitamin
        Route::get('/vitamin-types', [VitaminTypeController::class, 'index'])->name('vitamin-types.index');
        Route::post('/vitamin-types', [VitaminTypeController::class, 'store'])->name('vitamin-types.store');
        Route::put('/vitamin-types/{vitaminType}', [VitaminTypeController::class, 'update'])->name('vitamin-types.update');
        Route::delete('/vitamin-types/{vitaminType}', [VitaminTypeController::class, 'destroy'])->name('vitamin-types.destroy');

        // Kelola User Guru
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::delete('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
