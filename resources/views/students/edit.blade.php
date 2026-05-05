<x-app-layout>
    @section('header_title', 'Edit Data Siswa')
    @section('header_subtitle', 'Perbarui profil untuk siswa ' . $student->name)
    @section('header_actions')
        <a href="{{ route('students.index') }}" class="btn btn-outline" style="border-radius: 12px;">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i> Batal & Kembali
        </a>
    @endsection

    <div style="max-width: 640px; margin: 0 auto; animation: fadeInUp 0.5s ease-out;">
        <div class="card" style="padding: 2.5rem; border: 1px solid var(--border-light); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <div style="width: 64px; height: 64px; background: #fffbeb; color: #d97706; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; border: 1px solid #fef3c7;">
                    <i data-lucide="user-cog" style="width: 32px; height: 32px;"></i>
                </div>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary-dark);">Modifikasi Data Siswa</h2>
                <p style="font-size: 0.8125rem; color: var(--text-muted); margin-top: 4px;">Informasi yang diubah akan diperbarui secara real-time.</p>
            </div>

            <form action="{{ route('students.update', $student) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">Nama Lengkap</label>
                    <input type="text" name="name" class="input @error('name') is-invalid @enderror" value="{{ old('name', $student->name) }}" required>
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">NISN</label>
                        <input type="text" name="nisn" class="input @error('nisn') is-invalid @enderror" value="{{ old('nisn', $student->nisn) }}">
                        @error('nisn') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label" style="font-weight: 700; color: var(--primary-dark);">Jenis Kelamin</label>
                        <select name="gender" class="select @error('gender') is-invalid @enderror" required>
                            <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: var(--primary-dark);">Kelas</label>
                    <select name="class_id" class="select @error('class_id') is-invalid @enderror" required>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('class_id') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div style="margin-top: 2.5rem;">
                    <button type="submit" class="btn" style="width: 100%; height: 52px; border-radius: 14px; font-weight: 800; font-size: 1rem; background: #d97706; color: white;">
                        <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                        Perbarui Informasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .error-text { font-size: 0.75rem; color: #ef4444; margin-top: 0.5rem; font-weight: 600; }
        .is-invalid { border-color: #ef4444 !important; background-color: #fff1f2 !important; }
    </style>
    @endpush
</x-app-layout>

