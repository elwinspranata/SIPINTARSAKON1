<x-app-layout>
    @section('header_title', 'Database Siswa')
    @section('header_subtitle', 'Kelola informasi dan rekam jejak perilaku siswa.')
    @section('header_actions')
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i data-lucide="plus" size="15"></i> Tambah Siswa
        </a>
    @endsection

    <!-- Filters -->
    <form method="GET" action="{{ route('students.index') }}">
        <div class="filter-bar">
            <div class="filter-search">
                <i data-lucide="search" class="filter-search-icon" size="15"></i>
                <input type="text" name="search" class="filter-input" placeholder="Cari nama atau NISN..." value="{{ request('search') }}">
            </div>
            <select name="class_id" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline filter-btn">
                <i data-lucide="search" size="14"></i> Cari
            </button>
            <span class="filter-count" style="font-size: 0.9375rem; color: var(--text); margin-left: auto;"><strong>{{ $students->total() }}</strong> siswa</span>
        </div>
    </form>

    <!-- Table -->
    <form id="bulkDeleteForm" action="{{ route('students.bulkDestroy') }}" method="POST">
        @csrf
        @method('DELETE')
        
        <!-- Bulk Action Bar -->
        <div id="bulkActionBar" style="display: none; background: var(--danger-light); padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; align-items: center; justify-content: space-between; border: 1px solid rgba(255, 77, 77, 0.15); animation: fadeIn 0.2s ease-out;">
            <div style="font-size: 0.875rem; font-weight: 600; color: var(--danger);">
                <span id="selectedCount">0</span> siswa terpilih
            </div>
            <button type="submit" class="btn" style="background: var(--danger); color: white; padding: 0.5rem 1rem; font-size: 0.8125rem; height: auto;" onclick="return confirm('Hapus permanen semua siswa yang dipilih beserta data perilakunya?')">
                <i data-lucide="trash-2" size="14"></i> Hapus Terpilih
            </button>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" style="accent-color: var(--primary); cursor: pointer;">
                            </th>
                            <th>Siswa</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse($students as $student)
                    @php
                        $vPoints = $student->behaviorRecords->whereNotNull('violation_type_id')->sum(fn($r) => $r->violationType->points ?? 0);
                        $aPoints = $student->behaviorRecords->whereNotNull('vitamin_type_id')->sum(fn($r) => $r->vitaminType->points ?? 0);
                        $points = max(0, $vPoints - $aPoints);
                        if($points > 100) { $sc = 'danger'; $st = 'KRITIS'; $pc = 'var(--danger)'; }
                        elseif($points > 50) { $sc = 'warning'; $st = 'WASPADA'; $pc = 'var(--warning)'; }
                        elseif($points > 20) { $sc = 'info'; $st = 'BAIK'; $pc = 'var(--info)'; }
                        else { $sc = 'success'; $st = 'AMAN'; $pc = 'var(--success)'; }
                    @endphp
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" name="ids[]" value="{{ $student->id }}" class="student-checkbox" onchange="updateBulkActionUI()" style="accent-color: var(--primary); cursor: pointer;">
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background-image: url('https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random&color=fff&size=64'); background-size: cover; flex-shrink: 0;"></div>
                                <div>
                                    <div style="font-weight: 600; font-size: 0.8125rem;">{{ $student->name }}</div>
                                    <div style="font-size: 0.6875rem; color: var(--text-muted);">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-family: 'SF Mono', monospace; color: var(--text-muted); font-size: 0.75rem;">{{ $student->nisn ?? '-' }}</td>
                        <td>{{ $student->schoolClass->name ?? '-' }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="font-weight: 700; font-size: 0.8125rem; width: 24px;">{{ $points }}</span>
                                <div style="flex: 1; height: 4px; background: var(--border-light); border-radius: 10px; min-width: 60px;">
                                    <div style="width: {{ min(($points/150)*100, 100) }}%; height: 100%; background: {{ $pc }}; border-radius: 10px; transition: width 0.5s;"></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="status-badge" style="background: var(--{{ $sc }}-light); color: var(--{{ $sc }});">{{ $st }}</span></td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0;" title="Detail"><i data-lucide="eye" size="14"></i></a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0;" title="Edit"><i data-lucide="pencil" size="14"></i></a>
                                <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Hapus siswa ini?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0; color: var(--danger);" title="Hapus"><i data-lucide="trash-2" size="14"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">Belum ada data siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div style="padding: 1rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border);">
            <span style="font-size: 0.6875rem; color: var(--text-muted);">Hal. {{ $students->currentPage() }}/{{ $students->lastPage() }}</span>
            <div style="display: flex; gap: 0.25rem;">
                @if(!$students->onFirstPage())
                    <a href="{{ $students->previousPageUrl() }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem;"><i data-lucide="chevron-left" size="14"></i></a>
                @endif
                @for($i = 1; $i <= min($students->lastPage(), 5); $i++)
                    <a href="{{ $students->url($i) }}" class="btn {{ $students->currentPage() == $i ? 'btn-primary' : 'btn-outline' }}" style="padding: 0.25rem 0.625rem; min-width: 32px; font-size: 0.75rem;">{{ $i }}</a>
                @endfor
                @if($students->hasMorePages())
                    <a href="{{ $students->nextPageUrl() }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem;"><i data-lucide="chevron-right" size="14"></i></a>
                @endif
            </div>
        </div>
        @endif
    </div>
    </form>

    @push('scripts')
    <script>
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
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endpush
</x-app-layout>
