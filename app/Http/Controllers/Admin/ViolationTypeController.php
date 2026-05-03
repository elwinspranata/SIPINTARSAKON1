<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationType;
use App\Models\BehaviorRecord;
use Illuminate\Http\Request;

class ViolationTypeController extends Controller
{
    public function index()
    {
        $violations = ViolationType::all()->groupBy('category');
        return view('admin.violation-types', compact('violations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Ringan,Sedang,Berat',
            'sub_category' => 'nullable|string|max:255',
            'points' => 'required|integer|min:1|max:200',
        ]);

        ViolationType::create($request->only(['name', 'category', 'sub_category', 'points']));

        return redirect()->route('admin.violation-types.index')
            ->with('success', 'Jenis pelanggaran berhasil ditambahkan!');
    }

    public function update(Request $request, ViolationType $violationType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Ringan,Sedang,Berat',
            'sub_category' => 'nullable|string|max:255',
            'points' => 'required|integer|min:1|max:200',
        ]);

        $violationType->update($request->only(['name', 'category', 'sub_category', 'points']));

        return redirect()->route('admin.violation-types.index')
            ->with('success', 'Jenis pelanggaran berhasil diperbarui!');
    }

    public function destroy(ViolationType $violationType)
    {
        // Check if there are related behavior records
        $recordCount = BehaviorRecord::where('violation_type_id', $violationType->id)->count();
        
        if ($recordCount > 0) {
            return redirect()->route('admin.violation-types.index')
                ->with('error', "Tidak dapat menghapus! Terdapat {$recordCount} catatan siswa yang menggunakan jenis pelanggaran ini.");
        }

        $violationType->delete();

        return redirect()->route('admin.violation-types.index')
            ->with('success', 'Jenis pelanggaran berhasil dihapus!');
    }
}
