{{-- resources/views/students/modals/edit.blade.php --}}
<div id="editStudentModal" style="display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); animation: fadeIn 0.3s ease-out;" onclick="if(event.target===this)this.style.display='none'">
    <div class="card" style="width: 520px; max-width: 95vw; padding: 0; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
        <div style="padding: 1.5rem 2rem; background: linear-gradient(135deg, #d97706, #92400e); color: white; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="pencil" style="width: 20px; height: 20px;"></i>
                    Edit Data Siswa
                </h3>
                <p style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin-top: 2px;">Perbarui informasi akademik siswa</p>
            </div>
            <button onclick="document.getElementById('editStudentModal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; padding: 6px; border-radius: 10px; display: flex; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>
        <form id="editStudentForm" method="POST" style="padding: 2rem;">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="label" style="font-weight: 700; color: #92400e;">Nama Lengkap</label>
                <input type="text" name="name" id="editName" class="input" required>
            </div>
            <div class="form-group">
                <label class="label" style="font-weight: 700; color: #92400e;">NISN</label>
                <input type="text" name="nisn" id="editNisn" class="input">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: #92400e;">Kelas</label>
                    <select name="class_id" id="editClassId" class="select" required>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="label" style="font-weight: 700; color: #92400e;">Jenis Kelamin</label>
                    <select name="gender" id="editGender" class="select" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" class="btn" style="flex: 1; height: 48px; border-radius: 14px; font-weight: 800; background: #d97706; color: white; box-shadow: 0 10px 15px -3px rgba(217, 119, 6, 0.3);">
                    <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i> Perbarui Data Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditStudentModal(id, name, nisn, classId, gender) {
        document.getElementById('editStudentForm').action = '/students/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editNisn').value = nisn === 'null' || nisn === '' ? '' : nisn;
        document.getElementById('editClassId').value = classId;
        document.getElementById('editGender').value = gender;
        document.getElementById('editStudentModal').style.display = 'flex';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
</script>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
