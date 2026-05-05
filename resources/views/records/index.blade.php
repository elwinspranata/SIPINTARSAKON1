<x-app-layout>
    @section('header_title', 'Laporan & Riwayat')
    @section('header_subtitle', 'Monitoring catatan pelanggaran (Penyakit) dan prestasi (Vitamin) seluruh siswa.')

    <!-- Violation Records (Penyakit) -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 2rem; border: 1px solid var(--border-light); animation: fadeInUp 0.4s ease-out;">
        <div style="padding: 1.25rem 1.5rem; background: linear-gradient(to right, #fef2f2, #ffffff); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; color: #991b1b;">
                <i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i>
                Riwayat Penyakit (Pelanggaran)
            </h3>
            <span style="background: #fee2e2; color: #ef4444; padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.75rem; font-weight: 800;">{{ $violation_records->total() }} Catatan</span>
        </div>
        <div class="table-container">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Tanggal</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Siswa</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Pelanggaran</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Poin</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Guru Pencatat</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($violation_records as $r)
                    <tr class="premium-row">
                        <td style="padding: 1.25rem 1.5rem; font-size: 0.8125rem; font-weight: 700; color: var(--text-muted);">{{ \Carbon\Carbon::parse($r->date)->translatedFormat('d M Y') }}</td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="font-weight: 800; color: var(--primary-dark); font-size: 0.9375rem;">{{ $r->student->name ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: var(--text-muted); font-weight: 600;">Kelas {{ $r->student->schoolClass->name ?? '-' }}</div>
                        </td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="font-weight: 800; font-size: 0.875rem; color: #1e293b;">{{ $r->violationType->name ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: #ef4444; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">{{ $r->violationType->category ?? '' }}</div>
                        </td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <span style="font-weight: 900; color: #ef4444; font-size: 1.125rem;">+{{ $r->violationType->points ?? 0 }}</span>
                        </td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 32px; height: 32px; border-radius: 10px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($r->user->name ?? 'U') }}&background=2B5EA7&color=fff&bold=true&size=64'); background-size: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);"></div>
                                <span style="font-size: 0.8125rem; font-weight: 700; color: var(--primary-dark);">{{ $r->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('records.edit', $r) }}" class="btn" style="background: #fef3c7; color: #d97706; width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#d97706';this.style.color='white'" onmouseout="this.style.background='#fef3c7';this.style.color='#d97706'">
                                    <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                </a>
                                <form method="POST" action="{{ route('records.destroy', $r) }}" onsubmit="return confirm('Hapus catatan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn" style="background: #fee2e2; color: #ef4444; width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#ef4444';this.style.color='white'" onmouseout="this.style.background='#fee2e2';this.style.color='#ef4444'">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                            <i data-lucide="shield-check" style="width: 48px; height: 48px; opacity: 0.1; margin-bottom: 1rem;"></i>
                            <p style="font-weight: 700;">Belum ada catatan penyakit terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($violation_records->hasPages())
        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border-light); background: #f8fafc;">
            {{ $violation_records->links() }}
        </div>
        @endif
    </div>

    <!-- Vitamin Records (Prestasi) -->
    <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-light); animation: fadeInUp 0.5s ease-out;">
        <div style="padding: 1.25rem 1.5rem; background: linear-gradient(to right, #ecfdf5, #ffffff); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; color: #065f46;">
                <i data-lucide="sparkles" style="width: 20px; height: 20px;"></i>
                Riwayat Vitamin (Prestasi)
            </h3>
            <span style="background: #d1fae5; color: #10b981; padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.75rem; font-weight: 800;">{{ $vitamin_records->total() }} Catatan</span>
        </div>
        <div class="table-container">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Tanggal</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Siswa</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Vitamin</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Poin</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light);">Guru Pencatat</th>
                        <th style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vitamin_records as $r)
                    <tr class="premium-row">
                        <td style="padding: 1.25rem 1.5rem; font-size: 0.8125rem; font-weight: 700; color: var(--text-muted);">{{ \Carbon\Carbon::parse($r->date)->translatedFormat('d M Y') }}</td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="font-weight: 800; color: var(--primary-dark); font-size: 0.9375rem;">{{ $r->student->name ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: var(--text-muted); font-weight: 600;">Kelas {{ $r->student->schoolClass->name ?? '-' }}</div>
                        </td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="font-weight: 800; font-size: 0.875rem; color: #1e293b;">{{ $r->vitaminType->name ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: #10b981; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">{{ $r->vitaminType->category ?? '' }}</div>
                        </td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <span style="font-weight: 900; color: #10b981; font-size: 1.125rem;">-{{ $r->vitaminType->points ?? 0 }}</span>
                        </td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 32px; height: 32px; border-radius: 10px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($r->user->name ?? 'U') }}&background=10b981&color=fff&bold=true&size=64'); background-size: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);"></div>
                                <span style="font-size: 0.8125rem; font-weight: 700; color: var(--primary-dark);">{{ $r->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('records.edit', $r) }}" class="btn" style="background: #fef3c7; color: #d97706; width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#d97706';this.style.color='white'" onmouseout="this.style.background='#fef3c7';this.style.color='#d97706'">
                                    <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                </a>
                                <form method="POST" action="{{ route('records.destroy', $r) }}" onsubmit="return confirm('Hapus catatan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn" style="background: #fee2e2; color: #ef4444; width: 32px; height: 32px; border-radius: 10px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#ef4444';this.style.color='white'" onmouseout="this.style.background='#fee2e2';this.style.color='#ef4444'">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                            <i data-lucide="award" style="width: 48px; height: 48px; opacity: 0.1; margin-bottom: 1rem;"></i>
                            <p style="font-weight: 700;">Belum ada catatan vitamin terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vitamin_records->hasPages())
        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border-light); background: #f8fafc;">
            {{ $vitamin_records->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .premium-row { transition: all 0.2s; }
        .premium-row:hover { background: #f8fafc; box-shadow: inset 4px 0 0 var(--primary); }
    </style>
    @endpush
</x-app-layout>

