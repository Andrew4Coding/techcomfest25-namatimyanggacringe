@props(['section'])

<div class="flex flex-col gap-2">
    <div class="flex flex-col gap-4">
        <div class="flex gap-4 items-center">
            <h1 class="text-2xl font-semibold rounded">{{ $section->name }}</h1>
            <div class="flex gap-2">
                @if ($isEdit)
                    <x-lucide-pencil class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer"
                        onclick="document.getElementById('edit_section_modal_{{ $section->id }}').showModal();" />
                    <x-lucide-trash
                        class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-red-500 hover:rotate-12"
                        onclick="document.getElementById('delete_section_modal_{{ $section->id }}').showModal();" />
                    <x-lucide-plus-circle class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer"
                        onclick="document.getElementById('add_course_item_modal_{{ $section->id }}').showModal();" />
                @endif
            </div>

        </div>
        @if ($section->description)
            <p class="font-normal text-sm">{{ $section->description }}</p>
        @endif
    </div>

    @foreach ($section->courseItems as $item)
        @if ($item->course_itemable_type == 'App\Models\Material')
            @include('course.components.material', ['item' => $item])
        @elseif ($item->course_itemable_type == 'App\Models\Submission')
            @include('course.components.submission', ['item' => $item])
        @elseif ($item->course_itemable_type == 'App\Models\Forum')
            @include('course.components.forum', ['item' => $item])
        @endif

        <dialog id="delete_courseitem_modal_{{ $item->id }}" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Confirm Deletion</h3>
                <p>Are you sure you want to delete this Course Item?</p>
                <div class="modal-action">
                    <button type="button" class="btn"
                        onclick="document.getElementById('delete_courseitem_modal_{{ $item->id }}').close();">Cancel</button>
                    <form method="POST" action="{{ route('course.item.delete', ['id' => $item->id]) }}">
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
    @endforeach
</div>



<dialog id="add_course_item_modal_{{ $section->id }}" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Create new Course Item</h3>
        <div class="mb-4">
            <label for="item_type" class="block text-sm font-medium text-gray-700">Item Type</label>
            <select name="item_type" id="item_type" class="select select-bordered w-full" required>
                <option value="material">Material</option>
                <option value="submission">Submission</option>
                <option value="forum">Forum</option>
            </select>
        </div>

        <div id="material_fields" class="">
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
        <div id="submission_fields" class="hidden">
            <form method="POST" action="{{ route('submission.create', ['courseSectionId' => $section->id]) }}">
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
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <input type="text" name="content" id="content" class="input input-bordered w-full" required />
                </div>
                <div class="mb-4">
                    <label for="opened_at" class="block text-sm font-medium text-gray-700">Opened At</label>
                    <input type="datetime-local" name="opened_at" id="opened_at" class="input input-bordered w-full"
                        required />
                </div>
                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="datetime-local" name="due_date" id="due_date" class="input input-bordered w-full"
                        required />
                </div>
                <div class="mb-4">
                    <label for="file_types" class="block text-sm font-medium text-gray-700">File Types</label>
                    <input type="text" name="file_types" id="file_types" class="input input-bordered w-full"
                        required />
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                        onclick="document.getElementById('add_submission_modal_{{ $section->id }}').close();">Cancel</button>
                    <button type="submit" class="btn btn-primary">+ Create</button>
                </div>
            </form>
        </div>
        <div id="forum_fields" class="hidden">
            <form method="POST" action="{{ route('forum.create', ['courseSectionId' => $section->id]) }}">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full"
                        required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full" required></textarea>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                        onclick="document.getElementById('add_forum_modal_{{ $section->id }}').close();">Cancel</button>
                    <button type="submit" class="btn btn-primary">+ Create</button>
                </div>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="delete_section_modal_{{ $section->id }}" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Confirm Deletion</h3>
        <p>Are you sure you want to delete this section?</p>
        <div class="modal-action">
            <button type="button" class="btn"
                onclick="document.getElementById('delete_section_modal_{{ $section->id }}').close();">Cancel</button>
            <form method="POST" action="{{ route('course.section.delete', ['id' => $section->id]) }}">
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
        <form method="POST" action="{{ route('course.section.update', ['id' => $section->id]) }}">
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

<script>
    document.getElementById('item_type').addEventListener('change', function() {
        const materialFields = document.getElementById('material_fields');
        const submissionFields = document.getElementById('submission_fields');
        const forumFields = document.getElementById('forum_fields');
        materialFields.classList.add('hidden');
        submissionFields.classList.add('hidden');
        forumFields.classList.add('hidden');

        if (this.value === 'material') {
            materialFields.classList.remove('hidden');
        } else if (this.value === 'submission') {
            submissionFields.classList.remove('hidden');
        } else if (this.value === 'forum') {
            forumFields.classList.remove('hidden');
        }
    });
</script>
