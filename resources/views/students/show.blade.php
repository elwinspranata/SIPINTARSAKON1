<x-app-layout>
    @section('header_title', $student->name)
    @section('header_subtitle', 'Detail profil akademik dan rekam jejak kedisiplinan siswa.')
    @section('header_actions')
        <button onclick="openEditStudentModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->nisn }}', '{{ $student->class_id }}', '{{ $student->gender }}')" class="btn btn-outline" style="border-radius: 12px; border-color: #d97706; color: #d97706;">
            <i data-lucide="pencil" style="width: 14px; height: 14px;"></i> Edit Profil
        </button>
        <a href="{{ route('students.index') }}" class="btn btn-primary" style="border-radius: 12px;">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i> Kembali
        </a>
    @endsection

    @php
        $violationRecords = $student->behaviorRecords->whereNotNull('violation_type_id');
        $vitaminRecords = $student->behaviorRecords->whereNotNull('vitamin_type_id');
        $totalViolation = $violationRecords->sum(fn($r) => $r->violationType->points ?? 0);
        $totalVitamin = $vitaminRecords->sum(fn($r) => $r->vitaminType->points ?? 0);
        $netPoints = max(0, $totalViolation - $totalVitamin);

        if($netPoints > 100) { $st = 'KRITIS'; $sc = 'danger'; $pc = '#ef4444'; $bgc = '#fef2f2'; }
        elseif($netPoints > 50) { $st = 'WASPADA'; $sc = 'warning'; $pc = '#f59e0b'; $bgc = '#fffbeb'; }
        elseif($netPoints > 20) { $st = 'BAIK'; $sc = 'info'; $pc = '#3b82f6'; $bgc = '#eff6ff'; }
        else { $st = 'AMAN'; $sc = 'success'; $pc = '#10b981'; $bgc = '#ecfdf5'; }
    @endphp

    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; animation: fadeInUp 0.4s ease-out;">
        <!-- Left Column: Profile & Summary -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Profile Card -->
            <div class="card" style="text-align: center; border: 1px solid var(--border-light); padding: 2rem 1.5rem;">
                <div style="position: relative; display: inline-block; margin-bottom: 1.5rem;">
                    <div style="width: 100px; height: 100px; border-radius: 30px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random&color=fff&size=200&bold=true'); background-size: cover; border: 4px solid #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.1);"></div>
                    <div style="position: absolute; bottom: 0; right: 0; width: 28px; height: 28px; background: {{ $student->gender == 'L' ? '#0ea5e9' : '#db2777' }}; border: 3px solid #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i data-lucide="{{ $student->gender == 'L' ? 'user' : 'user-round' }}" style="width: 12px; height: 12px;"></i>
                    </div>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 0.25rem;">{{ $student->name }}</h3>
                <p style="color: var(--text-muted); font-size: 0.8125rem; font-weight: 600; margin-bottom: 2rem;">NISN: {{ $student->nisn ?? '---' }}</p>

                <div style="background: {{ $bgc }}; padding: 1.5rem; border-radius: 20px; margin-bottom: 1.5rem; border: 1px solid rgba(0,0,0,0.02);">
                    <div style="font-size: 2.5rem; font-weight: 900; color: {{ $pc }}; line-height: 1; letter-spacing: -0.04em;">{{ $netPoints }}</div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: {{ $pc }}; margin-top: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Status: {{ $st }}</div>
                </div>

                <div style="text-align: left; display: flex; flex-direction: column; gap: 1rem; padding: 0 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: var(--bg); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="school" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 0.625rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Kelas Sekarang</div>
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">{{ $student->schoolClass->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: var(--bg); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 0.625rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Terdaftar Sejak</div>
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">{{ $student->created_at->translatedFormat('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calculation Card -->
            <div class="card" style="padding: 1.5rem; border: 1px solid var(--border-light);">
                <h4 style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem;">
                    <i data-lucide="calculator" style="width: 18px; height: 18px; color: var(--primary);"></i> Ringkasan Poin
                </h4>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <span style="width: 10px; height: 10px; border-radius: 4px; background: #ef4444;"></span>
                            <span style="font-size: 0.8125rem; font-weight: 700; color: var(--text-secondary);">Total Penyakit</span>
                        </div>
                        <span style="font-weight: 800; color: #ef4444;">+{{ $totalViolation }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <span style="width: 10px; height: 10px; border-radius: 4px; background: #10b981;"></span>
                            <span style="font-size: 0.8125rem; font-weight: 700; color: var(--text-secondary);">Total Vitamin</span>
                        </div>
                        <span style="font-weight: 800; color: #10b981;">-{{ $totalVitamin }}</span>
                    </div>
                    <div style="height: 1px; background: var(--border-light); margin: 0.25rem 0;"></div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">Poin Bersih</span>
                        <span style="font-size: 1.25rem; font-weight: 900; color: {{ $pc }};">{{ $netPoints }}</span>
                    </div>
                </div>

                <div style="margin-top: 1.5rem;">
                    <div style="height: 10px; background: #f1f5f9; border-radius: 10px; overflow: hidden; border: 1px solid var(--border-light);">
                        <div style="width: {{ min(($netPoints/150)*100, 100) }}%; height: 100%; background: {{ $pc }}; border-radius: 10px; transition: width 1s cubic-bezier(0.34, 1.56, 0.64, 1);"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 0.6rem; font-size: 0.625rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">
                        <span>Aman</span><span>Waspada</span><span>Kritis</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: History -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Violation Records -->
            <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-light);">
                <div style="padding: 1.25rem 1.5rem; background: linear-gradient(to right, #fef2f2, #ffffff); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; color: #991b1b;">
                        <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
                        Riwayat Penyakit (Pelanggaran)
                    </h3>
                    <span style="background: #fee2e2; color: #ef4444; padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.75rem; font-weight: 800;">{{ $violationRecords->count() }} Kejadian</span>
                </div>
                
                <div style="display: flex; flex-direction: column;">
                    @forelse($violationRecords->sortByDesc('date') as $record)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.background='#fffafa'" onmouseout="this.style.background='transparent'">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(239, 68, 68, 0.1);">
                                <i data-lucide="shield-alert" style="width: 20px; height: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.9375rem; color: #1e293b;">{{ $record->violationType->name ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">
                                    {{ \Carbon\Carbon::parse($record->date)->translatedFormat('d F Y') }} · <span style="color: var(--primary);">{{ $record->user->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 900; color: #ef4444; font-size: 1.125rem;">+{{ $record->violationType->points ?? 0 }}</div>
                            <div style="font-size: 0.625rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Poin</div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                        <i data-lucide="check-circle-2" style="width: 48px; height: 48px; opacity: 0.1; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 700; font-size: 0.875rem;">Siswa berkelakuan sangat baik. Belum ada catatan penyakit.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Vitamin Records -->
            <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-light);">
                <div style="padding: 1.25rem 1.5rem; background: linear-gradient(to right, #ecfdf5, #ffffff); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; color: #065f46;">
                        <i data-lucide="sparkles" style="width: 20px; height: 20px;"></i>
                        Riwayat Vitamin (Prestasi)
                    </h3>
                    <span style="background: #d1fae5; color: #10b981; padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.75rem; font-weight: 800;">{{ $vitaminRecords->count() }} Penghargaan</span>
                </div>
                
                <div style="display: flex; flex-direction: column;">
                    @forelse($vitaminRecords->sortByDesc('date') as $record)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='transparent'">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(16, 185, 129, 0.1);">
                                <i data-lucide="trophy" style="width: 20px; height: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.9375rem; color: #1e293b;">{{ $record->vitaminType->name ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">
                                    {{ \Carbon\Carbon::parse($record->date)->translatedFormat('d F Y') }} · <span style="color: var(--primary);">{{ $record->user->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 900; color: #10b981; font-size: 1.125rem;">-{{ $record->vitaminType->points ?? 0 }}</div>
                            <div style="font-size: 0.625rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Poin</div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                        <i data-lucide="award" style="width: 48px; height: 48px; opacity: 0.1; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 700; font-size: 0.875rem;">Belum ada catatan vitamin/prestasi untuk siswa ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Re-use the Edit Student Modal Logic from index --}}
    @include('students.modals.edit')

    @push('scripts')
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endpush
</x-app-layout>

