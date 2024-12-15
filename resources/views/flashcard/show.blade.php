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
                <h3 class="font-bold text-lg">Add New Flashcard</h3>
                <form method="POST" action="{{ route('flashcard.create') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="nama" id="nama" class="input input-bordered w-full"
                            placeholder="Nama Flashcard" required />
                    </div>
                    <div class="mb-4">
                        <label for="file" class="block text-sm font-medium text-gray-700">Upload File</label>
                        <input type="file" name="file" id="file" class="file-input file-input-primary w-full" required />
                    </div>
                    <div class="modal-action">
                        <button type="button" class="btn"
                            onclick="document.getElementById('add_flashcard_modal').close()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </section>
@endsection
