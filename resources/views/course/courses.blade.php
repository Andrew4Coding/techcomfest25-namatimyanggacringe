@extends('layout.layout')
@section('content')
    <section class="flex flex-col md:flex-row justify-between items-start space-y-4 md:space-y-0 md:space-x-4">
        <div class="space-y-2 mb-4 md:mb-0">
            <h1 class="text-3xl font-semibold">Course List</h1>
            <p class="font-medium gradient-blue text-transparent bg-clip-text">
                Semangat yahh!
            </p>
        </div>

        {{-- If User is a teacher --}}
        @if (Auth::user()->userable_type == 'App\Models\Teacher' && !$courses->isEmpty())
            <button onclick="add_course_modal.showModal()" class="btn btn-primary">
                <x-lucide-plus class="w-6 h-6" />
                Tambah Kelas
            </button>
        @else
            @if (!$courses->isEmpty())
                <button onclick="enroll_class_modal.showModal()" class="btn btn-primary px-10">
                    Daftar Kelas
                </button>
            @endif
        @endif
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 min-h-[70vh] py-4">
        @if ($courses->isEmpty())
            <div class="w-full flex flex-col gap-4 items-center justify-center col-span-1 sm:col-span-2 lg:col-span-3">
                <div class="flex flex-col items-center gap-2 text-center">
                    <img src="{{ asset('mindora-mascot.png') }}" alt="Icon" class="w-52 h-auto">
                    <h1 class="text-xl font-medium">
                        Belum ada Kelas Saat ini
                    </h1>
                    <p
                        class="font-medium gradient-blue text-transparent bg-clip-text">
                        Belajar menjadi menyenangkan dan praktis.
                    </p>
                    @if (Auth::user()->userable_type == 'App\Models\Teacher')
                        <button class="btn btn-primary" onclick="add_course_modal.showModal()">
                            <x-lucide-plus class="w-6 h-6" />
                            Tambah Kelas
                        </button>
                    @else
                        <h1 class="text-xl font-semibold">Kamu belum memiliki course apapun</h1>
                        <button class="btn btn-primary" onclick="enroll_class_modal.showModal()">
                            Enroll a Class
                        </button>
                    @endif
                </div>
            </div>
        @else
            @foreach ($courses as $course)
                @include('course.components.course_card', ['course' => $course])
            @endforeach
        @endif
    </div>

    <dialog id="add_course_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-semibold text-lg">Buat Kelas Baru</h3>
            <form method="POST" action="{{ route('course.create') }}" id="add_course_form">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="class_code" class="block text-sm font-medium text-gray-700">Kode Kelas (5 Digit Huruf / Angka)</label>
                    <input name="class_code" id="class_code" class="input input-bordered w-full" required
                        pattern="[A-Za-z0-9]{5}" title="Class code must be 5 letters or numbers" />
                </div>
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700">Mata Pelajaran Terkait</label>
                    <select name="subject" id="subject" class="select w-full" required>
                        <option value="sosiologi">Sosiologi</option>
                        <option value="ekonomi">Ekonomi</option>
                        <option value="bahasa">Bahasa</option>
                        <option value="geografi">Geografi</option>
                        <option value="matematika">Matematika</option>
                        <option value="sejarah">Sejarah</option>
                        <option value="ipa">IPA</option>
                    </select>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="document.getElementById('add_course_modal').close();">Batalkan</button>
                    <button type="submit" class="btn btn-primary">+ Buat</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <dialog id="enroll_class_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-semibold text-lg">Enroll in a Class</h3>
            <form method="POST" action="{{ route('course.enroll') }}" id="enroll_class_form">
                @csrf
                <div class="mb-4">
                    <label for="enroll_class_code" class="block text-sm font-medium text-gray-700">Class Code</label>
                    <input type="text" name="class_code" id="enroll_class_code" class="input input-bordered w-full"
                        required />
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="hideModal('enroll_class_modal')">Batalkan</button>
                    <button type="submit" class="btn btn-primary">Enroll</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endsection
