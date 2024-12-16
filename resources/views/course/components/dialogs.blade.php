<dialog id="edit_course_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-semibold text-lg">Edit Kelas</h3>
        <form method="POST" action="{{ route('course.update', ['id' => $course->id]) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                <input type="text" name="name" id="edit_name" class="input input-bordered w-full"
                    value="{{ $course->name }}" required />
            </div>
            <div class="mb-4">
                <label for="edit_description"
                    class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                <textarea name="description" id="edit_description" rows="3" class="textarea textarea-bordered w-full">{{ $course->description }}</textarea>
            </div>
            <div class="mb-4">
                <label for="edit_class_code" class="block text-sm font-medium text-gray-700">Kode Kelas</label>
                <input type="text" name="class_code" id="edit_class_code" class="input input-bordered w-full"
                    value="{{ $course->class_code }}" required />
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="edit_course_modal.close()">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="delete_course_modal_{{ $course->id }}" class="modal">
    <div class="modal-box">
        <h3 class="font-semibold text-lg">Konfirmasi Penghapusan</h3>
        <p>Are you sure you want to delete this Course?</p>
        <div class="modal-action">
            <button type="button" class="btn"
                onclick="document.getElementById('delete_course_modal_{{ $course->id }}').close();">Batalkan</button>
            <form method="POST" action="{{ route('course.delete', ['id' => $course->id]) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error">Delete</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="add_section_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-semibold text-lg">Buat Section Baru</h3>
        <form method="POST" action="{{ route('course.section.create', ['id' => $course->id]) }}" id="course-section-form">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Section</label>
                <input type="text" name="name" id="name" class="input input-bordered w-full"
                    required />
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full"></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="add_section_modal.close()">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
