<x-app-layout>
    @section('header_title', 'Kelola Kelas')
    @section('header_subtitle', 'Manajemen struktur kelas, monitoring jumlah siswa, dan pindah massal.')

    @section('header_actions')
    <button onclick="openTransferModal()" class="btn btn-outline" style="padding: 0.6rem 1.25rem; border-radius: 12px; border-color: var(--secondary); color: var(--secondary);">
        <i data-lucide="arrow-right-left" style="width: 18px; height: 18px;"></i>
        <span>Pindah Massal</span>
    </button>
    <button onclick="openAddModal()" class="btn btn-primary" style="padding: 0.6rem 1.25rem; border-radius: 12px;">
        <i data-lucide="plus-circle" style="width: 18px; height: 18px;"></i>
        <span>Tambah Kelas</span>
    </button>
    @endsection

    {{-- Error Message --}}
    @if(session('error'))
    <div style="background: #fef2f2; color: #ef4444; padding: 1rem; border-radius: 14px; margin-bottom: 2rem; font-weight: 700; font-size: 0.875rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid rgba(239, 68, 68, 0.1); animation: fadeInUp 0.4s ease-out;">
        <i data-lucide="alert-triangle" style="width: 18px; height: 18px;"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 2.5rem; gap: 1.5rem;">
        @php
            $tingkatList = ['X', 'XI', 'XII'];
            $tingkatColors = ['X' => ['#2563eb', '#1d4ed8'], 'XI' => ['#d97706', '#b45309'], 'XII' => ['#059669', '#047857']];
            $tingkatIcons = ['X' => 'book-open', 'XI' => 'book-marked', 'XII' => 'graduation-cap'];
        @endphp
        @foreach($tingkatList as $t)
        <div class="card stats-card" style="background: linear-gradient(135deg, {{ $tingkatColors[$t][0] }}, {{ $tingkatColors[$t][1] }}); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="{{ $tingkatIcons[$t] }}" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="{{ $tingkatIcons[$t] }}" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ isset($classes[$t]) ? $classes[$t]->count() : 0 }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Kelas {{ $t }}</div>
        </div>
        @endforeach
    </div>

    {{-- Main Content: Cards per Tingkat --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.5rem;">
        @foreach($tingkatList as $tingkat)
        @php
            $color = $tingkatColors[$tingkat][0];
            $tingkatClasses = $classes[$tingkat] ?? collect();
        @endphp
        <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-light); animation: fadeInUp 0.5s ease-out; animation-delay: {{ $loop->index * 0.1 }}s;">
            <div style="padding: 1.25rem 1.5rem; background: linear-gradient(to right, #f8fafc, #ffffff); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--primary-dark); display: flex; align-items: center; gap: 0.6rem;">
                    <i data-lucide="{{ $tingkatIcons[$tingkat] }}" style="width: 20px; height: 20px; color: {{ $color }};"></i> Kelas {{ $tingkat }}
                </h3>
                <span class="status-badge" style="background: var(--bg); color: var(--text-secondary); font-weight: 800;">{{ $tingkatClasses->count() }} Kelas</span>
            </div>
            <div style="display: flex; flex-direction: column;">
                @forelse($tingkatClasses as $kelas)
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; transition: all 0.25s; opacity: {{ $kelas->is_active ? '1' : '0.6' }};" onmouseover="this.style.background='#f8fafc'; this.style.transform='translateX(4px)'" onmouseout="this.style.background='transparent'; this.style.transform='translateX(0)'">
                    <div style="flex: 1; min-width: 0; padding-right: 1rem;">
                        <div style="font-weight: 800; font-size: 0.9375rem; color: var(--primary-dark); display: flex; align-items: center; gap: 0.6rem;">
                            {{ $kelas->name }}
                            @if(!$kelas->is_active)
                                <span style="font-size: 0.625rem; font-weight: 800; background: #fee2e2; color: #ef4444; padding: 0.2rem 0.5rem; border-radius: 6px;">NONAKTIF</span>
                            @endif
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">Tingkat {{ $kelas->tingkat }}</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="text-align: right; min-width: 60px;">
                            <div style="font-size: 1.25rem; font-weight: 900; color: {{ $color }}; line-height: 1;">{{ $kelas->students_count }}</div>
                            <div style="font-size: 0.625rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Siswa</div>
                        </div>
                        <div style="display: flex; gap: 0.35rem;">
                            <form action="{{ route('classes.toggleStatus', $kelas) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn" style="background: {{ $kelas->is_active ? '#f1f5f9' : '#ecfdf5' }}; color: {{ $kelas->is_active ? '#64748b' : '#10b981' }}; width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='{{ $kelas->is_active ? '#64748b' : '#10b981' }}';this.style.color='white'" onmouseout="this.style.background='{{ $kelas->is_active ? '#f1f5f9' : '#ecfdf5' }}';this.style.color='{{ $kelas->is_active ? '#64748b' : '#10b981' }}'" title="{{ $kelas->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i data-lucide="{{ $kelas->is_active ? 'power-off' : 'power' }}" style="width: 14px; height: 14px;"></i>
                                </button>
                            </form>
                            <button onclick="openEditModal({{ $kelas->id }}, '{{ addslashes($kelas->name) }}', '{{ $kelas->tingkat }}')" class="btn" style="background: #fef3c7; color: #d97706; width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#d97706';this.style.color='white'" onmouseout="this.style.background='#fef3c7';this.style.color='#d97706'" title="Edit">
                                <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                            </button>
                            <form action="{{ route('classes.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Hapus kelas {{ $kelas->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn" style="background: #fee2e2; color: #ef4444; width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#ef4444';this.style.color='white'" onmouseout="this.style.background='#fee2e2';this.style.color='#ef4444'" title="Hapus">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div style="padding: 3rem 2rem; text-align: center; color: var(--text-muted);">
                    <i data-lucide="inbox" style="width: 40px; height: 40px; opacity: 0.1; margin-bottom: 1rem;"></i>
                    <p style="font-size: 0.8125rem; font-weight: 600;">Belum ada kelas.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>

    {{-- Add Modal --}}
    <div id="addModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); animation: fadeIn 0.3s ease-out;" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 480px; max-width: 90vw; padding: 0; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.125rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="plus-circle" style="width: 20px; height: 20px;"></i>
                    Tambah Kelas Baru
                </h3>
                <button onclick="document.getElementById('addModal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; padding: 6px; border-radius: 10px; display: flex; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            <form action="{{ route('classes.store') }}" method="POST" style="padding: 2rem;">
                @csrf
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">Tingkat</label>
                    <select name="tingkat" id="addTingkat" class="select" required onchange="updatePreview()">
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">Nama Kelas</label>
                    <input type="text" name="name" id="addName" class="input" placeholder="Contoh: X E.1, XI F.2" required oninput="updatePreview()">
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; height: 48px; border-radius: 14px; font-weight: 800;">
                        <i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); animation: fadeIn 0.3s ease-out;" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 480px; max-width: 90vw; padding: 0; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="padding: 1.5rem 2rem; background: linear-gradient(135deg, #d97706, #92400e); color: white; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.125rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="pencil" style="width: 20px; height: 20px;"></i>
                    Edit Data Kelas
                </h3>
                <button onclick="document.getElementById('editModal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; padding: 6px; border-radius: 10px; display: flex; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            <form id="editForm" method="POST" style="padding: 2rem;">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: #92400e;">Tingkat</label>
                    <select name="tingkat" id="editTingkat" class="select" required>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: #92400e;">Nama Kelas</label>
                    <input type="text" name="name" id="editName" class="input" required>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn" style="flex: 1; height: 48px; border-radius: 14px; font-weight: 800; background: #d97706; color: white;">
                        <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i> Perbarui Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk Transfer Modal --}}
    <div id="transferModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); animation: fadeIn 0.3s ease-out;" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 560px; max-width: 90vw; padding: 0; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--secondary), #1d4ed8); color: white; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.125rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="arrow-right-left" style="width: 20px; height: 20px;"></i>
                    Pindah Siswa Massal
                </h3>
                <button onclick="document.getElementById('transferModal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; padding: 6px; border-radius: 10px; display: flex; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            <form action="{{ route('classes.bulkTransfer') }}" method="POST" id="transferForm" style="padding: 2rem;">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 1rem; align-items: end; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">Kelas Asal</label>
                        <select name="source_class_id" id="sourceClass" class="select" required onchange="loadStudents()">
                            <option value="">-- Pilih --</option>
                            @php $sourceClasses = App\Models\SchoolClass::orderBy('tingkat')->orderBy('name')->get(); @endphp
                            @foreach($sourceClasses as $kls)
                                <option value="{{ $kls->id }}">{{ $kls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="padding-bottom: 0.75rem; color: var(--text-muted);">
                        <i data-lucide="arrow-right" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">Kelas Tujuan</label>
                        <select name="target_class_id" id="targetClass" class="select" required>
                            <option value="">-- Pilih --</option>
                            @php $targetClasses = App\Models\SchoolClass::where('is_active', true)->orderBy('tingkat')->orderBy('name')->get(); @endphp
                            @foreach($targetClasses as $kls)
                                <option value="{{ $kls->id }}">{{ $kls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="studentListArea" style="display: none; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <label class="label" style="margin-bottom: 0; font-weight: 700;">Pilih Siswa</label>
                        <label style="font-size: 0.8125rem; font-weight: 800; color: var(--primary); cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" style="accent-color: var(--primary); width: 16px; height: 16px;">
                            Pilih Semua (<span id="studentCount">0</span>)
                        </label>
                    </div>
                    <div id="studentList" style="max-height: 240px; overflow-y: auto; border: 1px solid var(--border-light); border-radius: 12px; background: #f8fafc; padding: 0.5rem;">
                    </div>
                    <div id="selectedInfo" style="margin-top: 0.75rem; font-size: 0.8125rem; font-weight: 800; color: var(--primary); display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="info" style="width: 14px; height: 14px;"></i> <span id="selectedCount">0</span> siswa dipilih
                    </div>
                </div>

                <div id="loadingState" style="display: none; text-align: center; padding: 2rem;">
                    <div style="display: inline-block; width: 24px; height: 24px; border: 3px solid var(--border); border-top-color: var(--primary); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                    <p style="margin-top: 0.75rem; font-size: 0.875rem; color: var(--text-muted); font-weight: 600;">Memuat data siswa...</p>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" id="transferBtn" class="btn btn-primary" style="flex: 1; height: 48px; border-radius: 14px; font-weight: 800;" disabled>
                        <i data-lucide="arrow-right-left" style="width: 18px; height: 18px;"></i> Pindahkan Siswa
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
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .student-checkbox-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: background 0.2s;
            cursor: pointer;
        }
        .student-checkbox-row:hover { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .student-checkbox-row input[type="checkbox"] { accent-color: var(--primary); width: 16px; height: 16px; flex-shrink: 0; }
    </style>
    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
            lucide.createIcons();
        }

        function openEditModal(id, name, tingkat) {
            document.getElementById('editForm').action = '/classes/' + id;
            document.getElementById('editName').value = name;
            document.getElementById('editTingkat').value = tingkat;
            document.getElementById('editModal').style.display = 'flex';
            lucide.createIcons();
        }

        function openTransferModal() {
            document.getElementById('transferModal').style.display = 'flex';
            lucide.createIcons();
        }

        function loadStudents() {
            var classId = document.getElementById('sourceClass').value;
            var listArea = document.getElementById('studentListArea');
            var studentList = document.getElementById('studentList');
            var loading = document.getElementById('loadingState');

            listArea.style.display = 'none';
            studentList.innerHTML = '';
            if (!classId) return;

            loading.style.display = 'block';

            fetch('/classes/' + classId + '/students')
                .then(res => res.json())
                .then(students => {
                    loading.style.display = 'none';
                    if (students.length === 0) return;

                    document.getElementById('studentCount').textContent = students.length;
                    var html = '';
                    students.forEach(s => {
                        html += `<label class="student-checkbox-row">
                            <input type="checkbox" name="student_ids[]" value="${s.id}" onchange="updateSelectedCount()">
                            <div>
                                <div style="font-weight: 800; font-size: 0.8125rem; color: var(--primary-dark);">${s.name}</div>
                                <div style="font-size: 0.6875rem; color: var(--text-muted); font-weight: 600;">NISN: ${s.nisn || '-'}</div>
                            </div>
                        </label>`;
                    });
                    studentList.innerHTML = html;
                    listArea.style.display = 'block';
                    lucide.createIcons();
                });
        }

        function toggleSelectAll() {
            var checked = document.getElementById('selectAll').checked;
            var checkboxes = document.querySelectorAll('#studentList input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            var count = document.querySelectorAll('#studentList input[type="checkbox"]:checked').length;
            document.getElementById('selectedCount').textContent = count;
            document.getElementById('transferBtn').disabled = count === 0;
        }
    </script>
    @endpush
</x-app-layout>

