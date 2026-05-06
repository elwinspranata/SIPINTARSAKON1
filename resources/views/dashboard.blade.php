<x-app-layout>
    @section('header_title', 'Dashboard Utama')
    @section('header_subtitle', 'Selamat datang di Sistem Pembinaan Integritas Terpadu (SI PINTAR)')

    <!-- Stats Section -->
    <div class="stats-grid">
        <div class="card stats-card" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="users" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="users" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ number_format($stats['total_students'] ?? 0) }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Siswa Aktif</div>
        </div>

        <div class="card stats-card" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="stethoscope" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="stethoscope" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ $stats['total_violations'] ?? 0 }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Total Penyakit</div>
        </div>

        <div class="card stats-card" style="background: linear-gradient(135deg, #10b981, #047857); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="sparkles" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="sparkles" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ $stats['total_vitamins'] ?? 0 }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Total Vitamin</div>
        </div>

        <div class="card stats-card" style="background: linear-gradient(135deg, #f59e0b, #b45309); color: white; border: none;">
            <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                <i data-lucide="activity" style="width: 100px; height: 100px;"></i>
            </div>
            <div class="stats-icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                <i data-lucide="activity" style="width: 22px; height: 22px;"></i>
            </div>
            <div class="stats-value" style="color: white; margin-top: 0.5rem;">{{ ($stats['violations_today'] ?? 0) + ($stats['vitamins_today'] ?? 0) }}</div>
            <div class="stats-label" style="color: rgba(255,255,255,0.8);">Hari Ini</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="dashboard-grid" style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 1.5rem; align-items: start;">
        <!-- Chart Card -->
        <div class="card" style="min-height: 420px; display: flex; flex-direction: column; border: 1px solid var(--border-light); animation: fadeInUp 0.5s ease-out;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: var(--primary-dark); letter-spacing: -0.02em; display: flex; align-items: center; gap: 0.6rem;">
                        <i data-lucide="line-chart" style="width: 20px; height: 20px; color: var(--primary);"></i> Tren Perilaku Siswa
                    </h3>
                    <p style="font-size: 0.8125rem; color: var(--text-muted); margin-top: 2px; font-weight: 600;">Perbandingan vitamin vs penyakit (30 hari)</p>
                </div>
                <div style="display: gap: 1rem; padding: 0.5rem; background: #f8fafc; border-radius: 12px; border: 1px solid var(--border-light); display: flex;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary);">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span> Vitamin
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); margin-left: 1rem;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444;"></span> Penyakit
                    </div>
                </div>
            </div>
            <div style="flex: 1; width: 100%; position: relative;">
                <canvas id="behaviorChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="card" style="min-height: 420px; display: flex; flex-direction: column; border: 1px solid var(--border-light); animation: fadeInUp 0.6s ease-out;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.125rem; font-weight: 800; color: var(--primary-dark); letter-spacing: -0.02em; display: flex; align-items: center; gap: 0.6rem;">
                    <i data-lucide="clock" style="width: 20px; height: 20px; color: var(--primary);"></i> Aktivitas Terbaru
                </h3>
                <div style="display: flex; gap: 0.5rem;">
                
                    <a href="{{ route('records.index') }}" class="btn" style="background: var(--bg); color: var(--primary); padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 10px; font-weight: 800; border: 1px solid var(--border-light);">Riwayat</a>
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.6rem; flex: 1; overflow-y: auto;">
                @php $allRecent = collect()->merge($recent_violations)->merge($recent_vitamins)->sortByDesc('date')->take(8); @endphp
                @forelse($allRecent as $v)
                @php $isVitamin = !empty($v->vitamin_type_id); @endphp
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem; border-radius: 14px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background: #fff; border: 1px solid transparent; cursor: pointer;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='var(--border-light)'; this.style.transform='translateX(5px)'" onmouseout="this.style.background='#fff'; this.style.borderColor='transparent'; this.style.transform='translateX(0)'">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $isVitamin ? '#ecfdf5' : '#fef2f2' }}; color: {{ $isVitamin ? '#10b981' : '#ef4444' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                        <i data-lucide="{{ $isVitamin ? 'sparkles' : 'alert-triangle' }}" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 800; font-size: 0.875rem; color: var(--primary-dark); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $v->student->name ?? 'Siswa' }}</div>
                        <div style="font-size: 0.6875rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">
                            {{ $isVitamin ? ($v->vitaminType->name ?? 'Vitamin') : ($v->violationType->name ?? 'Pelanggaran') }} · {{ \Carbon\Carbon::parse($v->date)->diffForHumans() }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 0.75rem; font-weight: 900; color: {{ $isVitamin ? '#10b981' : '#ef4444' }}; background: {{ $isVitamin ? '#ecfdf5' : '#fef2f2' }}; padding: 0.35rem 0.65rem; border-radius: 10px; border: 1px solid rgba(0,0,0,0.02);">
                            {{ $isVitamin ? '-' : '+' }}{{ $isVitamin ? ($v->vitaminType->points ?? 0) : ($v->violationType->points ?? 0) }}
                        </span>
                    </div>
                </div>
                @empty
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; flex: 1; padding: 2rem; color: var(--text-muted); text-align: center;">
                    <div style="width: 80px; height: 80px; border-radius: 30px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <i data-lucide="clipboard-check" style="width: 36px; height: 36px; opacity: 0.2;"></i>
                    </div>
                    <div style="font-weight: 800; font-size: 1rem; color: var(--primary-dark);">Sistem Berjalan Normal</div>
                    <p style="font-size: 0.8125rem; margin-top: 6px; color: var(--text-muted);">Belum ada catatan aktivitas baru hari ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('behaviorChart').getContext('2d');
            
            const gradientVitamin = ctx.createLinearGradient(0, 0, 0, 300);
            gradientVitamin.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
            gradientVitamin.addColorStop(1, 'rgba(16, 185, 129, 0)');

            const gradientPenyakit = ctx.createLinearGradient(0, 0, 0, 300);
            gradientPenyakit.addColorStop(0, 'rgba(239, 68, 68, 0.15)');
            gradientPenyakit.addColorStop(1, 'rgba(239, 68, 68, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Vitamin',
                        data: [12, 19, 15, 25, 22, 30],
                        borderColor: '#10b981',
                        backgroundColor: gradientVitamin,
                        fill: true,
                        tension: 0.45,
                        borderWidth: 4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#10b981',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
                    }, {
                        label: 'Penyakit',
                        data: [8, 12, 10, 15, 12, 18],
                        borderColor: '#ef4444',
                        backgroundColor: gradientPenyakit,
                        fill: true,
                        tension: 0.45,
                        borderWidth: 4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#ef4444',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
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
                            titleColor: '#0f172a',
                            bodyColor: '#64748b',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 15,
                            boxPadding: 6,
                            usePointStyle: true,
                            titleFont: { size: 14, weight: '800', family: "'Plus Jakarta Sans'" },
                            bodyFont: { size: 12, weight: '600', family: "'Plus Jakarta Sans'" }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            grid: { color: '#f1f5f9', drawTicks: false },
                            ticks: { color: '#94a3b8', font: { size: 11, weight: '700' }, padding: 12 }
                        },
                        x: {
                            border: { display: false },
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 11, weight: '700' }, padding: 12 }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

