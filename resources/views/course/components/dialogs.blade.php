<dialog id="edit_course_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Edit Course</h3>
        <form method="POST" action="{{ route('course.update', ['id' => $course->id]) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700">Course Name</label>
                <input type="text" name="name" id="edit_name" class="input input-bordered w-full"
                    value="{{ $course->name }}" required />
            </div>
            <div class="mb-4">
                <label for="edit_description"
                    class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="edit_description" rows="3" class="textarea textarea-bordered w-full" required>{{ $course->description }}</textarea>
            </div>
            <div class="mb-4">
                <label for="edit_class_code" class="block text-sm font-medium text-gray-700">Class Code</label>
                <input type="text" name="class_code" id="edit_class_code" class="input input-bordered w-full"
                    value="{{ $course->class_code }}" required />
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="edit_course_modal.close()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="delete_course_modal_{{ $course->id }}" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Confirm Deletion</h3>
        <p>Are you sure you want to delete this Course?</p>
        <div class="modal-action">
            <button type="button" class="btn"
                onclick="document.getElementById('delete_course_modal_{{ $course->id }}').close();">Cancel</button>
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
        <h3 class="font-bold text-lg">Create new Section</h3>
        <form method="POST" action="{{ route('course.section.create', ['id' => $course->id]) }}"
            id="course-section-form">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Section Name</label>
                <input type="text" name="name" id="name" class="input input-bordered w-full"
                    required />
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full"></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="add_section_modal.close()">Cancel</button>
                <button type="submit" class="btn btn-primary">+ Create</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
