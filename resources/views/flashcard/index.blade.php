@php
    $selected_theme = $flashcard->subject;

    $theme = config('constants.theme')[$selected_theme];
@endphp
@extends('layout.layout')
@section('content')
    <section>
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="/flashcard">Flashcard</a></li>
                <li>
                    <a href="{{ route('flashcard.show') }}">
                        {{ $flashcard->name }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="p-4 mb-10 rounded-xl relative min-h-52 overflow-hidden"
            style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] }};">

            @php
                $selected_theme = $flashcard->subject;
            @endphp

            @if ($selected_theme == 'sosiologi')
                <img src="{{ asset('corner/yellow-corner-left.png') }}" alt=""
                    class="absolute bottom-0 left-0 w-40 h-20 object-contain z-0">
                <img src="{{ asset('corner/yellow-corner-right.png') }}" alt=""
                    class="absolute top-0 right-0 w-40 h-20 object-contain z-0">
            @elseif ($selected_theme == 'ekonomi')
                <img src="{{ asset('corner/green-corner-left.png') }}" alt=""
                    class="absolute bottom-0 -left-12 w-40 h-20 object-contain z-0">
                <img src="{{ asset('corner/green-corner-right.png') }}" alt=""
                    class="absolute top-0 -right-12 w-40 h-20 object-contain z-0">
            @elseif ($selected_theme == 'bahasa')
                <img src="{{ asset('corner/blue-corner.png') }}" alt=""
                    class="absolute top-0 left-0 object-contain z-0">
            @endif

            <!-- Adjusted Image Styling -->
            <div class="absolute inset-0 pointer-events-none top-10 left-20">
                <img src="{{ asset('subject-mascots/' . $flashcard->subject . '.png') }}" alt="Icon"
                    class="w-80 h-52 object-contain z-0">
            </div>

            <div class="z-10">
                <div class="flex justify-between items-center mb-4 w-full">
                    <h1 class="text-xl font-bold">{{ $flashcard->name }}</h1>
                    <div class="flex gap-4 items-center">
                        <x-lucide-pencil class="min-w-4 h-4 hover:scale-105 duration-150 cursor-pointer"
                            onclick="document.getElementById('edit_flashcard_modal_{{ $flashcard->id }}').showModal();" />
                        <button
                            onclick="document.getElementById('delete_flashcard_modal_{{ $flashcard->id }}').showModal();">
                            <x-lucide-trash class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer" />
                        </button>
                        <dialog id="delete_flashcard_modal_{{ $flashcard->id }}" class="modal text-black">
                            <div class="modal-box">
                                <h3 class="font-semibold text-lg">Konfirmasi Penghapusan</h3>
                                <p>Apakah Anda yakin ingin menghapus Flashcard ini?</p>
                                <div class="modal-action">
                                    <button type="button" class="btn"
                                        onclick="document.getElementById('delete_flashcard_modal_{{ $flashcard->id }}').close();">Batalkan</button>
                                    <form method="POST"
                                        action="{{ route('flashcard.delete', ['id' => $flashcard->id]) }}">
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

                        <dialog id="edit_flashcard_modal_{{ $flashcard->id }}" class="modal text-black">
                            <div class="modal-box">
                                <h3 class="font-semibold text-lg">Edit Flashcard</h3>
                                <form method="POST" action="{{ route('flashcard.update', ['id' => $flashcard->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium">Name</label>
                                        <input type="text" name="name" id="name" value="{{ $flashcard->name }}"
                                            class="input input-bordered w-full">
                                    </div>
                                    <div class="mb-4">
                                        <label for="description" class="block text-sm font-medium">Description
                                            (Optional)</label>
                                        <textarea name="description" id="description" class="textarea textarea-bordered w-full">{{ $flashcard->description }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="subject" class="block text-sm font-medium text-gray-700">Mata Pelajaran
                                            Terkait</label>
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
                                        <button type="button" class="btn"
                                            onclick="document.getElementById('edit_flashcard_modal_{{ $flashcard->id }}').close();">Batalkan</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                            <form method="dialog" class="modal-backdrop">
                                <button>close</button>
                            </form>
                        </dialog>
                    </div>
                </div>
            </div>
        </div>

        {{-- Iterate over flashCardItems --}}
        @foreach ($flashcardItems as $item)
            <div class="w-full p-5 bg-white shadow-smooth rounded-xl mb-5">
                <div class="border-b-2 border-gray-100 pb-5 mb-5">
                    <h3 class="font-medium text-lg">
                        {{ $item->question }}
                    </h3>
                </div>
                <p>
                    {{ $item->answer }}
                </p>
            </div>
        @endforeach

    </section>
@endsection
