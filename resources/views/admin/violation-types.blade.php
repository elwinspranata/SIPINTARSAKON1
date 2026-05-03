<x-app-layout>
    @section('header_title', 'Kelola Jenis Pelanggaran')
    @section('header_subtitle', 'Tambah, edit, dan hapus jenis pelanggaran (penyakit) sesuai kebutuhan sekolah.')

    @section('header_actions')
    <button onclick="document.getElementById('addModal').style.display='flex'" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">
        <i data-lucide="plus-circle" size="18"></i>
        <span>Tambah Pelanggaran</span>
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
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem;">
        @php
            $allViolations = App\Models\ViolationType::all();
            $categories = ['Ringan', 'Sedang', 'Berat'];
            $colors = ['Ringan' => 'var(--info)', 'Sedang' => 'var(--warning)', 'Berat' => 'var(--danger)'];
            $bgColors = ['Ringan' => 'var(--info-light)', 'Sedang' => 'var(--warning-light)', 'Berat' => 'var(--danger-light)'];
            $icons = ['Ringan' => 'alert-circle', 'Sedang' => 'alert-triangle', 'Berat' => 'shield-alert'];
        @endphp
        @foreach($categories as $cat)
        <div class="card stats-card" style="border-top: 3px solid {{ $colors[$cat] }};">
            <div class="stats-icon-wrapper" style="background: {{ $bgColors[$cat] }}; color: {{ $colors[$cat] }};">
                <i data-lucide="{{ $icons[$cat] }}" size="22"></i>
            </div>
            <div class="stats-value">{{ $allViolations->where('category', $cat)->count() }}</div>
            <div class="stats-label">{{ $cat }}</div>
        </div>
        @endforeach
    </div>

    {{-- Main Content --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.25rem;">
        @foreach($violations as $category => $types)
        @php
            $color = $colors[$category] ?? 'var(--primary)';
            $bgLight = $bgColors[$category] ?? 'var(--primary-light)';
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
                        @if($type->sub_category)
                        <div style="font-size: 0.6875rem; color: var(--text-muted); margin-top: 0.125rem;">{{ $type->sub_category }}</div>
                        @endif
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="text-align: right;">
                            <div style="font-size: 1.125rem; font-weight: 800; color: {{ $color }};">{{ $type->points }}</div>
                            <div style="font-size: 0.625rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Poin</div>
                        </div>
                        <div style="display: flex; gap: 0.25rem;">
                            <button onclick="openEditModal({{ $type->id }}, '{{ addslashes($type->name) }}', '{{ $type->category }}', '{{ addslashes($type->sub_category) }}', {{ $type->points }})" style="background: var(--primary-light); color: var(--primary); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--primary)';this.style.color='white'" onmouseout="this.style.background='var(--primary-light)';this.style.color='var(--primary)'">
                                <i data-lucide="pencil" size="14"></i>
                            </button>
                            <form action="{{ route('admin.violation-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelanggaran ini?')">
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
    <div id="addModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 480px; max-width: 90vw; padding: 0; overflow: hidden; animation: modalIn 0.3s ease-out;">
            <div style="padding: 1.5rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="plus-circle" size="18" style="color: var(--primary);"></i>
                    Tambah Jenis Pelanggaran
                </h3>
                <button onclick="document.getElementById('addModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <i data-lucide="x" size="18"></i>
                </button>
            </div>
            <form action="{{ route('admin.violation-types.store') }}" method="POST" style="padding: 1.5rem;">
                @csrf
                <div class="form-group">
                    <label class="label">Nama Pelanggaran <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="input" placeholder="Contoh: Terlambat masuk sekolah" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="label">Kategori <span style="color: var(--danger);">*</span></label>
                        <select name="category" class="select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Ringan">Ringan</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Berat">Berat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Poin <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="points" class="input" min="1" max="200" placeholder="10" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="label">Sub-Kategori</label>
                    <input type="text" name="sub_category" class="input" placeholder="Contoh: Kerapian, Kehadiran (opsional)">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2;">
                        <i data-lucide="save" size="15"></i> Simpan
                    </button>
                    <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn btn-outline" style="flex: 1;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);" onclick="if(event.target===this)this.style.display='none'">
        <div class="card" style="width: 480px; max-width: 90vw; padding: 0; overflow: hidden; animation: modalIn 0.3s ease-out;">
            <div style="padding: 1.5rem; background: var(--bg-hover); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="pencil" size="18" style="color: var(--secondary);"></i>
                    Edit Jenis Pelanggaran
                </h3>
                <button onclick="document.getElementById('editModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                    <i data-lucide="x" size="18"></i>
                </button>
            </div>
            <form id="editForm" method="POST" style="padding: 1.5rem;">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="label">Nama Pelanggaran <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="editName" class="input" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="label">Kategori <span style="color: var(--danger);">*</span></label>
                        <select name="category" id="editCategory" class="select" required>
                            <option value="Ringan">Ringan</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Berat">Berat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Poin <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="points" id="editPoints" class="input" min="1" max="200" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="label">Sub-Kategori</label>
                    <input type="text" name="sub_category" id="editSubCategory" class="input" placeholder="Opsional">
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
        function openEditModal(id, name, category, subCategory, points) {
            document.getElementById('editForm').action = '/admin/violation-types/' + id;
            document.getElementById('editName').value = name;
            document.getElementById('editCategory').value = category;
            document.getElementById('editSubCategory').value = subCategory || '';
            document.getElementById('editPoints').value = points;
            document.getElementById('editModal').style.display = 'flex';
            lucide.createIcons();
        }
    </script>
    @endpush
</x-app-layout>
