<x-app-layout>
    @section('header_title', 'Kelola Siswa')
    @section('header_subtitle', 'Manajemen data siswa, monitoring poin perilaku, dan riwayat kedisiplinan.')
    @section('header_actions')
        <button onclick="document.getElementById('addStudentModal').style.display='flex'" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">
            <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
            <span>Tambah Siswa</span>
        </button>
    @endsection

    {{-- Stats Cards --}}
    <div class="stats-grid" style="margin-bottom: 2.5rem; gap: 1.5rem;">
        @php
            $totalStudents = App\Models\Student::count();
            $maleStudents = App\Models\Student::where('gender', 'L')->count();
            $femaleStudents = App\Models\Student::where('gender', 'P')->count();
        @endphp
        <div class="card stats-card" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="users" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="users" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ $totalStudents }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Total Siswa</div>
        </div>
        <div class="card stats-card" style="background: linear-gradient(135deg, #0284c7, #0369a1); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="user" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="user" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ $maleStudents }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Laki-laki</div>
        </div>
        <div class="card stats-card" style="background: linear-gradient(135deg, #db2777, #9d174d); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="user" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="user" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ $femaleStudents }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Perempuan</div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('students.index') }}">
        <div class="filter-bar">
            <div class="filter-search">
                <i data-lucide="search" class="filter-search-icon" style="width: 16px; height: 16px;"></i>
                <input type="text" name="search" class="filter-input" style="border: none; background: transparent;" placeholder="Cari nama atau NISN..." value="{{ request('search') }}">
            </div>
            <select name="class_id" class="filter-select" style="border: none; background-color: transparent;" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; border-radius: 12px;">
                <i data-lucide="filter" style="width: 14px; height: 14px;"></i> <span>Filter</span>
            </button>
        </div>
    </form>

    {{-- Bulk Action Bar --}}
    <form id="bulkDeleteForm" action="{{ route('students.bulkDestroy') }}" method="POST">
        @csrf
        @method('DELETE')
        <div id="bulkActionBar" style="display: none; background: linear-gradient(135deg, #fee2e2, #fecaca); padding: 1rem 1.5rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; align-items: center; justify-content: space-between; border: 1px solid rgba(220, 38, 38, 0.1); animation: modalIn 0.3s ease-out; box-shadow: 0 10px 25px rgba(220, 38, 38, 0.1);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="background: #dc2626; color: white; width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.875rem;" id="selectedCount">0</div>
                <div style="font-size: 0.875rem; font-weight: 700; color: #991b1b;">Siswa dipilih untuk tindakan massal</div>
            </div>
            <button type="submit" class="btn" style="background: #dc2626; color: white; padding: 0.6rem 1.5rem; font-size: 0.8125rem; border-radius: 12px; font-weight: 800; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);" onclick="return confirm('Hapus permanen semua siswa yang dipilih?')">
                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Hapus Terpilih
            </button>
        </div>

        <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-light); background: white;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; background: linear-gradient(to right, #f8fafc, #ffffff); flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem; color: var(--primary-dark);">
                        <i data-lucide="user-square" style="width: 20px; height: 20px; color: var(--primary);"></i> Daftar Siswa
                    </h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">Data akademik dan kedisiplinan siswa</p>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary);">{{ $students->total() }} Siswa</span>
                    <div style="width: 1px; height: 16px; background: var(--border);"></div>
                    <span class="status-badge" style="background: var(--primary-light); color: var(--primary); font-size: 0.65rem;">Hal {{ $students->currentPage() }}</span>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" style="accent-color: var(--primary); cursor: pointer; width: 16px; height: 16px;">
                            </th>
                            <th>Siswa</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Poin Perilaku</th>
                            <th>Terdaftar</th>
                            <th>Status</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        @php
                            $points = $student->net_points;
                            $status = $student->point_status;
                            $sc = $status['sp'] !== 'Normal' ? 'danger' : 'success';
                            $st = $status['label'];
                            $pc = $status['color'];
                            $bgc = $status['bg'];
                        @endphp
                        <tr style="animation: fadeInUp 0.4s ease-out backwards; animation-delay: {{ $loop->index * 0.05 }}s;">
                            <td style="text-align: center;">
                                <input type="checkbox" name="ids[]" value="{{ $student->id }}" class="student-checkbox" onchange="updateBulkActionUI()" style="accent-color: var(--primary); cursor: pointer; width: 16px; height: 16px;">
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="position: relative;">
                                        <div style="width: 42px; height: 42px; border-radius: 14px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random&color=fff&bold=true&size=84'); background-size: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);"></div>
                                        <div style="position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px; background: {{ $student->gender == 'L' ? '#0ea5e9' : '#db2777' }}; border: 2px solid #fff; border-radius: 50%;"></div>
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.875rem; color: var(--primary-dark);">{{ $student->name }}</div>
                                        <div style="font-size: 0.65rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-family: 'JetBrains Mono', 'SF Mono', monospace; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary);">{{ $student->nisn ?? '---' }}</td>
                            <td>
                                <span style="background: var(--bg); color: var(--text-secondary); padding: 0.35rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 800; border: 1px solid var(--border-light);">
                                    {{ $student->schoolClass->name ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <span style="font-weight: 900; font-size: 0.9375rem; color: {{ $pc }}; width: 28px;">{{ $points > 0 ? '+' : '' }}{{ $points }}</span>
                                    <div style="flex: 1; height: 6px; background: #f1f5f9; border-radius: 10px; min-width: 80px; overflow: hidden; border: 1px solid var(--border-light);">
                                        <div style="width: {{ min((max(0, -$points)/150)*100, 100) }}%; height: 100%; background: {{ $pc }}; border-radius: 10px; transition: width 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);"></div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">{{ $student->created_at->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="status-badge" style="background: {{ $bgc }}; color: {{ $pc }}; border: 1px solid rgba(0,0,0,0.02); font-weight: 800; font-size: 0.65rem;">{{ $st }}</span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.35rem;">
                                    <a href="{{ route('students.show', $student) }}" class="btn" style="background: var(--primary-light); color: var(--primary); width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--primary)';this.style.color='white'" onmouseout="this.style.background='var(--primary-light)';this.style.color='var(--primary)'">
                                        <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                    </a>
                                    <button type="button" onclick="openEditStudentModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->nisn }}', '{{ $student->class_id }}', '{{ $student->gender }}')" class="btn" style="background: #fef3c7; color: #d97706; width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#d97706';this.style.color='white'" onmouseout="this.style.background='#fef3c7';this.style.color='#d97706'">
                                        <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                    </button>
                                    <button type="button" onclick="confirmDelete({{ $student->id }}, '{{ addslashes($student->name) }}')" class="btn" style="background: #fee2e2; color: #dc2626; width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#dc2626';this.style.color='white'" onmouseout="this.style.background='#fee2e2';this.style.color='#dc2626'">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 6rem 2rem;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 1.5rem; animation: fadeInUp 0.5s ease-out;">
                                    <div style="width: 80px; height: 80px; background: var(--bg); border-radius: 30px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                        <i data-lucide="search-x" style="width: 40px; height: 40px; opacity: 0.3;"></i>
                                    </div>
                                    <div>
                                        <h4 style="font-weight: 800; color: var(--primary-dark); margin-bottom: 0.5rem;">Data Siswa Tidak Ditemukan</h4>
                                        <p style="font-size: 0.875rem; color: var(--text-muted); max-width: 320px; margin: 0 auto;">Kami tidak dapat menemukan siswa dengan nama atau NISN "{{ request('search') }}". Coba gunakan kata kunci lain.</p>
                                    </div>
                                    <a href="{{ route('students.index') }}" class="btn btn-outline" style="padding: 0.6rem 1.5rem; border-radius: 12px;">Reset Filter</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
            <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border-light); background: #fcfdfe;">
                {{ $students->links('vendor.pagination.custom') }}
            </div>
            @endif
        </div>
    </form>

    {{-- Add Student Modal --}}
    <div id="addStudentModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); animation: fadeIn 0.3s ease-out; padding: 1rem;" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 100%; max-width: 520px; padding: 0; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem;">
                        <i data-lucide="user-plus" style="width: 20px; height: 20px;"></i>
                        Tambah Siswa Baru
                    </h3>
                    <p style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin-top: 2px;">Daftarkan siswa baru ke dalam sistem akademik</p>
                </div>
                <button onclick="document.getElementById('addStudentModal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; padding: 6px; border-radius: 10px; display: flex; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            <form action="{{ route('students.store') }}" method="POST" style="padding: 2rem;">
                @csrf
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">Nama Lengkap</label>
                    <input type="text" name="name" class="input" placeholder="Masukkan nama lengkap siswa" required>
                </div>
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">NISN</label>
                    <input type="text" name="nisn" class="input" placeholder="Nomor Induk Siswa Nasional">
                </div>
                <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div class="form-group">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">Kelas</label>
                        <select name="class_id" class="select" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">Jenis Kelamin</label>
                        <select name="gender" class="select" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; height: 48px; border-radius: 14px; font-weight: 800; box-shadow: 0 10px 15px -3px rgba(43, 94, 167, 0.3);">
                        <i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Student Modal --}}
    @include('students.modals.edit')

    {{-- Hidden Delete Form for single delete --}}
    <form id="singleDeleteForm" method="POST" style="display: none;">
        @csrf @method('DELETE')
    </form>

    @push('scripts')
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        tbody tr:hover { 
            background: #f8fafc; 
            transform: translateX(4px);
            box-shadow: inset 4px 0 0 var(--primary);
        }
        tbody tr {
            transition: all 0.25s var(--ease);
        }
    </style>
    <script>
        // Live Search Auto-submit with debounce
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchIcon = document.querySelector('.filter-search-icon');
                if (searchIcon) searchIcon.style.opacity = '0.4';
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 600);
            });

            if (searchInput.value) {
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        }



        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
            updateBulkActionUI();
        }

        function updateBulkActionUI() {
            const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
            const actionBar = document.getElementById('bulkActionBar');
            const selectedCountSpan = document.getElementById('selectedCount');
            
            if (checkedCount > 0) {
                actionBar.style.display = 'flex';
                selectedCountSpan.textContent = checkedCount;
            } else {
                actionBar.style.display = 'none';
                document.getElementById('selectAllCheckbox').checked = false;
            }
        }

        function confirmDelete(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus data siswa "${name}"? Semua catatan perilaku terkait juga akan dihapus.`)) {
                const form = document.getElementById('singleDeleteForm');
                form.action = `/students/${id}`;
                form.submit();
            }
        }
    </script>
    @endpush
</x-app-layout>


