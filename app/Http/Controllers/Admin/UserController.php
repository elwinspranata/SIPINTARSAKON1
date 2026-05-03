<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BehaviorRecord;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $pendingCount = User::where('role', 'guru')->where('is_approved', false)->count();

        return view('admin.users', compact('users', 'pendingCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
            'is_approved' => true, // Admin-created accounts are auto-approved
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun guru berhasil dibuat!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data guru berhasil diperbarui!');
    }

    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->name} berhasil disetujui!");
    }

    public function reject(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun berhasil ditolak dan dihapus.');
    }

    public function destroy(User $user)
    {
        // Check if there are related records
        $behaviorCount = BehaviorRecord::where('user_id', $user->id)->count();
        $healthCount = HealthRecord::where('user_id', $user->id)->count();
        $totalRecords = $behaviorCount + $healthCount;

        if ($totalRecords > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', "Tidak dapat menghapus! Guru ini memiliki {$totalRecords} catatan terkait.");
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun guru berhasil dihapus!');
    }
}
