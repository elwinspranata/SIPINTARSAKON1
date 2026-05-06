<x-app-layout>
    @section('header_title', $student->name)
    @section('header_subtitle', 'Detail profil akademik dan rekam jejak kedisiplinan siswa.')
    @section('header_actions')
        <a href="{{ route('students.letter', $student) }}" target="_blank" class="btn"
            style="background: #1B6B3A; color: white; box-shadow: 0 8px 16px -4px rgba(27,107,58,0.3); border-radius: 12px;">
            <i data-lucide="file-text" style="width:14px;height:14px;"></i> Download Surat
        </a>
        <button
            onclick="openEditStudentModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->nisn }}', '{{ $student->class_id }}', '{{ $student->gender }}')"
            class="btn btn-outline" style="border-radius: 12px; border-color: #d97706; color: #d97706;">
            <i data-lucide="pencil" style="width: 14px; height: 14px;"></i> Edit Profil
        </button>
        <a href="{{ route('students.index') }}" class="btn btn-primary" style="border-radius: 12px;">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i> Kembali
        </a>
    @endsection

    @php
        $violationRecords = $student->behaviorRecords->whereNotNull('violation_type_id');
        $vitaminRecords = $student->behaviorRecords->whereNotNull('vitamin_type_id');
        $totalViolation = $student->violation_points;
        $totalVitamin = $student->vitamin_points;
        $netPoints = $student->net_points;
        $status = $student->point_status;
        $st = $status['label'];
        $pc = $status['color'];
        $bgc = $status['bg'];
    @endphp

    <div class="profile-grid">
        <!-- Left Column: Profile & Summary -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Profile Card -->
            <div class="card" style="text-align: center; border: 1px solid var(--border-light); padding: 2rem 1.5rem;">
                <div style="position: relative; display: inline-block; margin-bottom: 1.5rem;">
                    <div
                        style="width: 100px; height: 100px; border-radius: 30px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random&color=fff&size=200&bold=true'); background-size: cover; border: 4px solid #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                    </div>
                    <div
                        style="position: absolute; bottom: 0; right: 0; width: 28px; height: 28px; background: {{ $student->gender == 'L' ? '#0ea5e9' : '#db2777' }}; border: 3px solid #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i data-lucide="{{ $student->gender == 'L' ? 'user' : 'user-round' }}"
                            style="width: 12px; height: 12px;"></i>
                    </div>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 0.25rem;">
                    {{ $student->name }}
                </h3>
                <p style="color: var(--text-muted); font-size: 0.8125rem; font-weight: 600; margin-bottom: 2rem;">NISN:
                    {{ $student->nisn ?? '---' }}
                </p>

                <div
                    style="background: {{ $bgc }}; padding: 1.5rem; border-radius: 20px; margin-bottom: 1.5rem; border: 1px solid rgba(0,0,0,0.02); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 0.5rem;">
                    <div
                        style="font-size: 2.5rem; font-weight: 900; color: {{ $pc }}; line-height: 1; letter-spacing: -0.04em;">
                        {{ $netPoints > 0 ? '+' : '' }}{{ $netPoints }}
                    </div>
                    <div
                        style="font-size: 0.75rem; font-weight: 800; color: {{ $pc }}; text-transform: uppercase; letter-spacing: 0.05em;">
                        Status: {{ $st }}</div>
                </div>

                <div style="text-align: left; display: flex; flex-direction: column; gap: 1rem; padding: 0 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 10px; background: var(--bg); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="school" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div
                                style="font-size: 0.625rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">
                                Kelas Sekarang</div>
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">
                                {{ $student->schoolClass->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 10px; background: var(--bg); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div
                                style="font-size: 0.625rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">
                                Terdaftar Sejak</div>
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">
                                {{ $student->created_at->translatedFormat('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calculation Card -->
            <div class="card" style="padding: 1.5rem; border: 1px solid var(--border-light);">
                <h4
                    style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem;">
                    <i data-lucide="calculator" style="width: 18px; height: 18px; color: var(--primary);"></i> Ringkasan
                    Poin
                </h4>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <span style="width: 10px; height: 10px; border-radius: 4px; background: #ef4444;"></span>
                            <span style="font-size: 0.8125rem; font-weight: 700; color: var(--text-secondary);">Total
                                Penyakit</span>
                        </div>
                        <span style="font-weight: 800; color: #ef4444;">-{{ $totalViolation }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <span style="width: 10px; height: 10px; border-radius: 4px; background: #10b981;"></span>
                            <span style="font-size: 0.8125rem; font-weight: 700; color: var(--text-secondary);">Total
                                Vitamin</span>
                        </div>
                        <span style="font-weight: 800; color: #10b981;">+{{ $totalVitamin }}</span>
                    </div>
                    <div style="height: 1px; background: var(--border-light); margin: 0.25rem 0;"></div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">Poin
                            Bersih</span>
                        <span
                            style="font-size: 1.25rem; font-weight: 900; color: {{ $pc }};">{{ $netPoints > 0 ? '+' : '' }}{{ $netPoints }}</span>
                    </div>
                </div>

                @php
                    $chartValue = max(0, min(-$netPoints, 150));
                    $chartPercent = $chartValue / 150 * 100;
                    $markerTransform = $chartPercent == 0
                        ? 'translateY(-50%)'
                        : ($chartPercent == 100 ? 'translate(-100%, -50%)' : 'translate(-50%, -50%)');
                    $fillColor = $netPoints > 0 ? 'rgba(16,185,129,0.16)' : 'rgba(239,68,68,0.14)';
                    $markerLabel = $netPoints > 0 ? 'SEHAT' : $status['label'];
                @endphp
                <div style="margin-top: 1.5rem;">
                    <div
                        style="position: relative; height: 18px; background: #f8fafc; border-radius: 9999px; overflow: hidden; border: 1px solid rgba(148,163,184,0.22);">
                        <div
                            style="position: absolute; inset: 0; width: {{ $chartPercent }}%; background: {{ $fillColor }}; transition: width 0.35s ease;">
                        </div>
                        <div
                            style="position: absolute; left: {{ $chartPercent }}%; top: -24px; transform: {{ $markerTransform }}; white-space: nowrap; font-size: 0.65rem; font-weight: 800; color: #0f172a; letter-spacing: 0.02em; background: rgba(255,255,255,0.92); padding: 0.15rem 0.55rem; border-radius: 9999px; border: 1px solid rgba(148,163,184,0.22); box-shadow: 0 10px 24px rgba(15,23,42,0.06); z-index: 30;">
                            {{ $markerLabel }}
                        </div>
                        <div
                            style="position: absolute; left: {{ $chartPercent }}%; top: 50%; transform: {{ $markerTransform }}; width: 18px; height: 18px; border-radius: 50%; background: #fff; border: 3px solid {{ $pc }}; box-shadow: 0 8px 18px rgba(15,23,42,0.12); z-index: 20;">
                        </div>
                        <div
                            style="position: absolute; left: {{ $chartPercent }}%; top: 50%; transform: {{ $markerTransform }}; width: 2px; height: 32px; background: rgba(15,23,42,0.12); z-index: 15;">
                        </div>
                    </div>
                    <div
                        style="display: grid; grid-template-columns: 25% 25% 25% 25%; margin-top: 0.85rem; font-size: 0.75rem; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.05em;">
                        <div style="text-align: center;">Aman</div>
                        <div style="text-align: center;">Baik</div>
                        <div style="text-align: center;">Waspada</div>
                        <div style="text-align: center;">Kritis</div>
                    </div>
                </div>
            </div>

            <!-- Discipline Indicator -->
            <div class="card" style="padding: 1.5rem; border: 1px solid var(--border-light);">
                <h4
                    style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem;">
                    <i data-lucide="trending-up" style="width: 18px; height: 18px; color: var(--primary);"></i>
                    Indikator Kedisiplinan
                </h4>
                <div style="background: #f8fafc; border-radius: 24px; padding: 1rem; display: grid; gap: 0.75rem;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1rem; border-radius: 18px; background: #ffffff; border: 1px solid rgba(148,163,184,0.16);">
                        <div style="min-width: 0;">
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">0 - 20 Poin
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Aman dan disiplin.</div>
                        </div>
                        <span
                            style="font-size: 0.75rem; font-weight: 800; color: #10b981; white-space: nowrap;">Aman</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1rem; border-radius: 18px; background: #ffffff; border: 1px solid rgba(148,163,184,0.16);">
                        <div style="min-width: 0;">
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">21 - 50 Poin
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Masih baik, perlu pemantauan.
                            </div>
                        </div>
                        <span
                            style="font-size: 0.75rem; font-weight: 800; color: #2563eb; white-space: nowrap;">Baik</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1rem; border-radius: 18px; background: #ffffff; border: 1px solid rgba(148,163,184,0.16);">
                        <div style="min-width: 0;">
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">51 - 100
                                Poin</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Waspada, perlu evaluasi.
                            </div>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 800; color: #f59e0b; white-space: nowrap;">Waspada
                            (SP1)</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1rem; border-radius: 18px; background: #ffffff; border: 1px solid rgba(148,163,184,0.16);">
                        <div style="min-width: 0;">
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--primary-dark);">&gt; 100
                                Poin</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Kritis, perlu tindakan.
                            </div>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 800; color: #dc2626; white-space: nowrap;">Kritis
                            (SP3)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: History -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Violation Records -->
            <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-light);">
                <div
                    style="padding: 1.25rem 1.5rem; background: linear-gradient(to right, #fef2f2, #ffffff); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                    <h3
                        style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; color: #991b1b;">
                        <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
                        Riwayat Penyakit (Pelanggaran)
                    </h3>
                    <span
                        style="background: #fee2e2; color: #ef4444; padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.75rem; font-weight: 800;">{{ $violationRecords->count() }}
                        Kejadian</span>
                </div>

                <div style="display: flex; flex-direction: column;">
                    @forelse($violationRecords->sortByDesc('date') as $record)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: all 0.2s;"
                            onmouseover="this.style.background='#fffafa'" onmouseout="this.style.background='transparent'">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div
                                    style="width: 44px; height: 44px; border-radius: 12px; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(239, 68, 68, 0.1);">
                                    <i data-lucide="shield-alert" style="width: 20px; height: 20px;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 800; font-size: 0.9375rem; color: #1e293b;">
                                        {{ $record->violationType->name ?? '-' }}
                                    </div>
                                    <div
                                        style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">
                                        {{ \Carbon\Carbon::parse($record->date)->translatedFormat('d F Y') }} · <span
                                            style="color: var(--primary);">{{ $record->user->name ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 900; color: #ef4444; font-size: 1.125rem;">
                                    -{{ $record->violationType->points ?? 0 }}</div>
                                <div
                                    style="font-size: 0.625rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">
                                    Poin</div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                            <i data-lucide="check-circle-2"
                                style="width: 48px; height: 48px; opacity: 0.1; margin-bottom: 1rem;"></i>
                            <p style="font-weight: 700; font-size: 0.875rem;">Siswa berkelakuan sangat baik. Belum ada
                                catatan penyakit.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Vitamin Records -->
            <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-light);">
                <div
                    style="padding: 1.25rem 1.5rem; background: linear-gradient(to right, #ecfdf5, #ffffff); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                    <h3
                        style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; color: #065f46;">
                        <i data-lucide="sparkles" style="width: 20px; height: 20px;"></i>
                        Riwayat Vitamin (Prestasi)
                    </h3>
                    <span
                        style="background: #d1fae5; color: #10b981; padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.75rem; font-weight: 800;">{{ $vitaminRecords->count() }}
                        Penghargaan</span>
                </div>

                <div style="display: flex; flex-direction: column;">
                    @forelse($vitaminRecords->sortByDesc('date') as $record)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: all 0.2s;"
                            onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='transparent'">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div
                                    style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(16, 185, 129, 0.1);">
                                    <i data-lucide="trophy" style="width: 20px; height: 20px;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 800; font-size: 0.9375rem; color: #1e293b;">
                                        {{ $record->vitaminType->name ?? '-' }}
                                    </div>
                                    <div
                                        style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">
                                        {{ \Carbon\Carbon::parse($record->date)->translatedFormat('d F Y') }} · <span
                                            style="color: var(--primary);">{{ $record->user->name ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 900; color: #10b981; font-size: 1.125rem;">
                                    +{{ $record->vitaminType->points ?? 0 }}</div>
                                <div
                                    style="font-size: 0.625rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">
                                    Poin</div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                            <i data-lucide="award"
                                style="width: 48px; height: 48px; opacity: 0.1; margin-bottom: 1rem;"></i>
                            <p style="font-weight: 700; font-size: 0.875rem;">Belum ada catatan vitamin/prestasi untuk siswa
                                ini.</p>
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
                from {
                    opacity: 0;
                    transform: translateY(15px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush
</x-app-layout>