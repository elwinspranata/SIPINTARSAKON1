<x-app-layout>
    @section('header_title', 'Kelola Guru')
    @section('header_subtitle', 'Manajemen akun guru, persetujuan pendaftaran, dan monitoring aktivitas.')

    @section('header_actions')
    <button onclick="document.getElementById('addModal').style.display='flex'" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">
        <i data-lucide="user-plus" size="18"></i>
        <span>Tambah Guru</span>
    </button>
    @endsection

    {{-- Error Message --}}
    @if(session('error'))
    <div style="background: var(--danger-light); color: var(--danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 700; font-size: 0.875rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid rgba(239,68,68,0.1); animation: fadeInUp 0.4s ease-out;">
        <i data-lucide="alert-triangle" size="18"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Stats --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem;">
        @php
            $totalGuru = App\Models\User::where('role', 'guru')->count();
            $approvedGuru = App\Models\User::where('role', 'guru')->where('is_approved', true)->count();
        @endphp
        <div class="card stats-card" style="border-top: 3px solid var(--primary);">
            <div class="stats-icon-wrapper" style="background: var(--primary-light); color: var(--primary);">
                <i data-lucide="users" size="22"></i>
            </div>
            <div class="stats-value">{{ $totalGuru }}</div>
            <div class="stats-label">Total Guru</div>
        </div>
        <div class="card stats-card" style="border-top: 3px solid var(--success);">
            <div class="stats-icon-wrapper" style="background: var(--success-light); color: var(--success);">
                <i data-lucide="user-check" size="22"></i>
            </div>
            <div class="stats-value">{{ $approvedGuru }}</div>
            <div class="stats-label">Aktif</div>
        </div>
        <div class="card stats-card" style="border-top: 3px solid {{ $pendingCount > 0 ? 'var(--secondary)' : 'var(--border)' }};">
            <div class="stats-icon-wrapper" style="background: {{ $pendingCount > 0 ? 'var(--secondary-light)' : 'var(--bg-hover)' }}; color: {{ $pendingCount > 0 ? 'var(--secondary)' : 'var(--text-muted)' }};">
                <i data-lucide="clock" size="22"></i>
            </div>
            <div class="stats-value" style="{{ $pendingCount > 0 ? 'color: var(--secondary);' : '' }}">{{ $pendingCount }}</div>
            <div class="stats-label">Menunggu Persetujuan</div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div class="filter-bar">
            <div class="filter-search">
                <i data-lucide="search" class="filter-search-icon" size="15"></i>
                <input type="text" name="search" class="filter-input" placeholder="Cari nama atau email guru..." value="{{ request('search') }}">
            </div>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aktif</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
            </select>
            <button type="submit" class="btn btn-outline filter-btn">
                <i data-lucide="search" size="14"></i> Cari
            </button>
        </div>
    </form>

    {{-- Pending Approval Section --}}
    @php $pendingUsers = $users->filter(fn($u) => !$u->is_approved); @endphp
    @if($pendingUsers->count() > 0)
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 1.5rem; border: 2px solid var(--secondary); border-radius: var(--radius-lg);">
        <div style="padding: 1.25rem; background: var(--secondary-light); border-bottom: 1px solid rgba(255,145,0,0.15); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 0.9375rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: var(--secondary);">
                <i data-lucide="clock" size="16"></i> Menunggu Persetujuan
            </h3>
            <span class="status-badge" style="background: var(--secondary); color: white;">{{ $pendingUsers->count() }} guru</span>
        </div>
        @foreach($pendingUsers as $user)
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 40px; height: 40px; border-radius: 12px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=FF9100&color=fff&bold=true&size=80'); background-size: cover;"></div>
                <div>
                    <div style="font-weight: 700; font-size: 0.875rem;">{{ $user->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->email }} · Mendaftar {{ $user->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn" style="padding: 0.4rem 1rem; background: var(--success); color: white; font-size: 0.75rem; border-radius: 8px;">
                        <i data-lucide="check" size="13"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('admin.users.reject', $user) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn" style="padding: 0.4rem 1rem; background: var(--danger-light); color: var(--danger); font-size: 0.75rem; border-radius: 8px;" onclick="return confirm('Tolak dan hapus akun ini?')">
                        <i data-lucide="x" size="13"></i> Tolak
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Active Users Table --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 0.9375rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="users" size="16" style="color: var(--primary);"></i> Daftar Guru
            </h3>
            <span class="status-badge" style="background: var(--primary-light); color: var(--primary);">{{ $users->total() }} guru</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th>Record</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    @php
                        $recordCount = App\Models\BehaviorRecord::where('user_id', $user->id)->count() + App\Models\HealthRecord::where('user_id', $user->id)->count();
                    @endphp
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 36px; height: 36px; border-radius: 10px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $user->is_approved ? '2B5EA7' : 'FF9100' }}&color=fff&bold=true&size=72'); background-size: cover; flex-shrink: 0;"></div>
                                <div style="font-weight: 600; font-size: 0.8125rem;">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->email }}</td>
                        <td>
                            @if($user->is_approved)
                                <span class="status-badge" style="background: var(--success-light); color: var(--success);">Aktif</span>
                            @else
                                <span class="status-badge" style="background: var(--secondary-light); color: var(--secondary);">Pending</span>
                            @endif
                        </td>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <span style="font-weight: 700; font-size: 0.8125rem; color: var(--primary);">{{ $recordCount }}</span>
                            <span style="font-size: 0.625rem; color: var(--text-muted); margin-left: 2px;">catatan</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <button onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}')" style="background: var(--primary-light); color: var(--primary); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--primary)';this.style.color='white'" onmouseout="this.style.background='var(--primary-light)';this.style.color='var(--primary)'">
                                    <i data-lucide="pencil" size="14"></i>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun guru ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: var(--danger-light); color: var(--danger); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--danger)';this.style.color='white'" onmouseout="this.style.background='var(--danger-light)';this.style.color='var(--danger)'">
                                        <i data-lucide="trash-2" size="14"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">Belum ada data guru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div style="padding: 0.75rem; border-top: 1px solid var(--border);">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- Add Modal --}}
    <div id="addModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 480px; max-width: 90vw; padding: 0; overflow: hidden; animation: modalIn 0.3s ease-out;">
            <div style="padding: 1.5rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="user-plus" size="18" style="color: var(--primary);"></i>
                    Tambah Akun Guru
                </h3>
                <button onclick="document.getElementById('addModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <i data-lucide="x" size="18"></i>
                </button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" style="padding: 1.5rem;">
                @csrf
                <div style="background: var(--success-light); border: 1px solid rgba(0,210,106,0.1); border-radius: var(--radius-sm); padding: 0.75rem; margin-bottom: 1.25rem; font-size: 0.75rem; color: var(--success); font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="check-circle" size="14"></i>
                    Akun yang dibuat oleh admin otomatis langsung aktif
                </div>
                <div class="form-group">
                    <label class="label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="input" placeholder="Nama guru" required>
                </div>
                <div class="form-group">
                    <label class="label">Email <span style="color: var(--danger);">*</span></label>
                    <input type="email" name="email" class="input" placeholder="email@sekolah.com" required>
                </div>
                <div class="form-group">
                    <label class="label">Password <span style="color: var(--danger);">*</span></label>
                    <input type="password" name="password" class="input" placeholder="Minimal 8 karakter" required minlength="8">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2;">
                        <i data-lucide="user-plus" size="15"></i> Buat Akun
                    </button>
                    <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn btn-outline" style="flex: 1;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 480px; max-width: 90vw; padding: 0; overflow: hidden; animation: modalIn 0.3s ease-out;">
            <div style="padding: 1.5rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="pencil" size="18" style="color: var(--secondary);"></i>
                    Edit Data Guru
                </h3>
                <button onclick="document.getElementById('editModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <i data-lucide="x" size="18"></i>
                </button>
            </div>
            <form id="editForm" method="POST" style="padding: 1.5rem;">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="editName" class="input" required>
                </div>
                <div class="form-group">
                    <label class="label">Email <span style="color: var(--danger);">*</span></label>
                    <input type="email" name="email" id="editEmail" class="input" required>
                </div>
                <div class="form-group">
                    <label class="label">Password Baru <span style="color: var(--text-muted); font-weight: 400;">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="input" placeholder="Minimal 8 karakter" minlength="8">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn" style="flex: 2; background: var(--secondary); color: white;">
                        <i data-lucide="check" size="15"></i> Perbarui
                    </button>
                    <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn btn-outline" style="flex: 1;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <style>
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        function openEditModal(id, name, email) {
            document.getElementById('editForm').action = '/admin/users/' + id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editModal').style.display = 'flex';
            lucide.createIcons();
        }
    </script>
    @endpush
</x-app-layout>
