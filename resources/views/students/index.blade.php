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
        <div class="card" style="padding: 0.75rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;">
            <div style="position: relative; flex: 1;">
                <i data-lucide="search" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);" size="15"></i>
                <input type="text" name="search" class="input" placeholder="Cari nama atau NISN..." style="padding-left: 2.25rem; height: 36px;" value="{{ request('search') }}">
            </div>
            <select name="class_id" class="select" style="width: 160px; height: 36px;" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline" style="height: 36px; padding: 0 1rem;">
                <i data-lucide="search" size="14"></i> Cari
            </button>
            <span style="font-size: 0.6875rem; color: var(--text-muted); white-space: nowrap;"><strong>{{ $students->total() }}</strong> siswa</span>
        </div>
    </form>

    <!-- Table -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
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
                        $points = $student->behaviorRecords->sum(fn($r) => $r->violationType->points ?? 0);
                        if($points > 100) { $sc = 'danger'; $st = 'KRITIS'; $pc = 'var(--danger)'; }
                        elseif($points > 50) { $sc = 'warning'; $st = 'WASPADA'; $pc = 'var(--warning)'; }
                        elseif($points > 20) { $sc = 'info'; $st = 'BAIK'; $pc = 'var(--info)'; }
                        else { $sc = 'success'; $st = 'AMAN'; $pc = 'var(--success)'; }
                    @endphp
                    <tr>
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
                    <tr><td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">Belum ada data siswa.</td></tr>
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
</x-app-layout>
