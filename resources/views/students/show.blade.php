<x-app-layout>
    @section('header_title', $student->name)
    @section('header_subtitle', 'Detail informasi dan riwayat perilaku siswa.')
    @section('header_actions')
        <a href="{{ route('students.edit', $student) }}" class="btn btn-outline"><i data-lucide="pencil" size="14"></i> Edit</a>
        <a href="{{ route('students.index') }}" class="btn btn-primary"><i data-lucide="arrow-left" size="14"></i> Kembali</a>
    @endsection

    @php
        $violationRecords = $student->behaviorRecords->whereNotNull('violation_type_id');
        $vitaminRecords = $student->behaviorRecords->whereNotNull('vitamin_type_id');
        $totalViolation = $violationRecords->sum(fn($r) => $r->violationType->points ?? 0);
        $totalVitamin = $vitaminRecords->sum(fn($r) => $r->vitaminType->points ?? 0);
        $netPoints = max(0, $totalViolation - $totalVitamin);

        if($netPoints > 100) { $st = 'KRITIS'; $sc = 'danger'; }
        elseif($netPoints > 50) { $st = 'WASPADA'; $sc = 'warning'; }
        elseif($netPoints > 20) { $st = 'BAIK'; $sc = 'info'; }
        else { $st = 'AMAN'; $sc = 'success'; }
    @endphp

    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1rem;">
        <!-- Profile Card -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1rem; border-radius: var(--radius-lg); background-image: url('https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=2B5EA7&color=fff&size=160&bold=true'); background-size: cover;"></div>
                <h3 style="font-size: 1.125rem; margin-bottom: 0.125rem;">{{ $student->name }}</h3>
                <p style="color: var(--text-muted); font-size: 0.75rem; margin-bottom: 1.5rem;">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }} · {{ $student->schoolClass->name ?? '-' }}</p>

                <div style="background: var(--{{ $sc }}-light); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem;">
                    <div style="font-size: 2rem; font-weight: 800; color: var(--{{ $sc }}); line-height: 1;">{{ $netPoints }}</div>
                    <div style="font-size: 0.6875rem; font-weight: 600; color: var(--{{ $sc }}); margin-top: 0.25rem;">Poin Bersih — {{ $st }}</div>
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
                </div>
            </div>

            <!-- Point Breakdown Card -->
            <div class="card" style="padding: 1.25rem;">
                <h4 style="font-size: 0.8125rem; font-weight: 800; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="calculator" size="15" style="color: var(--primary);"></i> Kalkulasi Poin
                </h4>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8125rem;">
                        <span style="color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--danger);"></span>
                            Poin Penyakit
                        </span>
                        <span style="font-weight: 700; color: var(--danger);">+{{ $totalViolation }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8125rem;">
                        <span style="color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--success);"></span>
                            Poin Vitamin
                        </span>
                        <span style="font-weight: 700; color: var(--success);">-{{ $totalVitamin }}</span>
                    </div>
                    <div style="border-top: 2px solid var(--border-light); padding-top: 0.75rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem;">
                        <span style="font-weight: 800;">Poin Bersih</span>
                        <span style="font-weight: 800; color: var(--{{ $sc }}); font-size: 1.125rem;">{{ $netPoints }}</span>
                    </div>
                </div>

                <!-- Point Bar Visual -->
                <div style="margin-top: 1rem;">
                    <div style="height: 8px; background: var(--border-light); border-radius: 99px; overflow: hidden; position: relative;">
                        <div style="position: absolute; left: 0; top: 0; height: 100%; width: {{ min(($netPoints / 150) * 100, 100) }}%; background: var(--{{ $sc }}); border-radius: 99px; transition: width 0.5s;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.625rem; font-weight: 700; color: var(--text-muted);">
                        <span>0</span><span>20</span><span>50</span><span>100</span><span>150</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Records Column -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <!-- Violation Records -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 0.9375rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="alert-triangle" size="15" style="color: var(--danger);"></i> Riwayat Penyakit
                    </h3>
                    <span class="status-badge" style="background: var(--danger-light); color: var(--danger);">{{ $violationRecords->count() }} catatan</span>
                </div>
                
                @forelse($violationRecords->sortByDesc('date') as $record)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-light);">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--danger-light); color: var(--danger); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i data-lucide="alert-triangle" size="14"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.8125rem;">{{ $record->violationType->name ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }} · {{ $record->violationType->category ?? '' }} · oleh {{ $record->user->name ?? '-' }}</div>
                        </div>
                    </div>
                    <span style="font-weight: 700; color: var(--danger); font-size: 0.875rem; white-space: nowrap;">+{{ $record->violationType->points ?? 0 }}</span>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.8125rem;">
                    <i data-lucide="check-circle" size="28" style="opacity: 0.3; margin-bottom: 0.5rem;"></i>
                    <div>Belum ada catatan penyakit. 🎉</div>
                </div>
                @endforelse
            </div>

            <!-- Vitamin Records -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 0.9375rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="sparkles" size="15" style="color: var(--success);"></i> Riwayat Vitamin (Prestasi)
                    </h3>
                    <span class="status-badge" style="background: var(--success-light); color: var(--success);">{{ $vitaminRecords->count() }} catatan</span>
                </div>
                
                @forelse($vitaminRecords->sortByDesc('date') as $record)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-light);">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--success-light); color: var(--success); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i data-lucide="sparkles" size="14"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.8125rem;">{{ $record->vitaminType->name ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }} · {{ $record->vitaminType->category ?? '' }} · oleh {{ $record->user->name ?? '-' }}</div>
                        </div>
                    </div>
                    <span style="font-weight: 700; color: var(--success); font-size: 0.875rem; white-space: nowrap;">-{{ $record->vitaminType->points ?? 0 }}</span>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.8125rem;">
                    <i data-lucide="award" size="28" style="opacity: 0.3; margin-bottom: 0.5rem;"></i>
                    <div>Belum ada catatan vitamin.</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
