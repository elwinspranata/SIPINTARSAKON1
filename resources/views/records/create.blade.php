<x-app-layout>
    @php
        $type = request('type', 'violation');
        $isViolation = $type == 'violation';
    @endphp

    @section('header_title', $isViolation ? 'Input Pelanggaran (Penyakit)' : 'Input Prestasi (Vitamin)')
    @section('header_subtitle', $isViolation ? 'Catat perilaku negatif siswa yang melanggar aturan sekolah.' : 'Berikan apresiasi atas prestasi dan kebaikan siswa.')

    @if($errors->any())
    <div style="background: var(--danger-light); color: var(--danger); padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.8125rem; border: 1px solid rgba(239,68,68,0.1);">
        <ul style="padding-left: 1rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1rem; align-items: start;">
        <!-- Form -->
        <div class="card">
            <form action="{{ route('records.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="form-group">
                    <label class="label">Pilih Kelas <span style="color: var(--danger);">*</span></label>
                    <select id="classSelect" class="select" required onchange="loadClassStudents()">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="label">Pilih Siswa <span style="color: var(--danger);">*</span></label>
                    <select name="student_id" id="studentSelect" class="select" required disabled onchange="loadStudentPoints()">
                        <option value="">-- Pilih kelas terlebih dahulu --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="label">{{ $isViolation ? 'Jenis Pelanggaran' : 'Kategori Vitamin' }} <span style="color: var(--danger);">*</span></label>
                    @if($isViolation)
                    <select name="violation_type_id" class="select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($violation_types as $category => $types)
                            <optgroup label="{{ $category }}">
                                @foreach($types as $vt)
                                    <option value="{{ $vt->id }}" {{ old('violation_type_id') == $vt->id ? 'selected' : '' }}>{{ $vt->name }} ({{ $vt->points }} Poin)</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @else
                    <select name="vitamin_type_id" class="select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($vitamin_types as $category => $types)
                            <optgroup label="{{ $category }}">
                                @foreach($types as $vt)
                                    <option value="{{ $vt->id }}" {{ old('vitamin_type_id') == $vt->id ? 'selected' : '' }}>{{ $vt->name }} (+{{ $vt->points }} Poin)</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @endif
                </div>

                <div class="form-group">
                    <label class="label">Keterangan</label>
                    <textarea name="notes" class="textarea" rows="3" placeholder="Jelaskan detail kejadian...">{{ old('notes') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="label">Bukti Foto (Opsional)</label>
                    <div style="border: 2px dashed var(--border); border-radius: var(--radius-md); padding: 1.5rem; text-align: center; cursor: pointer; transition: all 200ms;" onclick="this.querySelector('input').click()" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                        <i data-lucide="camera" style="color: var(--text-muted); opacity: 0.4;" size="24"></i>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Klik untuk upload</div>
                        <input type="file" name="evidence" style="display: none;">
                    </div>
                </div>

                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn {{ $isViolation ? '' : '' }}" style="flex: 2; padding: 0.75rem; background: {{ $isViolation ? 'var(--danger)' : 'var(--success)' }}; color: white; font-size: 0.875rem; border-radius: var(--radius-md);">
                        <i data-lucide="save" size="15"></i> {{ $isViolation ? 'Simpan Pelanggaran' : 'Simpan Prestasi' }}
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline" style="flex: 1; padding: 0.75rem;">Batal</a>
                </div>
            </form>
        </div>

        <!-- Guide Panel -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            {{-- Student Points Info Card (Sidebar) --}}
            <div id="studentInfoCard" class="card" style="display: none; animation: fadeInUp 0.3s ease-out;">
                <h3 style="font-size: 0.875rem; font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted);">
                    <i data-lucide="user-check" size="15"></i> Siswa Terpilih
                </h3>
                <div id="infoName" style="font-weight: 800; font-size: 1.125rem; color: var(--text); margin-bottom: 1rem; line-height: 1.2;"></div>
                
                <div style="display: flex; align-items: center; justify-content: space-between; background: var(--bg-hover); padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                    <div style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">Total Poin</div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span id="infoPoints" style="font-size: 1.25rem; font-weight: 900; line-height: 1;"></span>
                        <span id="infoStatus" style="font-size: 0.625rem; font-weight: 800; text-transform: uppercase; padding: 0.25rem 0.5rem; border-radius: 99px;"></span>
                    </div>
                </div>
            </div>
            @if($isViolation)
            <div class="card" style="border-left: 3px solid var(--danger);">
                <h3 style="font-size: 0.875rem; font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="info" size="15" style="color: var(--danger);"></i> Panduan
                </h3>
                <ul style="font-size: 0.75rem; color: var(--text-secondary); padding-left: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <li>Akumulasi poin berlaku selama 1 tahun ajaran</li>
                    <li>Poin Penyakit dapat dikurangi dengan Poin Vitamin</li>
                    <li>Notifikasi otomatis dikirim ke Wali Kelas & Orang Tua</li>
                </ul>
            </div>
            @else
            <div class="card" style="border-left: 3px solid var(--success);">
                <h3 style="font-size: 0.875rem; font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="sparkles" size="15" style="color: var(--success);"></i> Manfaat Vitamin
                </h3>
                <ul style="font-size: 0.75rem; color: var(--text-secondary); padding-left: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <li>Mengurangi akumulasi poin pelanggaran</li>
                    <li>Sertifikat elektronik terbit otomatis</li>
                    <li>Notifikasi apresiasi ke Orang Tua</li>
                </ul>
            </div>
            @endif

            <div class="card" style="background: var(--primary-dark); border: none; color: white;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: white; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="book-open" size="15" style="color: var(--secondary);"></i> Pedoman Skor
                </h3>
                <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.75rem; color: rgba(255,255,255,0.7);">
                    <div style="display: flex; justify-content: space-between;"><span>0 - 20 poin</span><span style="color: #22c55e; font-weight: 600;">Aman ✓</span></div>
                    <div style="display: flex; justify-content: space-between;"><span>21 - 50 poin</span><span style="color: #3b82f6; font-weight: 600;">Baik</span></div>
                    <div style="display: flex; justify-content: space-between;"><span>51 - 100 poin</span><span style="color: #f59e0b; font-weight: 600;">Waspada (SP1)</span></div>
                    <div style="display: flex; justify-content: space-between;"><span>> 100 poin</span><span style="color: #ef4444; font-weight: 600;">Kritis (SP3)</span></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        var oldStudentId = '{{ old('student_id') }}';

        function loadClassStudents() {
            var classId = document.getElementById('classSelect').value;
            var studentSelect = document.getElementById('studentSelect');

            studentSelect.innerHTML = '<option value="">-- Memuat siswa... --</option>';
            studentSelect.disabled = true;
            document.getElementById('studentInfoCard').style.display = 'none';

            if (!classId) {
                studentSelect.innerHTML = '<option value="">-- Pilih kelas terlebih dahulu --</option>';
                return;
            }

            fetch('/classes/' + classId + '/students')
                .then(function(res) { return res.json(); })
                .then(function(students) {
                    if (students.length === 0) {
                        studentSelect.innerHTML = '<option value="">-- Tidak ada siswa --</option>';
                        return;
                    }

                    var html = '<option value="">-- Pilih Siswa --</option>';
                    students.forEach(function(s) {
                        var selected = oldStudentId == s.id ? ' selected' : '';
                        html += '<option value="' + s.id + '"' + selected + '>' + s.name + '</option>';
                    });
                    studentSelect.innerHTML = html;
                    studentSelect.disabled = false;

                    // Auto-load points if old student was pre-selected
                    if (studentSelect.value) {
                        loadStudentPoints();
                    }
                })
                .catch(function() {
                    studentSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
                });
        }

        function loadStudentPoints() {
            var studentId = document.getElementById('studentSelect').value;
            var card = document.getElementById('studentInfoCard');

            if (!studentId) {
                card.style.display = 'none';
                return;
            }

            fetch('/students/' + studentId + '/points')
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var colorMap = {
                        danger: { bg: 'var(--danger-light)', text: 'var(--danger)', border: 'rgba(239,68,68,0.15)' },
                        warning: { bg: 'var(--warning-light)', text: 'var(--warning)', border: 'rgba(245,158,11,0.15)' },
                        info: { bg: 'var(--info-light)', text: 'var(--info)', border: 'rgba(59,130,246,0.15)' },
                        success: { bg: 'var(--success-light)', text: 'var(--success)', border: 'rgba(0,210,106,0.15)' }
                    };
                    var c = colorMap[data.color] || colorMap.success;

                    card.style.borderLeft = '3px solid ' + c.text;
                    card.style.display = 'block';

                    document.getElementById('infoName').textContent = data.name;
                    document.getElementById('infoPoints').textContent = data.points;
                    document.getElementById('infoPoints').style.color = c.text;
                    
                    var statusEl = document.getElementById('infoStatus');
                    statusEl.textContent = data.status;
                    statusEl.style.backgroundColor = c.text;
                    statusEl.style.color = 'white';
                })
                .catch(function() {
                    card.style.display = 'none';
                });
        }

        // Auto-load if old class_id exists (validation error redirect)
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('classSelect').value) {
                loadClassStudents();
            }
        });
    </script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endpush
</x-app-layout>
