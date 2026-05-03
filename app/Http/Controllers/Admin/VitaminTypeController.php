<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VitaminType;
use App\Models\BehaviorRecord;
use Illuminate\Http\Request;

class VitaminTypeController extends Controller
{
    public function index()
    {
        $vitamins = VitaminType::all()->groupBy('category');
        return view('admin.vitamin-types', compact('vitamins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Akademik,Non-Akademik,Sosial',
            'points' => 'required|integer|min:1|max:200',
        ]);

        VitaminType::create($request->only(['name', 'category', 'points']));

        return redirect()->route('admin.vitamin-types.index')
            ->with('success', 'Kategori vitamin berhasil ditambahkan!');
    }

    public function update(Request $request, VitaminType $vitaminType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Akademik,Non-Akademik,Sosial',
            'points' => 'required|integer|min:1|max:200',
        ]);

        $vitaminType->update($request->only(['name', 'category', 'points']));

        return redirect()->route('admin.vitamin-types.index')
            ->with('success', 'Kategori vitamin berhasil diperbarui!');
    }

    public function destroy(VitaminType $vitaminType)
    {
        // Check if there are related behavior records
        $recordCount = BehaviorRecord::where('vitamin_type_id', $vitaminType->id)->count();
        
        if ($recordCount > 0) {
            return redirect()->route('admin.vitamin-types.index')
                ->with('error', "Tidak dapat menghapus! Terdapat {$recordCount} catatan siswa yang menggunakan kategori vitamin ini.");
        }

        $vitaminType->delete();

        return redirect()->route('admin.vitamin-types.index')
            ->with('success', 'Kategori vitamin berhasil dihapus!');
    }
}
