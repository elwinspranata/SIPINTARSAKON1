<x-app-layout>
    @section('header_title', 'Kelola Kelas')
    @section('header_subtitle', 'Tambah, edit, dan hapus data kelas sekolah.')

    @section('header_actions')
    <button onclick="openTransferModal()" class="btn btn-outline" style="padding: 0.6rem 1.25rem; border-color: var(--secondary); color: var(--secondary);">
        <i data-lucide="arrow-right-left" size="18"></i>
        <span>Pindah Massal</span>
    </button>
    <button onclick="openAddModal()" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">
        <i data-lucide="plus-circle" size="18"></i>
        <span>Tambah Kelas</span>
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
            $tingkatList = ['X', 'XI', 'XII'];
            $tingkatColors = ['X' => 'var(--info)', 'XI' => 'var(--warning)', 'XII' => 'var(--success)'];
            $tingkatBg = ['X' => 'var(--info-light)', 'XI' => 'var(--warning-light)', 'XII' => 'var(--success-light)'];
            $tingkatIcons = ['X' => 'book-open', 'XI' => 'book-marked', 'XII' => 'graduation-cap'];
        @endphp
        @foreach($tingkatList as $t)
        <div class="card stats-card" style="border-top: 3px solid {{ $tingkatColors[$t] }};">
            <div class="stats-icon-wrapper" style="background: {{ $tingkatBg[$t] }}; color: {{ $tingkatColors[$t] }};">
                <i data-lucide="{{ $tingkatIcons[$t] }}" size="22"></i>
            </div>
            <div class="stats-value">{{ isset($classes[$t]) ? $classes[$t]->count() : 0 }}</div>
            <div class="stats-label">Kelas {{ $t }}</div>
        </div>
        @endforeach
    </div>

    {{-- Main Content: Cards per Tingkat --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.25rem;">
        @foreach($tingkatList as $tingkat)
        @php
            $color = $tingkatColors[$tingkat];
            $bgLight = $tingkatBg[$tingkat];
            $tingkatClasses = $classes[$tingkat] ?? collect();
        @endphp
        <div class="card" style="padding: 0; overflow: hidden; border-top: 4px solid {{ $color }};">
            <div style="padding: 1.25rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; color: {{ $color }};">Kelas {{ $tingkat }}</h3>
                <span class="status-badge" style="background: {{ $bgLight }}; color: {{ $color }};">{{ $tingkatClasses->count() }} Kelas</span>
            </div>
            <div style="display: flex; flex-direction: column;">
                @forelse($tingkatClasses as $kelas)
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; transition: background 200ms; opacity: {{ $kelas->is_active ? '1' : '0.6' }};" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                    <div style="flex: 1; min-width: 0; padding-right: 1rem;">
                        <div style="font-weight: 600; font-size: 0.875rem; color: var(--text); display: flex; align-items: center; gap: 0.5rem;">
                            {{ $kelas->name }}
                            @if(!$kelas->is_active)
                                <span style="font-size: 0.625rem; font-weight: 700; background: var(--danger-light); color: var(--danger); padding: 0.125rem 0.375rem; border-radius: 4px; line-height: 1;">NONAKTIF</span>
                            @endif
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="text-align: right;">
                            <div style="font-size: 1.125rem; font-weight: 800; color: {{ $color }};">{{ $kelas->students_count }}</div>
                            <div style="font-size: 0.625rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Siswa</div>
                        </div>
                        <div style="display: flex; gap: 0.25rem;">
                            <form action="{{ route('classes.toggleStatus', $kelas) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" style="background: {{ $kelas->is_active ? 'var(--border-light)' : 'var(--success-light)' }}; color: {{ $kelas->is_active ? 'var(--text-muted)' : 'var(--success)' }}; border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='{{ $kelas->is_active ? 'var(--text-secondary)' : 'var(--success)' }}';this.style.color='white'" onmouseout="this.style.background='{{ $kelas->is_active ? 'var(--border-light)' : 'var(--success-light)' }}';this.style.color='{{ $kelas->is_active ? 'var(--text-muted)' : 'var(--success)' }}'" title="{{ $kelas->is_active ? 'Nonaktifkan Kelas' : 'Aktifkan Kelas' }}">
                                    <i data-lucide="{{ $kelas->is_active ? 'power-off' : 'power' }}" size="14"></i>
                                </button>
                            </form>
                            <button onclick="openEditModal({{ $kelas->id }}, '{{ addslashes($kelas->name) }}', '{{ $kelas->tingkat }}')" style="background: var(--primary-light); color: var(--primary); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--primary)';this.style.color='white'" onmouseout="this.style.background='var(--primary-light)';this.style.color='var(--primary)'" title="Edit Kelas">
                                <i data-lucide="pencil" size="14"></i>
                            </button>
                            <form action="{{ route('classes.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas {{ $kelas->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: var(--danger-light); color: var(--danger); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--danger)';this.style.color='white'" onmouseout="this.style.background='var(--danger-light)';this.style.color='var(--danger)'" title="Hapus Kelas">
                                    <i data-lucide="trash-2" size="14"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div style="padding: 2rem; text-align: center; color: var(--text-muted); font-size: 0.8125rem;">
                    <i data-lucide="inbox" size="24" style="margin-bottom: 0.5rem; opacity: 0.4;"></i>
                    <p>Belum ada kelas untuk tingkat {{ $tingkat }}.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>

    {{-- Add Modal --}}
    <div id="addModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 480px; max-width: 90vw; padding: 0; overflow: hidden; animation: modalIn 0.3s ease-out;">
            <div style="padding: 1.5rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="plus-circle" size="18" style="color: var(--primary);"></i>
                    Tambah Kelas Baru
                </h3>
                <button onclick="document.getElementById('addModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <i data-lucide="x" size="18"></i>
                </button>
            </div>
            <form action="{{ route('classes.store') }}" method="POST" style="padding: 1.5rem;">
                @csrf
                <div class="form-group">
                    <label class="label">Tingkat <span style="color: var(--danger);">*</span></label>
                    <select name="tingkat" id="addTingkat" class="select" required onchange="updatePreview()">
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="label">Nama Kelas <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="addName" class="input" placeholder="Contoh: X E.1, XI F.2, XII IPA 1" required oninput="updatePreview()">
                    <p style="font-size: 0.6875rem; color: var(--text-muted); margin-top: 0.375rem;">Tulis nama lengkap kelas termasuk tingkatnya.</p>
                </div>
                <div id="previewBadge" style="display: none; background: var(--primary-light); border: 1px solid rgba(43,94,167,0.1); border-radius: var(--radius-sm); padding: 0.75rem; margin-bottom: 1.25rem; font-size: 0.8125rem; color: var(--primary); font-weight: 700; display: none; align-items: center; gap: 0.5rem;">
                    <i data-lucide="eye" size="14"></i>
                    Preview: <span id="previewText"></span>
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2;">
                        <i data-lucide="save" size="15"></i> Simpan
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
                    Edit Data Kelas
                </h3>
                <button onclick="document.getElementById('editModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <i data-lucide="x" size="18"></i>
                </button>
            </div>
            <form id="editForm" method="POST" style="padding: 1.5rem;">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="label">Tingkat <span style="color: var(--danger);">*</span></label>
                    <select name="tingkat" id="editTingkat" class="select" required>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="label">Nama Kelas <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="editName" class="input" required>
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

    {{-- Bulk Transfer Modal --}}
    <div id="transferModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 560px; max-width: 90vw; padding: 0; overflow: hidden; animation: modalIn 0.3s ease-out;">
            <div style="padding: 1.5rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="arrow-right-left" size="18" style="color: var(--secondary);"></i>
                    Pindah Siswa Massal
                </h3>
                <button onclick="document.getElementById('transferModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <i data-lucide="x" size="18"></i>
                </button>
            </div>
            <form action="{{ route('classes.bulkTransfer') }}" method="POST" id="transferForm" style="padding: 1.5rem;">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 0.75rem; align-items: end; margin-bottom: 1.25rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="label">Kelas Asal <span style="color: var(--danger);">*</span></label>
                        <select name="source_class_id" id="sourceClass" class="select" required onchange="loadStudents()">
                            <option value="">-- Pilih --</option>
                            @php $sourceClasses = App\Models\SchoolClass::orderBy('tingkat')->orderBy('name')->get(); @endphp
                            @foreach($sourceClasses as $kls)
                                <option value="{{ $kls->id }}">{{ $kls->name }} {{ !$kls->is_active ? '(NONAKTIF)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="padding-bottom: 0.5rem; color: var(--text-muted);">
                        <i data-lucide="arrow-right" size="20"></i>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="label">Kelas Tujuan <span style="color: var(--danger);">*</span></label>
                        <select name="target_class_id" id="targetClass" class="select" required>
                            <option value="">-- Pilih --</option>
                            @php $targetClasses = App\Models\SchoolClass::where('is_active', true)->orderBy('tingkat')->orderBy('name')->get(); @endphp
                            @foreach($targetClasses as $kls)
                                <option value="{{ $kls->id }}">{{ $kls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Student list area --}}
                <div id="studentListArea" style="display: none; margin-bottom: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <label class="label" style="margin-bottom: 0;">Pilih Siswa</label>
                        <label style="font-size: 0.75rem; font-weight: 600; color: var(--primary); cursor: pointer; display: flex; align-items: center; gap: 0.375rem;">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" style="accent-color: var(--primary);">
                            Pilih Semua (<span id="studentCount">0</span>)
                        </label>
                    </div>
                    <div id="studentList" style="max-height: 240px; overflow-y: auto; border: 2px solid #f1f5f9; border-radius: var(--radius-sm); background: #f8fafc;">
                    </div>
                    <div id="selectedInfo" style="margin-top: 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--primary);">
                        <span id="selectedCount">0</span> siswa dipilih
                    </div>
                </div>

                {{-- Loading state --}}
                <div id="loadingState" style="display: none; text-align: center; padding: 1.5rem; color: var(--text-muted); font-size: 0.8125rem;">
                    <div style="display: inline-block; width: 20px; height: 20px; border: 2px solid var(--border); border-top-color: var(--primary); border-radius: 50%; animation: spin 0.6s linear infinite;"></div>
                    <p style="margin-top: 0.5rem;">Memuat data siswa...</p>
                </div>

                {{-- Empty state --}}
                <div id="emptyState" style="display: none; text-align: center; padding: 1.5rem; color: var(--text-muted); font-size: 0.8125rem;">
                    <i data-lucide="inbox" size="24" style="opacity: 0.4; margin-bottom: 0.25rem;"></i>
                    <p>Tidak ada siswa di kelas ini.</p>
                </div>

                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button type="submit" id="transferBtn" class="btn" style="flex: 2; background: var(--secondary); color: white;" disabled>
                        <i data-lucide="arrow-right-left" size="15"></i> Pindahkan
                    </button>
                    <button type="button" onclick="document.getElementById('transferModal').style.display='none'" class="btn btn-outline" style="flex: 1;">Batal</button>
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
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .student-checkbox-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background 150ms;
            cursor: pointer;
        }
        .student-checkbox-row:last-child { border-bottom: none; }
        .student-checkbox-row:hover { background: #eef2ff; }
        .student-checkbox-row input[type="checkbox"] { accent-color: var(--primary); flex-shrink: 0; }
        .student-checkbox-row .student-name { font-weight: 600; font-size: 0.8125rem; color: var(--text); }
        .student-checkbox-row .student-nisn { font-size: 0.6875rem; color: var(--text-muted); }
    </style>
    <script>
        function openAddModal() {
            document.getElementById('addTingkat').value = '';
            document.getElementById('addName').value = '';
            document.getElementById('previewBadge').style.display = 'none';
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

        function updatePreview() {
            var name = document.getElementById('addName').value;
            var badge = document.getElementById('previewBadge');
            var text = document.getElementById('previewText');
            if (name.trim()) {
                text.textContent = name;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        function openTransferModal() {
            document.getElementById('sourceClass').value = '';
            document.getElementById('targetClass').value = '';
            document.getElementById('studentListArea').style.display = 'none';
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('studentList').innerHTML = '';
            document.getElementById('transferBtn').disabled = true;
            document.getElementById('transferModal').style.display = 'flex';
            lucide.createIcons();
        }

        function loadStudents() {
            var classId = document.getElementById('sourceClass').value;
            var listArea = document.getElementById('studentListArea');
            var studentList = document.getElementById('studentList');
            var loading = document.getElementById('loadingState');
            var empty = document.getElementById('emptyState');

            listArea.style.display = 'none';
            empty.style.display = 'none';
            studentList.innerHTML = '';
            updateSelectedCount();

            if (!classId) return;

            loading.style.display = 'block';

            fetch('/classes/' + classId + '/students')
                .then(function(res) { return res.json(); })
                .then(function(students) {
                    loading.style.display = 'none';

                    if (students.length === 0) {
                        empty.style.display = 'block';
                        lucide.createIcons();
                        return;
                    }

                    document.getElementById('studentCount').textContent = students.length;
                    document.getElementById('selectAll').checked = false;

                    var html = '';
                    students.forEach(function(s) {
                        html += '<label class="student-checkbox-row">';
                        html += '<input type="checkbox" name="student_ids[]" value="' + s.id + '" onchange="updateSelectedCount()">';
                        html += '<div><div class="student-name">' + s.name + '</div>';
                        html += '<div class="student-nisn">NISN: ' + (s.nisn || '-') + '</div></div>';
                        html += '</label>';
                    });
                    studentList.innerHTML = html;
                    listArea.style.display = 'block';
                    updateSelectedCount();
                })
                .catch(function() {
                    loading.style.display = 'none';
                    empty.style.display = 'block';
                });
        }

        function toggleSelectAll() {
            var checked = document.getElementById('selectAll').checked;
            var checkboxes = document.querySelectorAll('#studentList input[type="checkbox"]');
            checkboxes.forEach(function(cb) { cb.checked = checked; });
            updateSelectedCount();
        }

        function updateSelectedCount() {
            var checkboxes = document.querySelectorAll('#studentList input[type="checkbox"]:checked');
            var count = checkboxes.length;
            document.getElementById('selectedCount').textContent = count;
            document.getElementById('transferBtn').disabled = count === 0;
        }
    </script>
    @endpush
</x-app-layout>
