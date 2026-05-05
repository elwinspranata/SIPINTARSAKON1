<x-app-layout>
    @section('header_title', 'Edit Catatan')
    @section('header_subtitle', 'Koreksi data perilaku untuk ' . ($record->student->name ?? 'Siswa'))

    @php $isVitamin = !empty($record->vitamin_type_id); @endphp

    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-light);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: {{ $isVitamin ? 'var(--success-light)' : 'var(--warning-light)' }}; color: {{ $isVitamin ? 'var(--success)' : 'var(--warning)' }}; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="{{ $isVitamin ? 'sparkles' : 'edit-3' }}" size="24"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 700;">{{ $isVitamin ? 'Edit Catatan Vitamin' : 'Edit Catatan Pelanggaran' }}</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted);">Ubah data catatan jika terjadi kesalahan input.</p>
                </div>
            </div>

            <form action="{{ route('records.update', $record) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="label">Siswa</label>
                    <input type="text" class="input" value="{{ $record->student->name }}" disabled style="background: var(--bg); cursor: not-allowed;">
                </div>

                <div class="form-group">
                    @if($isVitamin)
                        <label class="label">Kategori Vitamin <span style="color: var(--danger);">*</span></label>
                        <select name="category" id="categorySelect" class="select" required onchange="loadTypes()">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($vitamin_types as $category => $types)
                                <option value="{{ $category }}" {{ ($record->vitaminType->category ?? '') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                        
                        <label class="label" style="margin-top: 1rem;">Jenis Vitamin <span style="color: var(--danger);">*</span></label>
                        <select name="vitamin_type_id" id="typeSelect" class="select" required>
                            <option value="">-- Pilih kategori dulu --</option>
                        </select>
                    @else
                        <label class="label">Kategori Pelanggaran <span style="color: var(--danger);">*</span></label>
                        <select name="category" id="categorySelect" class="select" required onchange="loadTypes()">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($violation_types as $category => $types)
                                <option value="{{ $category }}" {{ ($record->violationType->category ?? '') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>

                        <label class="label" style="margin-top: 1rem;">Jenis Pelanggaran <span style="color: var(--danger);">*</span></label>
                        <select name="violation_type_id" id="typeSelect" class="select" required>
                            <option value="">-- Pilih kategori dulu --</option>
                        </select>
                    @endif
                </div>

                <div class="form-group">
                    <label class="label">Keterangan Tambahan</label>
                    <textarea name="notes" class="textarea" rows="3" placeholder="Tambahkan catatan jika perlu...">{{ old('notes', $record->notes) }}</textarea>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light);">
                    <button type="submit" class="btn" style="flex: 2; height: 42px; background: {{ $isVitamin ? 'var(--success)' : 'var(--primary)' }}; color: white;">
                        <i data-lucide="save" size="18"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('records.index') }}" class="btn btn-outline" style="flex: 1; height: 42px;">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        var typesData = @json($isVitamin ? $vitamin_types : $violation_types);
        var isVitamin = {{ $isVitamin ? 'true' : 'false' }};
        var oldTypeId = '{{ $record->vitamin_type_id ?? $record->violation_type_id }}';

        var categorySelectTs, typeSelectTs;

        function initTomSelects() {
            categorySelectTs = new TomSelect('#categorySelect', {
                create: false,
                plugins: ['dropdown_input'],
                placeholder: "-- Pilih Kategori --"
            });

            typeSelectTs = new TomSelect('#typeSelect', {
                create: false,
                plugins: ['dropdown_input'],
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                placeholder: "-- Pilih kategori dulu --"
            });

            categorySelectTs.on('change', loadTypes);
        }

        function loadTypes() {
            var category = categorySelectTs.getValue();
            
            typeSelectTs.clearOptions();
            typeSelectTs.clear();

            if (!category || !typesData[category]) {
                typeSelectTs.disable();
                return;
            }
            
            var types = typesData[category];
            var options = types.map(t => {
                var pointsText = isVitamin ? `(+${t.points} Poin)` : `(${t.points} Poin)`;
                return { id: t.id, name: `${t.name} ${pointsText}` };
            });
            
            typeSelectTs.addOptions(options);
            typeSelectTs.enable();

            if (oldTypeId) {
                typeSelectTs.setValue(oldTypeId);
                oldTypeId = null;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initTomSelects();
            if (categorySelectTs.getValue()) loadTypes();
        });
    </script>
    @endpush
</x-app-layout>
