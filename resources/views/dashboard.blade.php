<x-app-layout>
    @section('header_title', 'Dashboard Utama')
    @section('header_subtitle', 'Selamat datang di Sistem Pembinaan Integritas Terpadu')

    <!-- Stats Section -->
    <div class="stats-grid">
        <div class="card stats-card">
            <div class="stats-icon-wrapper" style="background: var(--primary-light); color: var(--primary);">
                <i data-lucide="users"></i>
            </div>
            <div class="stats-label">Siswa Aktif</div>
            <div class="stats-value">{{ number_format($stats['total_students'] ?? 0) }}</div>
        </div>

        <div class="card stats-card">
            <div class="stats-icon-wrapper" style="background: var(--danger-light); color: var(--danger);">
                <i data-lucide="stethoscope"></i>
            </div>
            <div class="stats-label">Total Penyakit</div>
            <div class="stats-value">{{ $stats['total_violations'] ?? 0 }}</div>
        </div>

        <div class="card stats-card">
            <div class="stats-icon-wrapper" style="background: var(--success-light); color: var(--success);">
                <i data-lucide="heart-pulse"></i>
            </div>
            <div class="stats-label">Check-up Hari Ini</div>
            <div class="stats-value">{{ $stats['health_today'] ?? 0 }}</div>
        </div>

        <div class="card stats-card">
            <div class="stats-icon-wrapper" style="background: var(--warning-light); color: var(--warning);">
                <i data-lucide="activity"></i>
            </div>
            <div class="stats-label">Penyakit Baru</div>
            <div class="stats-value">{{ $stats['violations_today'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 1.5rem; align-items: start;">
        <!-- Chart Card -->
        <div class="card" style="min-height: 420px; display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: var(--text); letter-spacing: -0.02em;">Tren Perilaku Siswa</h3>
                    <p style="font-size: 0.8125rem; color: var(--text-muted); margin-top: 2px; font-weight: 500;">Perbandingan vitamin vs penyakit (30 hari)</p>
                </div>
                <div style="display: flex; gap: 1rem; padding: 0.5rem; background: #f8fafc; border-radius: 12px; border: 1px solid var(--border-light);">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary);">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--success);"></span> Vitamin
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary);">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--danger);"></span> Penyakit
                    </div>
                </div>
            </div>
            <div style="flex: 1; width: 100%; position: relative;">
                <canvas id="behaviorChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="card" style="min-height: 420px; display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.125rem; font-weight: 800; color: var(--text); letter-spacing: -0.02em;">Aktivitas Terbaru</h3>
                <a href="{{ route('records.index') }}" class="btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 10px; font-weight: 700;">Lihat Semua</a>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1; overflow-y: auto;">
                @forelse($recent_violations as $v)
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: 16px; transition: all 0.2s; background: #fff; border: 1px solid transparent;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='var(--border-light)'" onmouseout="this.style.background='#fff'; this.style.borderColor='transparent'">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: var(--danger-light); color: var(--danger); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: inset 0 0 0 1px rgba(255, 77, 77, 0.1);">
                        <i data-lucide="alert-triangle" size="20"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; font-size: 0.875rem; color: var(--text); margin-bottom: 2px;">{{ $v->student->name ?? 'Siswa' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">
                            {{ $v->violationType->name ?? 'Pelanggaran' }} · {{ \Carbon\Carbon::parse($v->date)->diffForHumans() }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 0.8125rem; font-weight: 800; color: var(--danger); background: var(--danger-light); padding: 0.25rem 0.5rem; border-radius: 8px;">
                            +{{ $v->violationType->points ?? 0 }}
                        </span>
                    </div>
                </div>
                @empty
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; flex: 1; padding: 2rem; color: var(--text-muted); text-align: center;">
                    <div style="width: 64px; height: 64px; border-radius: 20px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <i data-lucide="clipboard-check" size="32" style="opacity: 0.2;"></i>
                    </div>
                    <div style="font-weight: 700; font-size: 0.875rem; color: var(--text-secondary);">Belum ada rekam penyakit</div>
                    <p style="font-size: 0.75rem; margin-top: 4px;">Semua siswa dalam kondisi sehat walafiat.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('behaviorChart').getContext('2d');
            
            // Create gradients
            const gradientVitamin = ctx.createLinearGradient(0, 0, 0, 300);
            gradientVitamin.addColorStop(0, 'rgba(0, 210, 106, 0.1)');
            gradientVitamin.addColorStop(1, 'rgba(0, 210, 106, 0)');

            const gradientPenyakit = ctx.createLinearGradient(0, 0, 0, 300);
            gradientPenyakit.addColorStop(0, 'rgba(255, 77, 77, 0.1)');
            gradientPenyakit.addColorStop(1, 'rgba(255, 77, 77, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Vitamin',
                        data: [12, 19, 15, 25, 22, 30],
                        borderColor: '#00D26A',
                        backgroundColor: gradientVitamin,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#00D26A',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                    }, {
                        label: 'Penyakit',
                        data: [8, 12, 10, 15, 12, 18],
                        borderColor: '#FF4D4D',
                        backgroundColor: gradientPenyakit,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#FF4D4D',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1e293b',
                            bodyColor: '#64748b',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 4,
                            usePointStyle: true,
                            titleFont: { size: 13, weight: '700', family: "'Plus Jakarta Sans'" },
                            bodyFont: { size: 12, family: "'Plus Jakarta Sans'" }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            grid: { color: '#f1f5f9', drawTicks: false },
                            ticks: { color: '#94a3b8', font: { size: 11, weight: '600' }, padding: 10 }
                        },
                        x: {
                            border: { display: false },
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 11, weight: '600' }, padding: 10 }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
