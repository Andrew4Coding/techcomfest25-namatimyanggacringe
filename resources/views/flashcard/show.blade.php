@extends('layout.layout')
@section('content')
    <section class="flex flex-col md:flex-row justify-between items-start space-y-4 md:space-y-0 md:space-x-4">
        <div class="space-y-2 mb-4 md:mb-0">
            <h1 class="text-3xl font-semibold">AI-Powered Flash Card</h1>
            <p class="font-medium gradient-blue text-transparent bg-clip-text">
                Belajar menjadi menyenangkan dan praktis.
            </p>
        </div>

        <button class="btn btn-primary" onclick="document.getElementById('add_flashcard_modal').showModal()">
            Import Document
        </button>

        <dialog id="add_flashcard_modal" class="modal">
            <div class="modal-box">
                <h3 class="font-semibold text-lg">Add New Flashcard</h3>
                <form method="POST" action="{{ route('flashcard.create') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="nama" id="nama" class="input input-bordered w-full"
                            placeholder="Nama Flashcard" required />
                    </div>
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700">Mata Pelajaran Terkait</label>
                        <select name="subject" id="subject" class="select select-bordered w-full" required>
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
                        <label for="file" class="block text-sm font-medium text-gray-700">Upload File</label>
                        <input type="file" name="file" id="file" class="file-input file-input-primary w-full"
                            required />
                    </div>
                    <div class="modal-action">
                        <button type="button" class="btn"
                            onclick="document.getElementById('add_flashcard_modal').close()">Batalkan</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </section>
    <section>
        @foreach ($flashcards as $flashcard)
            <div>
                HELLO
            </div>
        @endforeach

        @if ($flashcards->isEmpty())
            <div class="flex flex-col items-center justify-center space-y-4 h-full min-h-[500px]">
                <img src="{{ asset('mindora-mascot.png') }}" alt="Icon" class="w-52 h-auto">
                <h1 class="text-xl font-medium">
                    Belum ada Flash Card Saat ini
                </h1>
            </div>
        @endif
    </section>
@endsection
