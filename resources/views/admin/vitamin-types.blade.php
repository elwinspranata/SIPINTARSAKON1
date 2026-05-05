<x-app-layout>
    @section('header_title', 'Kelola Kategori Vitamin')
    @section('header_subtitle', 'Tambah, edit, dan hapus kategori vitamin (prestasi) sesuai kebutuhan sekolah.')

    @section('header_actions')
    <button onclick="document.getElementById('addModal').style.display='flex'" class="btn" style="padding: 0.6rem 1.25rem; background: var(--success); color: white; box-shadow: 0 8px 16px -4px rgba(0, 210, 106, 0.3);">
        <i data-lucide="plus-circle" size="18"></i>
        <span>Tambah Vitamin</span>
    </button>
    @endsection

    {{-- Error Message --}}
    @if(session('error'))
    <div style="background: var(--danger-light); color: var(--danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 700; font-size: 0.875rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid rgba(239,68,68,0.1); animation: fadeInUp 0.4s ease-out;">
        <i data-lucide="alert-triangle" size="18"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Stats Summary --}}
    @php
        $allVitamins = App\Models\VitaminType::all();
        $categories = ['Akademik', 'Non-Akademik', 'Sosial'];
        $catColors = [
            'Akademik' => '#6366f1',
            'Non-Akademik' => '#f59e0b', 
            'Sosial' => '#10b981'
        ];
        $catBg = [
            'Akademik' => 'rgba(99, 102, 241, 0.08)',
            'Non-Akademik' => 'rgba(245, 158, 11, 0.08)',
            'Sosial' => 'rgba(16, 185, 129, 0.08)'
        ];
        $catIcons = [
            'Akademik' => 'graduation-cap',
            'Non-Akademik' => 'trophy',
            'Sosial' => 'heart-handshake'
        ];
    @endphp

    <div class="stats-grid" style="margin-bottom: 2rem;">
        @foreach($categories as $cat)
        <div class="card stats-card" style="border-top: 3px solid {{ $catColors[$cat] }};">
            <div class="stats-icon-wrapper" style="background: {{ $catBg[$cat] }}; color: {{ $catColors[$cat] }};">
                <i data-lucide="{{ $catIcons[$cat] }}" size="22"></i>
            </div>
            <div class="stats-value">{{ $allVitamins->where('category', $cat)->count() }}</div>
            <div class="stats-label">{{ $cat }}</div>
        </div>
        @endforeach
    </div>

    {{-- Main Content --}}
    <div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.25rem;">
        @foreach($vitamins as $category => $types)
        @php
            $color = $catColors[$category] ?? 'var(--success)';
            $bgLight = $catBg[$category] ?? 'rgba(16, 185, 129, 0.08)';
        @endphp
        <div class="card" style="padding: 0; overflow: hidden; border-top: 4px solid {{ $color }};">
            <div style="padding: 1.25rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; color: {{ $color }}; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="{{ $catIcons[$category] ?? 'star' }}" size="16"></i>
                    {{ $category }}
                </h3>
                <span class="status-badge" style="background: {{ $bgLight }}; color: {{ $color }};">{{ count($types) }} Jenis</span>
            </div>
            <div style="display: flex; flex-direction: column;">
                @foreach($types as $type)
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; transition: background 200ms;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                    <div style="flex: 1; min-width: 0; padding-right: 1rem;">
                        <div style="font-weight: 600; font-size: 0.8125rem; color: var(--text);">{{ $type->name }}</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="text-align: right;">
                            <div style="font-size: 1.125rem; font-weight: 800; color: {{ $color }};">+{{ $type->points }}</div>
                            <div style="font-size: 0.625rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Poin</div>
                        </div>
                        <div style="display: flex; gap: 0.25rem;">
                            <button onclick="openEditModal({{ $type->id }}, '{{ addslashes($type->name) }}', '{{ $type->category }}', {{ $type->points }})" style="background: rgba(99, 102, 241, 0.08); color: #6366f1; border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#6366f1';this.style.color='white'" onmouseout="this.style.background='rgba(99, 102, 241, 0.08)';this.style.color='#6366f1'">
                                <i data-lucide="pencil" size="14"></i>
                            </button>
                            <form action="{{ route('admin.vitamin-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus vitamin ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: var(--danger-light); color: var(--danger); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--danger)';this.style.color='white'" onmouseout="this.style.background='var(--danger-light)';this.style.color='var(--danger)'">
                                    <i data-lucide="trash-2" size="14"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Add Modal --}}
    <div id="addModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); padding: 1rem;" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 100%; max-width: 480px; padding: 0; overflow: hidden; animation: modalIn 0.3s ease-out;">
            <div style="padding: 1.5rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="plus-circle" size="18" style="color: var(--success);"></i>
                    Tambah Kategori Vitamin
                </h3>
                <button onclick="document.getElementById('addModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <i data-lucide="x" size="18"></i>
                </button>
            </div>
            <form action="{{ route('admin.vitamin-types.store') }}" method="POST" style="padding: 1.5rem;">
                @csrf
                <div class="form-group">
                    <label class="label">Nama Vitamin <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="input" placeholder="Contoh: Juara lomba olahraga" required>
                </div>
                <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                    <div class="form-group">
                        <label class="label">Kategori <span style="color: var(--danger);">*</span></label>
                        <select name="category" class="select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Non-Akademik">Non-Akademik</option>
                            <option value="Sosial">Sosial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Poin <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="points" class="input" min="1" max="200" placeholder="10" required>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn" style="flex: 2; background: var(--success); color: white;">
                        <i data-lucide="save" size="15"></i> Simpan
                    </button>
                    <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn btn-outline" style="flex: 1;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); padding: 1rem;" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 100%; max-width: 480px; padding: 0; overflow: hidden; animation: modalIn 0.3s ease-out;">
            <div style="padding: 1.5rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="pencil" size="18" style="color: var(--secondary);"></i>
                    Edit Kategori Vitamin
                </h3>
                <button onclick="document.getElementById('editModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <i data-lucide="x" size="18"></i>
                </button>
            </div>
            <form id="editForm" method="POST" style="padding: 1.5rem;">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="label">Nama Vitamin <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="editName" class="input" required>
                </div>
                <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                    <div class="form-group">
                        <label class="label">Kategori <span style="color: var(--danger);">*</span></label>
                        <select name="category" id="editCategory" class="select" required>
                            <option value="Akademik">Akademik</option>
                            <option value="Non-Akademik">Non-Akademik</option>
                            <option value="Sosial">Sosial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Poin <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="points" id="editPoints" class="input" min="1" max="200" required>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn" style="flex: 2; background: var(--secondary); color: white;">
                        <i data-lucide="check" size="15"></i> Perbarui
                    </button>
                    <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn btn-outline" style="flex: 1;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <style>
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        function openEditModal(id, name, category, points) {
            document.getElementById('editForm').action = '/admin/vitamin-types/' + id;
            document.getElementById('editName').value = name;
            document.getElementById('editCategory').value = category;
            document.getElementById('editPoints').value = points;
            document.getElementById('editModal').style.display = 'flex';
            lucide.createIcons();
        }
    </script>
    @endpush
</x-app-layout>
