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
    <div class="stats-grid" style="margin-bottom: 2.5rem; gap: 1.5rem;">
        @php
            $totalGuru = App\Models\User::where('role', 'guru')->count();
            $approvedGuru = App\Models\User::where('role', 'guru')->where('is_approved', true)->count();
        @endphp
        <div class="card stats-card" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="users" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="users" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ $totalGuru }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Total Personel</div>
        </div>
        <div class="card stats-card" style="background: linear-gradient(135deg, #059669, #065f46); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="user-check" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="user-check" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ $approvedGuru }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Guru Aktif</div>
        </div>
        <div class="card stats-card" style="background: linear-gradient(135deg, #ea580c, #9a3412); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="clock" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="clock" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ $pendingCount }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Menunggu Approval</div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div class="filter-bar">
            <div class="filter-search">
                <i data-lucide="search" class="filter-search-icon" style="width: 16px; height: 16px;"></i>
                <input type="text" name="search" class="filter-input" style="border: none; background: transparent;" placeholder="Cari nama atau email guru..." value="{{ request('search') }}">
            </div>
            <select name="status" class="filter-select" style="border: none; background-color: transparent;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aktif</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; border-radius: 12px;">
                <i data-lucide="filter" style="width: 14px; height: 14px;"></i> <span>Filter</span>
            </button>
        </div>
    </form>

    {{-- Pending Approval Section --}}
    @php $pendingUsers = $users->filter(fn($u) => !$u->is_approved); @endphp
    @if($pendingUsers->count() > 0)
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 2rem; border: none; border-radius: var(--radius-lg); box-shadow: 0 10px 30px rgba(234, 88, 12, 0.15);">
        <div style="padding: 1.25rem 1.5rem; background: linear-gradient(135deg, #fff7ed, #ffedd5); border-bottom: 1px solid rgba(234, 88, 12, 0.1); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h3 style="font-size: 0.9375rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem; color: #ea580c;">
                <i data-lucide="alert-circle" style="width: 18px; height: 18px;"></i> Butuh Persetujuan
            </h3>
            <span style="background: #ea580c; color: white; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.7rem; font-weight: 800;">{{ $pendingUsers->count() }} Guru Baru</span>
        </div>
        @foreach($pendingUsers as $user)
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(234, 88, 12, 0.05); display: flex; justify-content: space-between; align-items: center; background: white; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 44px; height: 44px; border-radius: 14px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ea580c&color=fff&bold=true&size=88'); background-size: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(234, 88, 12, 0.1);"></div>
                <div>
                    <div style="font-weight: 800; font-size: 0.875rem; color: #9a3412;">{{ $user->name }}</div>
                    <div style="font-size: 0.75rem; color: #c2410c; margin-top: 2px; opacity: 0.8;">{{ $user->email }} · <span style="font-weight: 700;">{{ $user->created_at->diffForHumans() }}</span></div>
                </div>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn" style="padding: 0.5rem 1.25rem; background: #059669; color: white; font-size: 0.75rem; border-radius: 12px; font-weight: 800; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);">
                        <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('admin.users.reject', $user) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn" style="padding: 0.5rem 1.25rem; background: #fee2e2; color: #dc2626; font-size: 0.75rem; border-radius: 12px; font-weight: 800;" onclick="return confirm('Tolak dan hapus akun ini?')">
                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Tolak
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Active Users Table --}}
    <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-light); background: white;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; background: linear-gradient(to right, #f8fafc, #ffffff); flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem; color: var(--primary-dark);">
                    <i data-lucide="users" style="width: 20px; height: 20px; color: var(--primary);"></i> Daftar Guru & Staff
                </h3>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">Total data terdaftar di sistem</p>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary);">{{ $users->total() }} Personel</span>
                <div style="width: 1px; height: 16px; background: var(--border);"></div>
                <span class="status-badge" style="background: var(--primary-light); color: var(--primary); font-size: 0.65rem;">Hal {{ $users->currentPage() }}</span>
            </div>
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
                    <tr style="animation: fadeInUp 0.4s ease-out backwards; animation-delay: {{ $loop->index * 0.05 }}s;">
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="position: relative;">
                                    <div style="width: 42px; height: 42px; border-radius: 14px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $user->is_approved ? '2B5EA7' : 'FF9100' }}&color=fff&bold=true&size=84'); background-size: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);"></div>
                                    @if($user->is_approved)
                                    <div style="position: absolute; bottom: -2px; right: -2px; width: 14px; height: 14px; background: var(--success); border: 2px solid #fff; border-radius: 50%;"></div>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight: 800; font-size: 0.875rem; color: var(--primary-dark);">{{ $user->name }}</div>
                                    <div style="display: flex; align-items: center; gap: 0.4rem; margin-top: 2px;">
                                        <span style="font-size: 0.65rem; background: var(--bg); color: var(--text-muted); padding: 1px 6px; border-radius: 4px; font-weight: 700;">ID: {{ str_pad($user->staff_id ?? $user->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i data-lucide="mail" style="width: 12px; height: 12px; color: var(--text-muted);"></i>
                                {{ $user->email }}
                            </div>
                        </td>
                        <td>
                            @if($user->is_approved)
                                <span class="status-badge" style="background: #ecfdf5; color: #059669; border: 1px solid rgba(16,185,129,0.1); font-weight: 800;">Aktif</span>
                            @else
                                <span class="status-badge" style="background: #fff7ed; color: #ea580c; border: 1px solid rgba(249,115,22,0.1); font-weight: 800;">Pending</span>
                            @endif
                        </td>
                        <td style="font-size: 0.8125rem; color: var(--text-muted); font-weight: 500;">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                        <td>
                            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--primary-light); padding: 0.35rem 0.75rem; border-radius: 10px;">
                                <i data-lucide="database" style="width: 12px; height: 12px; color: var(--primary);"></i>
                                <span style="font-weight: 800; font-size: 0.8125rem; color: var(--primary);">{{ $recordCount }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <button onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}')" style="background: var(--primary-light); color: var(--primary); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--primary)';this.style.color='white'" onmouseout="this.style.background='var(--primary-light)';this.style.color='var(--primary)'">
                                    <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun guru ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: var(--danger-light); color: var(--danger); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--danger)';this.style.color='white'" onmouseout="this.style.background='var(--danger-light)';this.style.color='var(--danger)'">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 5rem 2rem;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 1.5rem; animation: fadeInUp 0.5s ease-out;">
                                <div style="width: 80px; height: 80px; background: var(--bg); border-radius: 30px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                    <i data-lucide="search-x" style="width: 40px; height: 40px; opacity: 0.3;"></i>
                                </div>
                                <div>
                                    <h4 style="font-weight: 800; color: var(--primary-dark); margin-bottom: 0.5rem;">Tidak Menemukan Hasil</h4>
                                    <p style="font-size: 0.875rem; color: var(--text-muted); max-width: 300px; margin: 0 auto;">Maaf, kami tidak menemukan guru dengan kriteria pencarian "{{ request('search') }}".</p>
                                </div>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="padding: 0.6rem 1.5rem; border-radius: 12px;">Reset Pencarian</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div style="padding: 0.75rem; border-top: 1px solid var(--border);">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- Add Modal --}}
    <div id="addModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); animation: fadeIn 0.3s ease-out; padding: 1rem;" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 100%; max-width: 480px; padding: 0; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem;">
                        <i data-lucide="user-plus" style="width: 20px; height: 20px;"></i>
                        Tambah Guru Baru
                    </h3>
                    <p style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin-top: 2px;">Daftarkan personel baru ke dalam sistem</p>
                </div>
                <button onclick="document.getElementById('addModal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; padding: 6px; border-radius: 10px; display: flex; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" style="padding: 2rem;">
                @csrf
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">Nama Lengkap</label>
                    <div style="position: relative;">
                        <i data-lucide="user" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                        <input type="text" name="name" class="input" style="padding-left: 2.75rem;" placeholder="Contoh: Budi Santoso, S.Pd." required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">Alamat Email</label>
                    <div style="position: relative;">
                        <i data-lucide="mail" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                        <input type="email" name="email" class="input" style="padding-left: 2.75rem;" placeholder="email@sipintar.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">Password Akun</label>
                    <div style="position: relative;">
                        <i data-lucide="lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                        <input type="password" name="password" class="input" style="padding-left: 2.75rem;" placeholder="Minimal 8 karakter" required minlength="8">
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; height: 48px; border-radius: 14px; font-weight: 800; box-shadow: 0 10px 15px -3px rgba(43, 94, 167, 0.3);">
                        <i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); animation: fadeIn 0.3s ease-out; padding: 1rem;" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 100%; max-width: 480px; padding: 0; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--secondary), #d97706); color: white; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem;">
                        <i data-lucide="pencil" style="width: 20px; height: 20px;"></i>
                        Edit Data Guru
                    </h3>
                    <p style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin-top: 2px;">Perbarui informasi akun personel</p>
                </div>
                <button onclick="document.getElementById('editModal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; padding: 6px; border-radius: 10px; display: flex; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            <form id="editForm" method="POST" style="padding: 2rem;">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: #9a3412;">Nama Lengkap</label>
                    <div style="position: relative;">
                        <i data-lucide="user" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                        <input type="text" name="name" id="editName" class="input" style="padding-left: 2.75rem;" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: #9a3412;">Alamat Email</label>
                    <div style="position: relative;">
                        <i data-lucide="mail" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                        <input type="email" name="email" id="editEmail" class="input" style="padding-left: 2.75rem;" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: #9a3412;">Password Baru <span style="font-weight: 400; opacity: 0.6;">(opsional)</span></label>
                    <div style="position: relative;">
                        <i data-lucide="lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                        <input type="password" name="password" class="input" style="padding-left: 2.75rem;" placeholder="Biarkan kosong jika tidak diubah" minlength="8">
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn" style="flex: 1; height: 48px; border-radius: 14px; font-weight: 800; background: var(--secondary); color: white; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);">
                        <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i> Perbarui Data
                    </button>
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
        // Live Search Auto-submit with debounce
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                
                // Visual indicator that something is happening
                const searchIcon = document.querySelector('.filter-search-icon');
                if (searchIcon) searchIcon.style.opacity = '0.4';

                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 600);
            });

            // Restore focus and cursor position after page reload
            if (searchInput.value) {
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        }

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
