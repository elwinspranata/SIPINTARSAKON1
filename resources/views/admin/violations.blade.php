<x-app-layout>
    @section('header_title', 'Master Pelanggaran')
    @section('header_subtitle', 'Konfigurasi poin dan kategori pelanggaran sekolah.')

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.25rem;">
        @foreach($violations as $category => $types)
        @php
            $color = match($category) {
                'Berat' => 'var(--danger)',
                'Sedang' => 'var(--warning)',
                'Ringan' => 'var(--info)',
                default => 'var(--primary)'
            };
            $bgLight = match($category) {
                'Berat' => 'var(--danger-light)',
                'Sedang' => 'var(--warning-light)',
                'Ringan' => 'var(--info-light)',
                default => 'var(--primary-light)'
            };
        @endphp
        <div class="card" style="padding: 0; overflow: hidden; border-top: 4px solid {{ $color }};">
            <div style="padding: 1.25rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; color: {{ $color }};">{{ $category }}</h3>
                <span class="status-badge" style="background: {{ $bgLight }}; color: {{ $color }};">{{ count($types) }} Jenis</span>
            </div>
            <div style="display: flex; flex-direction: column;">
                @foreach($types as $type)
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; transition: background 200ms;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                    <div style="flex: 1; min-width: 0; padding-right: 1rem;">
                        <div style="font-weight: 600; font-size: 0.8125rem; color: var(--text);">{{ $type->name }}</div>
                        <div style="font-size: 0.6875rem; color: var(--text-muted); margin-top: 0.125rem;">{{ $type->sub_category }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 1.125rem; font-weight: 800; color: {{ $color }};">{{ $type->points }}</div>
                        <div style="font-size: 0.625rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Poin</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>
