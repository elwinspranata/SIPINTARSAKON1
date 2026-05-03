<x-app-layout>
    @section('header_title', 'Laporan & Riwayat')
    @section('header_subtitle', 'Daftar catatan pelanggaran dan vitamin siswa beserta riwayat guru pencatat.')

    <!-- Violation Records (Penyakit) -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 1.5rem;">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 0.9375rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="alert-triangle" size="16" style="color: var(--danger);"></i> Riwayat Penyakit (Pelanggaran)
            </h3>
            <span class="status-badge" style="background: var(--danger-light); color: var(--danger);">{{ $violation_records->total() }} catatan</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Pelanggaran</th>
                        <th>Poin</th>
                        <th>Dicatat Oleh</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($violation_records as $r)
                    <tr>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
                        <td style="font-weight: 600;">{{ $r->student->name ?? '-' }}</td>
                        <td>
                            <div style="font-weight: 500; font-size: 0.8125rem;">{{ $r->violationType->name ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: var(--text-muted);">{{ $r->violationType->category ?? '' }}</div>
                        </td>
                        <td style="font-weight: 700; color: var(--danger);">+{{ $r->violationType->points ?? 0 }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 24px; height: 24px; border-radius: 6px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($r->user->name ?? 'U') }}&background=2B5EA7&color=fff&bold=true&size=48'); background-size: cover; flex-shrink: 0;"></div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary);">{{ $r->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <a href="{{ route('records.edit', $r) }}" class="btn btn-outline" style="padding: 0.2rem 0.4rem; font-size: 0;"><i data-lucide="pencil" size="13"></i></a>
                                <form method="POST" action="{{ route('records.destroy', $r) }}" onsubmit="return confirm('Hapus?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="padding: 0.2rem 0.4rem; font-size: 0; color: var(--danger);"><i data-lucide="trash-2" size="13"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada catatan penyakit.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($violation_records->hasPages())
        <div style="padding: 0.75rem; border-top: 1px solid var(--border);">{{ $violation_records->links() }}</div>
        @endif
    </div>

    <!-- Vitamin Records (Prestasi) -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 0.9375rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="sparkles" size="16" style="color: var(--success);"></i> Riwayat Vitamin (Prestasi)
            </h3>
            <span class="status-badge" style="background: var(--success-light); color: var(--success);">{{ $vitamin_records->total() }} catatan</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Vitamin</th>
                        <th>Poin</th>
                        <th>Dicatat Oleh</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vitamin_records as $r)
                    <tr>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
                        <td style="font-weight: 600;">{{ $r->student->name ?? '-' }}</td>
                        <td>
                            <div style="font-weight: 500; font-size: 0.8125rem;">{{ $r->vitaminType->name ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: var(--text-muted);">{{ $r->vitaminType->category ?? '' }}</div>
                        </td>
                        <td style="font-weight: 700; color: var(--success);">-{{ $r->vitaminType->points ?? 0 }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 24px; height: 24px; border-radius: 6px; background-image: url('https://ui-avatars.com/api/?name={{ urlencode($r->user->name ?? 'U') }}&background=00D26A&color=fff&bold=true&size=48'); background-size: cover; flex-shrink: 0;"></div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary);">{{ $r->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <a href="{{ route('records.edit', $r) }}" class="btn btn-outline" style="padding: 0.2rem 0.4rem; font-size: 0;"><i data-lucide="pencil" size="13"></i></a>
                                <form method="POST" action="{{ route('records.destroy', $r) }}" onsubmit="return confirm('Hapus?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="padding: 0.2rem 0.4rem; font-size: 0; color: var(--danger);"><i data-lucide="trash-2" size="13"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada catatan vitamin.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vitamin_records->hasPages())
        <div style="padding: 0.75rem; border-top: 1px solid var(--border);">{{ $vitamin_records->links() }}</div>
        @endif
    </div>
</x-app-layout>
