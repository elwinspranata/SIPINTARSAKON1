<x-app-layout>
    @php
        $type = request('type', 'violation');
        $isViolation = $type == 'violation';
        $accentColor = $isViolation ? '#ef4444' : '#10b981';
        $accentBg = $isViolation ? '#fef2f2' : '#ecfdf5';
    @endphp

    @section('header_title', $isViolation ? 'Input Penyakit' : 'Berikan Vitamin')
    @section('header_subtitle', $isViolation ? 'Catat perilaku negatif siswa yang melanggar aturan sekolah.' : 'Berikan apresiasi atas prestasi dan kebaikan siswa.')

    @if($errors->any())
        <div
            style="background: #fef2f2; color: #ef4444; padding: 1rem; border-radius: 14px; margin-bottom: 2rem; font-size: 0.8125rem; border: 1px solid rgba(239,68,68,0.1); animation: fadeInUp 0.4s ease-out;">
            <ul style="padding-left: 1.5rem; font-weight: 700;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="dashboard-grid"
        style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.5rem; align-items: start;">
        <!-- Form Section -->
        <div class="card"
            style="padding: 1.5rem; border: 1px solid var(--border-light); animation: fadeInUp 0.5s ease-out;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2.5rem;">
                <div
                    style="width: 54px; height: 54px; background: {{ $accentBg }}; color: {{ $accentColor }}; border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px {{ $isViolation ? 'rgba(239,68,68,0.1)' : 'rgba(16,185,129,0.1)' }};">
                    <i data-lucide="{{ $isViolation ? 'alert-octagon' : 'sparkles' }}"
                        style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary-dark);">
                        {{ $isViolation ? 'Form Catatan Pelanggaran' : 'Form Pemberian Prestasi' }}</h2>
                    <p style="font-size: 0.8125rem; color: var(--text-muted); font-weight: 500;">Silakan lengkapi detail
                        informasi di bawah ini.</p>
                </div>
            </div>

            <form action="{{ route('records.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="form-grid"
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">Target Kelas</label>
                        <select id="classSelect" class="select" required onchange="loadClassStudents()"
                            style="height: 48px;">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">Nama Siswa</label>
                        <select name="student_id" id="studentSelect" class="select" required disabled
                            onchange="loadStudentPoints()" style="height: 48px;">
                            <option value="">-- Pilih kelas dulu --</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid"
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">Kategori
                            {{ $isViolation ? 'Pelanggaran' : 'Vitamin' }}</label>
                        <select name="category" id="categorySelect" class="select" required onchange="loadTypes()"
                            style="height: 48px;">
                            <option value="">-- Pilih Kategori --</option>
                            @php $typesGroup = $isViolation ? $violation_types : $vitamin_types; @endphp
                            @foreach($typesGroup as $category => $types)
                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">Jenis
                            {{ $isViolation ? 'Pelanggaran' : 'Vitamin' }}</label>
                        <select name="{{ $isViolation ? 'violation_type_id' : 'vitamin_type_id' }}" id="typeSelect"
                            class="select" required disabled style="height: 48px;">
                            <option value="">-- Pilih kategori dulu --</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">Keterangan
                        Tambahan</label>
                    <textarea name="notes" class="textarea" rows="3"
                        placeholder="Jelaskan kronologi atau detail kejadian..."
                        style="border-radius: 14px; padding: 1rem; border: 1px solid var(--border-light);">{{ old('notes') }}</textarea>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; height: 52px; border-radius: 14px; font-weight: 800; background: {{ $accentColor }}; border: none; box-shadow: 0 10px 20px {{ $isViolation ? 'rgba(239,68,68,0.2)' : 'rgba(16,185,129,0.2)' }};">
                        <i data-lucide="save" style="width: 20px; height: 20px;"></i>
                        {{ $isViolation ? 'Simpan Catatan' : 'Berikan Vitamin' }}
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline"
                        style="width: 100%; height: 52px; border-radius: 14px; font-weight: 800;">Batal</a>
                </div>
            </form>
        </div>

        <!-- Sidebar Info Section -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            {{-- Student Quick View Card --}}
            <div id="studentInfoCard" class="card"
                style="display: none; padding: 2rem; border: none; background: linear-gradient(135deg, var(--primary-dark), #1e293b); color: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); animation: fadeInUp 0.4s ease-out;">
                <h3
                    style="font-size: 0.8125rem; font-weight: 800; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1.25rem;">
                    Profil Siswa</h3>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div id="infoAvatar"
                        style="width: 48px; height: 48px; border-radius: 14px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 900;">
                    </div>
                    <div>
                        <div id="infoName" style="font-weight: 900; font-size: 1.125rem; letter-spacing: -0.02em;">
                        </div>
                        <div id="infoNisn" style="font-size: 0.75rem; color: rgba(255,255,255,0.6); font-weight: 600;">
                        </div>
                    </div>
                </div>
                <div
                    style="background: rgba(255,255,255,0.05); padding: 1.25rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.8125rem; font-weight: 700; color: rgba(255,255,255,0.8);">Akumulasi
                            Poin</span>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div id="infoPoints" style="font-size: 1.75rem; font-weight: 900; line-height: 1;"></div>
                            <div id="infoStatus"
                                style="font-size: 0.625rem; font-weight: 900; margin-top: 4px; padding: 0.2rem 0.6rem; border-radius: 6px; display: inline-block;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Guide Card --}}
            <div class="card" style="padding: 1.5rem; border: 1px solid var(--border-light);">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                    <div
                        style="width: 32px; height: 32px; background: var(--bg); color: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="help-circle" style="width: 18px; height: 18px;"></i>
                    </div>
                    <h4 style="font-weight: 800; color: var(--primary-dark); font-size: 0.9375rem;">Panduan Singkat</h4>
                </div>
                <ul style="display: flex; flex-direction: column; gap: 0.875rem; padding: 0; list-style: none;">
                    <li style="display: flex; gap: 0.75rem;">
                        <i data-lucide="check-circle-2"
                            style="width: 16px; height: 16px; color: var(--success); flex-shrink: 0; margin-top: 2px;"></i>
                        <span style="font-size: 0.8125rem; font-weight: 600; color: var(--text-secondary);">Poin
                            bersifat akumulatif.</span>
                    </li>
                    <li style="display: flex; gap: 0.75rem;">
                        <i data-lucide="check-circle-2"
                            style="width: 16px; height: 16px; color: var(--success); flex-shrink: 0; margin-top: 2px;"></i>
                        <span style="font-size: 0.8125rem; font-weight: 600; color: var(--text-secondary);">Vitamin
                            diberikan untuk prestasi akademik maupun perilaku positif.</span>
                    </li>
                    <li style="display: flex; gap: 0.75rem;">
                        <i data-lucide="check-circle-2"
                            style="width: 16px; height: 16px; color: var(--success); flex-shrink: 0; margin-top: 2px;"></i>
                        <span style="font-size: 0.8125rem; font-weight: 600; color: var(--text-secondary);">Penyakit
                            diberikan sebagai konsekuensi atas perilaku negatif.</span>
                    </li>
                </ul>
            </div>

            {{-- Score Indicator Card --}}
            <div class="card"
                style="padding: 1.5rem; background: linear-gradient(135deg, #334155, #0f172a); border: none; color: white;">
                <h4
                    style="font-weight: 800; font-size: 0.875rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem;">
                    <i data-lucide="bar-chart-3" style="width: 18px; height: 18px; color: var(--secondary);"></i>
                    Indikator Kedisiplinan
                </h4>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @php
                        $levels = [
                            ['range' => '0 - 20', 'label' => 'Aman', 'color' => '#10b981'],
                            ['range' => '21 - 50', 'label' => 'Baik', 'color' => '#3b82f6'],
                            ['range' => '51 - 100', 'label' => 'Waspada (SP1)', 'color' => '#f59e0b'],
                            ['range' => '> 100', 'label' => 'Kritis (SP3)', 'color' => '#ef4444'],
                        ];
                    @endphp
                    @foreach($levels as $lvl)
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.05); padding: 0.6rem 0.875rem; border-radius: 10px;">
                            <span
                                style="font-size: 0.75rem; font-weight: 700; color: rgba(255,255,255,0.7);">{{ $lvl['range'] }}
                                Poin</span>
                            <span
                                style="font-size: 0.75rem; font-weight: 900; color: {{ $lvl['color'] }};">{{ $lvl['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var oldStudentId = '{{ old('student_id') }}';
            var typesData = @json($isViolation ? $violation_types : $vitamin_types);
            var isViolation = {{ $isViolation ? 'true' : 'false' }};
            var oldTypeId = '{{ old($isViolation ? 'violation_type_id' : 'vitamin_type_id') }}';

            var classSelectTs, studentSelectTs, categorySelectTs, typeSelectTs;

            function initTomSelects() {
                classSelectTs = new TomSelect('#classSelect', {
                    create: false,
                    plugins: ['dropdown_input'],
                    placeholder: "-- Pilih Kelas --"
                });

                studentSelectTs = new TomSelect('#studentSelect', {
                    create: false,
                    plugins: ['dropdown_input'],
                    valueField: 'id',
                    labelField: 'name',
                    searchField: 'name',
                    placeholder: "-- Pilih kelas dulu --"
                });

                categorySelectTs = new TomSelect('#categorySelect', {
                    create: false,
                    plugins: ['dropdown_input'],
                    placeholder: "-- Pilih Kategori --"
                });

                typeSelectTs = new TomSelect('#typeSelect', {
                    create: false,
                    plugins: ['dropdown_input'],
                    valueField: 'id',
                    labelField: 'name',
                    searchField: 'name',
                    placeholder: "-- Pilih kategori dulu --"
                });

                classSelectTs.on('change', loadClassStudents);
                studentSelectTs.on('change', loadStudentPoints);
                categorySelectTs.on('change', loadTypes);
            }

            function loadTypes() {
                var category = categorySelectTs.getValue();

                typeSelectTs.clearOptions();
                typeSelectTs.clear();

                if (!category || !typesData[category]) {
                    typeSelectTs.disable();
                    return;
                }

                var types = typesData[category];
                var options = types.map(t => {
                    var pointsText = isViolation ? `(${t.points} Poin)` : `(+${t.points} Poin)`;
                    return { id: t.id, name: `${t.name} ${pointsText}` };
                });

                typeSelectTs.addOptions(options);
                typeSelectTs.enable();

                if (oldTypeId) {
                    typeSelectTs.setValue(oldTypeId);
                    oldTypeId = null;
                }
            }

            function loadClassStudents() {
                var classId = classSelectTs.getValue();

                studentSelectTs.clearOptions();
                studentSelectTs.clear();
                studentSelectTs.disable();
                document.getElementById('studentInfoCard').style.display = 'none';

                if (!classId) return;

                fetch('/classes/' + classId + '/students')
                    .then(res => res.json())
                    .then(students => {
                        if (students.length === 0) {
                            return;
                        }

                        studentSelectTs.addOptions(students);
                        studentSelectTs.enable();

                        if (oldStudentId) {
                            studentSelectTs.setValue(oldStudentId);
                            oldStudentId = null;
                        }
                    })
                    .catch(err => console.error("Error loading students:", err));
            }

            function loadStudentPoints() {
                var studentId = studentSelectTs.getValue();
                var card = document.getElementById('studentInfoCard');

                if (!studentId) {
                    card.style.display = 'none';
                    return;
                }

                fetch('/students/' + studentId + '/points')
                    .then(res => res.json())
                    .then(data => {
                        var colorMap = {
                            danger: '#ef4444',
                            warning: '#f59e0b',
                            info: '#3b82f6',
                            success: '#10b981'
                        };
                        var c = colorMap[data.color] || colorMap.success;

                        card.style.display = 'block';
                        document.getElementById('infoAvatar').textContent = data.name.charAt(0);
                        document.getElementById('infoName').textContent = data.name;
                        document.getElementById('infoNisn').textContent = 'NISN: ' + (data.nisn || '-');
                        document.getElementById('infoPoints').textContent = data.points;
                        document.getElementById('infoPoints').style.color = c;

                        var statusEl = document.getElementById('infoStatus');
                        statusEl.textContent = data.status;
                        statusEl.style.backgroundColor = c;
                        statusEl.style.color = 'white';
                    });
            }

            document.addEventListener('DOMContentLoaded', function () {
                initTomSelects();
                if (classSelectTs.getValue()) loadClassStudents();
                if (categorySelectTs.getValue()) loadTypes();
            });
        </script>
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush
</x-app-layout>