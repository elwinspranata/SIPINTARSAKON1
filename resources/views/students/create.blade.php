<x-app-layout>
    @section('header_title', 'Tambah Siswa Baru')
    @section('header_subtitle', 'Daftarkan siswa baru ke dalam sistem monitoring.')

    <div style="max-width: 650px; margin: 0 auto;">
        <div class="card">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-light);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="user-plus" size="24"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 700;">Formulir Pendaftaran</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted);">Pastikan data NISN valid sesuai Dapodik.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('students.store') }}">
                @csrf

                <div class="form-group">
                    <label class="label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="input" value="{{ old('name') }}" placeholder="Masukkan nama lengkap siswa..." required autofocus>
                    @error('name') <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label">NISN</label>
                    <input type="text" name="nisn" class="input" value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa Nasional (10 Digit)">
                    @error('nisn') <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label class="label">Kelas <span style="color: var(--danger);">*</span></label>
                        <select name="class_id" class="select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('class_id') <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="label">Jenis Kelamin <span style="color: var(--danger);">*</span></label>
                        <select name="gender" class="select" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender') <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light);">
                    <button type="submit" class="btn btn-primary" style="flex: 2; height: 42px; font-size: 0.875rem;">
                        <i data-lucide="save" size="18"></i> Simpan Data Siswa
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-outline" style="flex: 1; height: 42px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
