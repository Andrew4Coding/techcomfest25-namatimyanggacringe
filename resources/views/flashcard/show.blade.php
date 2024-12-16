@extends('layout.layout')
@section('content')
    <section class="flex flex-col md:flex-row justify-between items-start space-y-4 md:space-y-0 md:space-x-4">
        <div class="space-y-2 mb-4 md:mb-0">
            <h1 class="text-3xl font-semibold">AI-Powered Flash Card</h1>
            <p class="font-medium gradient-blue text-transparent bg-clip-text">
                Belajar menjadi menyenangkan dan praktis.
            </p>
        </div>

        @if (!$flashcards->isEmpty())
            <button class="btn btn-primary" onclick="document.getElementById('add_flashcard_modal').showModal()">
                <x-lucide-file class="w-4 h-4" />
                Import Document
            </button>
        @endif


    </section>
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-10">
        @foreach ($flashcards as $flashcard)
            @php
                $selected_theme = $flashcard->subject;
                $theme = config('constants.theme')[$selected_theme];
            @endphp

            <a href="{{ url('/flashcard/' . $flashcard->id) }}" class="no-underline text-black h-fit">
                <div class="w-full overflow-hidden shadow-lg h-full rounded-xl md:max-h-64 lg:max-h-80 hover:scale-105 duration-300 flashcard-{{ $flashcard->theme }}"
                    style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] ?? $theme['secondary'] }}">
                    <div class="px-6 py-4 flex flex-col justify-between h-full relative grow min-h-52">
                        <div class="absolute inset-0 flex items-center justify-center -bottom-20">
                            <img src="{{ asset('subject-mascots/' . $flashcard->subject . '.png') }}" alt="Icon"
                                class="w-80 h-full object-contain">
                        </div>
                        <div class="font-bold text-xl mb-2" style="color: {{ $theme['tertiary'] }}">{{ $flashcard->name }}
                        </div>
                    </div>
                </div>
            </a>
        @endforeach

        @if ($flashcards->isEmpty())
            <div class="flex flex-col items-center justify-center space-y-4 h-full min-h-[500px] col-span-3">
                <img src="{{ asset('mascot-love.png') }}" alt="Icon" class="w-52 h-auto">
                <h1 class="text-xl font-medium">
                    Belum ada Flash Card Saat ini
                </h1>
                <button class="btn btn-primary"
                    onclick="document.getElementById('add_flashcard_modal').showModal()"
                >
                    <x-lucide-plus class="w-4 h-4" />
                    Buat Flash Card
                </button>
            </div>
        @endif

    </section>
    <dialog id="add_flashcard_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-semibold text-lg">Buat Flashcard Baru</h3>
            <form method="POST" action="{{ route('flashcard.create') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" class="input w-full" placeholder="Nama Flashcard"
                        required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                        (Opsional)</label>
                    <textarea name="description" id="description" class="textarea w-full" placeholder="Deskripsi Flashcard"></textarea>
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
                <div class="mb-4">
                    <label for="pdf" class="block text-sm font-medium text-gray-700">Upload File</label>
                    <input type="file" name="pdf" id="pdf" accept=".pdf"
                        class="file-input file-input-primary w-full" required />
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                        onclick="document.getElementById('add_flashcard_modal').close()">Batalkan</button>
                    <button type="submit" class="btn btn-primary">
                        <x-lucide-plus class="w-4 h-4" />
                        Tambahkan
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endsection
