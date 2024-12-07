@props(['section'])

<div class="flex flex-col gap-2">
    <div class="flex flex-col gap-4">
        <h1 class="text-3xl font-bold">{{ $section->name }}</h1>
        <p class="font-medium">{{ $section->description }}</p>
    </div>

    <div class="flex gap-4 w-full">
        <button class="btn btn-error"
            onclick="document.getElementById('delete_section_modal_{{ $section->id }}').showModal();">
            Delete Section
        </button>
    
        <button class="btn btn-accent"
            onclick="document.getElementById('edit_section_modal_{{ $section->id }}').showModal();">
            Edit Section
        </button>
    
    
        <button class="btn"
            onclick="const modal = document.getElementById('add_course_item_modal_{{ $section->id }}').showModal();">+ Add
            Course Item</button>
    </div>


    <dialog id="delete_section_modal_{{ $section->id }}" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Deletion</h3>
            <p>Are you sure you want to delete this section?</p>
            <div class="modal-action">
                <button type="button" class="btn"
                    onclick="document.getElementById('delete_section_modal_{{ $section->id }}').close();">Cancel</button>
                <form method="POST" action="{{ route('course.section.delete', ['sectionId' => $section->id]) }}">
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


    <dialog id="edit_section_modal_{{ $section->id }}" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Edit Section</h3>
            <form method="POST" action="{{ route('course.section.update', ['sectionId' => $section->id]) }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full"
                        value="{{ $section->name }}" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full" required>{{ $section->description }}</textarea>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                        onclick="document.getElementById('edit_section_modal_{{ $section->id }}').close();">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>


    <dialog id="add_course_item_modal_{{ $section->id }}" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Create new Course Item</h3>
            <form method="POST"
                action="{{ route('course.item.create', ['course_section_id' => $section->id, 'type' => 'material']) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700">Upload File</label>
                    <input type="file" name="file" id="file" class="file-input file-input-primary w-full"
                        required accept=".pdf,.txt,.xlsx,.docx,.pptx" />
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
</div>
@foreach ($section->courseItems as $item)
    <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5">
        <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
        <div>
            <p class="font-semibold">{{ $item['name'] }}</p>
            <p>{{ $item['description'] }}</p>
        </div>
    </div>
@endforeach
</div>
