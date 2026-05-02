<x-app-layout>
    @section('header_title', 'Laporan & Riwayat')
    @section('header_subtitle', 'Daftar semua catatan pelanggaran dan kesehatan siswa.')

    <!-- Behavior Records -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 1rem;">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 0.9375rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="alert-triangle" size="16" style="color: var(--danger);"></i> Riwayat Pelanggaran
            </h3>
            <span class="status-badge" style="background: var(--danger-light); color: var(--danger);">{{ $behavior_records->total() }} catatan</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Pelanggaran</th>
                        <th>Poin</th>
                        <th>Petugas</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($behavior_records as $r)
                    <tr>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
                        <td style="font-weight: 600;">{{ $r->student->name ?? '-' }}</td>
                        <td>
                            <div style="font-weight: 500; font-size: 0.8125rem;">{{ $r->violationType->name ?? '-' }}</div>
                            <div style="font-size: 0.6875rem; color: var(--text-muted);">{{ $r->violationType->category ?? '' }}</div>
                        </td>
                        <td style="font-weight: 700; color: var(--danger);">{{ $r->violationType->points ?? 0 }}</td>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ $r->user->name ?? '-' }}</td>
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
                    <tr><td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada catatan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($behavior_records->hasPages())
        <div style="padding: 0.75rem; border-top: 1px solid var(--border);">{{ $behavior_records->links() }}</div>
        @endif
    </div>

    <!-- Health Records -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 0.9375rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="heart-pulse" size="16" style="color: var(--info);"></i> Riwayat Kesehatan
            </h3>
            <span class="status-badge" style="background: var(--info-light); color: var(--info);">{{ $health_records->total() }} catatan</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Diagnosa</th>
                        <th>Vital Signs</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($health_records as $h)
                    <tr>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($h->date)->format('d/m/Y') }}</td>
                        <td style="font-weight: 600;">{{ $h->student->name ?? '-' }}</td>
                        <td>{{ $h->health_problem ?? '-' }}</td>
                        <td style="font-size: 0.75rem;">
                            @if($h->temperature) <span>{{ $h->temperature }}°C</span> @endif
                            @if($h->blood_pressure) <span style="margin-left: 0.25rem;">{{ $h->blood_pressure }}</span> @endif
                            @if(!$h->temperature && !$h->blood_pressure) - @endif
                        </td>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ $h->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada catatan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($health_records->hasPages())
        <div style="padding: 0.75rem; border-top: 1px solid var(--border);">{{ $health_records->links() }}</div>
        @endif
    </div>
</x-app-layout>
