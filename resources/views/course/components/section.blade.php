@props(['section'])

<div class="flex flex-col gap-2">
    <div class="flex flex-col gap-4">
        <div class="flex gap-4 items-center">
            <h1 class="text-2xl font-semibold rounded">{{ $section->name }}</h1>
            <div class="flex gap-2">
                @if ($isEdit)
                    <div class="px-4 py-2 text-white flex items-center gap-3 rounded-full bg-primary">
                        <button type="button" onclick="toggleSection('{{ $section->id }}')">
                            @if ($section->is_public)
                                <div class="tooltip tooltip-top flex items-center" data-tip="Hide from students">
                                    <x-lucide-eye
                                        class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-blue-500 hover:rotate-12"/>
                                </div>
                            @else
                                <div class="tooltip tooltip-right" data-tip="Show to students">
                                    <x-lucide-eye-off
                                        class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-blue-500 hover:rotate-12"/>
                                </div>
                            @endif
                        </button>

                        <script>
                            function toggleSection(sectionId) {
                                const rootUrl = window.location.origin;
                                fetch(`${rootUrl}/courses/sections/toggle/${sectionId}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({})
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            location.reload();
                                        } else {
                                            alert('Failed to toggle section visibility.');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('An error occurred. Please try again.');
                                    });
                            }
                        </script>
                        <div class="tooltip tooltip-top" data-tip="Edit Section">
                            <x-lucide-pencil class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer"
                                             onclick="document.getElementById('edit_section_modal_{{ $section->id }}').showModal();"/>
                        </div>
                        <div class="tooltip tooltip-top" data-tip="Tambah Course Item">
                            <x-lucide-plus class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer"
                                           onclick="document.getElementById('add_course_item_modal_{{ $section->id }}').showModal();"/>
                        </div>
                        <div class="tooltip tooltip-top" data-tip="Hapus Section">
                            <x-lucide-trash
                                class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-red-500 hover:rotate-12"
                                onclick="document.getElementById('delete_section_modal_{{ $section->id }}').showModal();"/>
                        </div>
                    </div>
                @endif
            </div>

        </div>
        @if ($section->description)
            <p class="font-normal text-sm">{{ $section->description }}</p>
        @endif
    </div>

    @php
        // Sort Courseitem by created_at
        $courseItems = $section->courseItems->sortBy('created_at');
    @endphp

    @foreach ($courseItems as $item)
        @include('course.components.course_item', ['item' => $item])

        <dialog id="delete_courseitem_modal_{{ $item->id }}" class="modal">
            <div class="modal-box">
                <h3 class="font-semibold text-lg">Konfirmasi Penghapusan</h3>
                <p>Apakah kamu yakin untuk menghapus Course Item ini?</p>
                <div class="modal-action">
                    <button type="button" class="btn"
                            onclick="document.getElementById('delete_courseitem_modal_{{ $item->id }}').close();">
                        Batalkan
                    </button>
                    <form method="POST" action="{{ route('course.item.delete', ['id' => $item->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error">Hapus</button>
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
        <h3 class="font-semibold text-lg">Buat Course Item Baru</h3>
        <div class="mb-4">
            <label for="item_type" class="block text-sm font-medium text-gray-700">Tipe Item</label>
            <select name="item_type" id="item_type" class="select w-full" required>
                <option value="material">Material</option>
                <option value="submission">Submisi</option>
                <option value="forum">Forum</option>
                <option value="attendance">Kehadiran</option>
                <option value="quiz">Kuis</option>
            </select>
        </div>

        <div id="material_fields" class="">
            <form method="POST"
                  action="{{ route('course.item.create', ['course_section_id' => $section->id, 'type' => 'material']) }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Material</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full" required/>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                        (Opsional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700">Upload File</label>
                    <input type="file" name="file" id="file" class="file-input file-input-primary w-full"
                           required accept=".pdf,.txt,.xlsx,.docx,.pptx"/>
                    <small class="text-gray-500">Max file size: 2MB</small>
                    <input type="hidden" name="MAX_FILE_SIZE" value="2097152"/>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="add_section_modal.close()">Batalkan</button>
                    <button type="submit" class="btn btn-primary">+ Buat</button>
                </div>
            </form>
        </div>
        <div id="submission_fields" class="hidden">
            <form method="POST" action="{{ route('submission.create', ['courseSectionId' => $section->id]) }}">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Item</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                        (Opsional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Konten</label>
                    <input type="text" name="content" id="content" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="mb-4">
                    <label for="opened_at" class="block text-sm font-medium text-gray-700">Dibuka Pada</label>
                    <input type="datetime-local" name="opened_at" id="opened_at" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Ditutup Pada</label>
                    <input type="datetime-local" name="due_date" id="due_date" class="input input-bordered w-full"
                           required/>
                </div>
                {{-- Max attemps --}}
                <div class="mb-4">
                    <label for="attempts" class="block text-sm font-medium text-gray-700">Attempts</label>
                    <input type="number" name="max_attempts" id="attempts" class="input input-bordered w-full"
                           required min="0"/>
                </div>
                <div class="mb-4">
                    <label for="file_types" class="block text-sm font-medium text-gray-700">Accepted File
                        Types</label>
                    <input type="text" name="file_types" id="file_types" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                            onclick="document.getElementById('add_submission_modal_{{ $section->id }}').close();">
                        Batalkan
                    </button>
                    <button type="submit" class="btn btn-primary">+ Buat</button>
                </div>
            </form>
        </div>
        <div id="forum_fields" class="hidden">
            <form method="POST" action="{{ route('forum.create', ['courseSectionId' => $section->id]) }}">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Item</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                        (Opsional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                            onclick="document.getElementById('add_forum_modal_{{ $section->id }}').close();">Batalkan
                    </button>
                    <button type="submit" class="btn btn-primary">+ Buat</button>
                </div>
            </form>
        </div>
        <div id="attendance_fields" class="hidden">
            <form method="POST"
                  action="{{ route('attendance.store', ['courseSectionId' => $section->id, 'type' => 'attendance']) }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Item</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                        (Opsional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Absensi</label>
                    <input type="text" name="password" id="password" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="add_section_modal.close()">Batalkan</button>
                    <button type="submit" class="btn btn-primary">+ Buat</button>
                </div>
            </form>
        </div>
        <div id="quiz_fields" class="hidden">
            <form
                method="POST"
                action="{{ route('course.item.create', ['course_section_id' => $section->id, 'type' => 'quiz']) }}"
                enctype="multipart/form-data"
            >
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Item</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                        (Opsional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="start" class="block text-sm font-medium text-gray-700">Start</label>
                    <input type="datetime-local" name="start" id="start" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="mb-4">
                    <label for="finish" class="block text-sm font-medium text-gray-700">Start</label>
                    <input type="datetime-local" name="finish" id="finish" class="input input-bordered w-full"
                           required/>
                </div>
                <div class="mb-4">
                    <label for="duration" class="block text-sm font-medium text-gray-700">Durasi (Menit)</label>
                    <input type="number" name="duration" id="duration" class="input input-bordered w-full"
                           required min="1"/>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                            onclick="document.getElementById('add_quiz_modal_{{ $section->id }}').close();">Batalkan
                    </button>
                    <button type="submit" class="btn btn-primary">+ Buat</button>
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
        <h3 class="font-semibold text-lg">Konfirmasi Penghapusan</h3>
        <p>Apakah kamu yakin untuk menghapus section ini?</p>
        <div class="modal-action">
            <button type="button" class="btn"
                    onclick="document.getElementById('delete_section_modal_{{ $section->id }}').close();">Batalkan
            </button>
            <form method="POST" action="{{ route('course.section.delete', ['id' => $section->id]) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error">Hapus</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="edit_section_modal_{{ $section->id }}" class="modal">
    <div class="modal-box">
        <h3 class="font-semibold text-lg">Edit Section</h3>
        <form method="POST" action="{{ route('course.section.update', ['id' => $section->id]) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Item</label>
                <input type="text" name="name" id="name" class="input input-bordered w-full"
                       value="{{ $section->name }}" required/>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3"
                          class="textarea textarea-bordered w-full">{{ $section->description }}</textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn"
                        onclick="document.getElementById('edit_section_modal_{{ $section->id }}').close();">Batalkan
                </button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    document.getElementById('item_type').addEventListener('change', function () {
        const materialFields = document.getElementById('material_fields');
        const submissionFields = document.getElementById('submission_fields');
        const forumFields = document.getElementById('forum_fields');
        const attendanceFields = document.getElementById('attendance_fields');
        const quizFields = document.getElementById('quiz_fields');

        materialFields.classList.add('hidden');
        submissionFields.classList.add('hidden');
        forumFields.classList.add('hidden');
        attendanceFields.classList.add('hidden');
        quizFields.classList.add('hidden');

        if (this.value === 'material') {
            materialFields.classList.remove('hidden');
        } else if (this.value === 'submission') {
            submissionFields.classList.remove('hidden');
        } else if (this.value === 'forum') {
            forumFields.classList.remove('hidden');
        } else if (this.value === 'attendance') {
            attendanceFields.classList.remove('hidden');
        } else if (this.value === 'quiz') {
            quizFields.classList.remove('hidden');
        }
    });
</script>
