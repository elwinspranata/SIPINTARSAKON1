<x-app-layout>
    @section('header_title', $student->name)
    @section('header_subtitle', 'Detail informasi dan riwayat perilaku siswa.')
    @section('header_actions')
        <a href="{{ route('students.edit', $student) }}" class="btn btn-outline"><i data-lucide="pencil" size="14"></i> Edit</a>
        <a href="{{ route('students.index') }}" class="btn btn-primary"><i data-lucide="arrow-left" size="14"></i> Kembali</a>
    @endsection

    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1rem;">
        <!-- Profile Card -->
        <div class="card" style="text-align: center;">
            <div style="width: 80px; height: 80px; margin: 0 auto 1rem; border-radius: var(--radius-lg); background-image: url('https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=2B5EA7&color=fff&size=160&bold=true'); background-size: cover;"></div>
            <h3 style="font-size: 1.125rem; margin-bottom: 0.125rem;">{{ $student->name }}</h3>
            <p style="color: var(--text-muted); font-size: 0.75rem; margin-bottom: 1.5rem;">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }} · {{ $student->schoolClass->name ?? '-' }}</p>

            @php
                if($totalPoints > 100) { $st = 'KRITIS'; $sc = 'danger'; }
                elseif($totalPoints > 50) { $st = 'WASPADA'; $sc = 'warning'; }
                elseif($totalPoints > 20) { $st = 'BAIK'; $sc = 'info'; }
                else { $st = 'AMAN'; $sc = 'success'; }
            @endphp

            <div style="background: var(--{{ $sc }}-light); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--{{ $sc }}); line-height: 1;">{{ $totalPoints }}</div>
                <div style="font-size: 0.6875rem; font-weight: 600; color: var(--{{ $sc }}); margin-top: 0.25rem;">Poin — {{ $st }}</div>
            </div>

            <div style="text-align: left; display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; justify-content: space-between; font-size: 0.8125rem;">
                    <span style="color: var(--text-muted);">NISN</span>
                    <span style="font-weight: 600;">{{ $student->nisn ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.8125rem;">
                    <span style="color: var(--text-muted);">Kelas</span>
                    <span style="font-weight: 600;">{{ $student->schoolClass->name ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.8125rem;">
                    <span style="color: var(--text-muted);">Pelanggaran</span>
                    <span style="font-weight: 600;">{{ $student->behaviorRecords->count() }} kali</span>
                </div>
            </div>
        </div>

        <!-- Records -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 1.25rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 0.9375rem; font-weight: 700;">Riwayat Pelanggaran</h3>
                <span style="font-size: 0.6875rem; color: var(--text-muted);">{{ $student->behaviorRecords->count() }} catatan</span>
            </div>
            
            @forelse($student->behaviorRecords->sortByDesc('date') as $record)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--border-light);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--danger-light); color: var(--danger); display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="alert-triangle" size="14"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.8125rem;">{{ $record->violationType->name ?? '-' }}</div>
                        <div style="font-size: 0.6875rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }} · {{ $record->violationType->category ?? '' }}</div>
                    </div>
                </div>
                <span style="font-weight: 700; color: var(--danger); font-size: 0.8125rem;">+{{ $record->violationType->points ?? 0 }}</span>
            </div>
            @empty
            <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                <i data-lucide="check-circle" size="28" style="opacity: 0.3; margin-bottom: 0.5rem;"></i>
                <div style="font-size: 0.8125rem;">Belum ada catatan pelanggaran. 🎉</div>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
