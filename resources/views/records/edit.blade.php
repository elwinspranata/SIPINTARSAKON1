<x-app-layout>
    @section('header_title', 'Edit Catatan')
    @section('header_subtitle', 'Koreksi data perilaku untuk ' . ($record->student->name ?? 'Siswa'))

    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-light);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: var(--warning-light); color: var(--warning); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="edit-3" size="24"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 700;">Formulir Koreksi</h3>
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
                    <label class="label">Jenis Pelanggaran / Vitamin <span style="color: var(--danger);">*</span></label>
                    <select name="violation_type_id" class="select" required>
                        @foreach($violation_types as $category => $types)
                            <optgroup label="{{ $category }}">
                                @foreach($types as $vt)
                                    <option value="{{ $vt->id }}" {{ $record->violation_type_id == $vt->id ? 'selected' : '' }}>
                                        {{ $vt->name }} ({{ $vt->points }} Poin)
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="label">Tanggal Kejadian <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="date" class="input" value="{{ old('date', $record->date) }}" required>
                </div>

                <div class="form-group">
                    <label class="label">Keterangan Tambahan</label>
                    <textarea name="notes" class="textarea" rows="3" placeholder="Tambahkan catatan jika perlu...">{{ old('notes', $record->notes) }}</textarea>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light);">
                    <button type="submit" class="btn btn-primary" style="flex: 2; height: 42px;">
                        <i data-lucide="save" size="18"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('records.index') }}" class="btn btn-outline" style="flex: 1; height: 42px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
