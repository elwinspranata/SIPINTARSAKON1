<x-app-layout>
    @section('header_title', 'Edit Data Siswa')
    @section('header_subtitle', 'Perbarui informasi untuk ' . $student->name)

    <div style="max-width: 650px; margin: 0 auto;">
        <div class="card">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-light);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="user-cog" size="24"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 700;">Modifikasi Informasi</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted);">Perubahan akan segera diterapkan di seluruh sistem.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('students.update', $student) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="input" value="{{ old('name', $student->name) }}" required>
                    @error('name') <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label">NISN</label>
                    <input type="text" name="nisn" class="input" value="{{ old('nisn', $student->nisn) }}">
                    @error('nisn') <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label class="label">Kelas <span style="color: var(--danger);">*</span></label>
                        <select name="class_id" class="select" required>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="label">Jenis Kelamin <span style="color: var(--danger);">*</span></label>
                        <select name="gender" class="select" required>
                            <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light);">
                    <button type="submit" class="btn btn-primary" style="flex: 2; height: 42px;">
                        <i data-lucide="refresh-cw" size="18"></i> Perbarui Data
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-outline" style="flex: 1; height: 42px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
