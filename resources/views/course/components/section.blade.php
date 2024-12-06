@php
    $courseItem = [
        [
            'name' => 'Vektor Eigen',
            'description' => 'Mahasiswa diminta untuk menonton video yang akan saya berikan',
        ],
        [
            'name' => 'Transformasi Linear',
            'description' => 'Mahasiswa diminta untuk membaca materi tentang transformasi linear',
        ],
        [
            'name' => 'Matriks dan Determinan',
            'description' => 'Mahasiswa diminta untuk mengerjakan soal-soal matriks dan determinan',
        ],
        [
            'name' => 'Ruang Vektor',
            'description' => 'Mahasiswa diminta untuk memahami konsep ruang vektor',
        ],
        [
            'name' => 'Basis dan Dimensi',
            'description' => 'Mahasiswa diminta untuk mempelajari basis dan dimensi',
        ],
        [
            'name' => 'Nilai Eigen dan Vektor Eigen',
            'description' => 'Mahasiswa diminta untuk menonton video tentang nilai eigen dan vektor eigen',
        ],
    ];
@endphp

@props(['section'])

<div class="flex flex-col gap-2">
    <div class="flex flex-col gap-4">
        <h1 class="text-3xl font-bold">{{ $section->name }}</h1>
        <p class="font-medium">{{ $section->description }}</p>
    </div>

    <button class="btn" onclick="add_course_item_modal.showModal()">+ Add Course Item</button>
    <dialog id="add_course_item_modal" class="modal">
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
    @if ($item->itemable)
        {{-- Access polymorphic-specific fields --}}
        @if ($item->itemable_type === App\Models\Material::class)
            Material Details: {{ $item->itemable->content }}
            <p>{{ $item['file_url'] }}</p>
        @elseif ($item->itemable_type === App\Models\Quiz::class)
            Quiz Details: {{ $item->itemable->questions_count }} Questions
        @endif
    @endif
@endforeach
</div>
