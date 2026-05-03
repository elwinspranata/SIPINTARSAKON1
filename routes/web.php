<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ViolationTypeController;
use App\Http\Controllers\Admin\VitaminTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
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
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

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
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

