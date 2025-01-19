<div class="p-5 md:p-10 bg-white shadow-smooth rounded-xl h-full">
    <div class="flex gap-4 items-center mb-5">
        <h3 class="w-1/2">Pesan untuk murid</h3>
    </div>

    @if (!$students || count($students) === 0)
        <div class="p-5 w-full h-full flex items-center justify-center flex-col min-h-72 text-center gap-2">
            <img src="{{ asset('mascot-nyari.png') }}" alt="No Students" class="w-[150px]">
            <h4 class="font-medium">Tidak ada murid yang terdaftar</h4>
            <p class="text-sm text-gray-400">Sebar kode kelas untuk menerima murid</p>
        </div>
    @else
        <div class="w-full flex flex-col gap-4 max-h-[300px] overflow-y-auto overflow-x-hidden">
            @foreach ($students as $student)
                <div
                    class="flex flex-col md:flex-row items-center gap-4 p-4 bg-gray-50 rounded-xl border-2 border-gray-50">
                    <div class="w-full flex items-center gap-5">
                        @if ($student->user && $student->user->profile_picture)
                            <img src="{{ $student->user->profile_picture }}" alt="{{ $student->user->name }}"
                                class="w-12 h-12 rounded-full mr-4">
                        @else
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-300">
                                <img class="w-full object-cover"
                                    src="https://ui-avatars.com/api/?name={{ $student->user->name }}&color=7F9CF5&background=EBF4FF"
                                    alt="{{ $student->user->name }}">
                            </div>
                        @endif
                        <div class="">
                            <div class="font-medium">{{ $student->user->name }}</div>
                        </div>
                    </div>
                    <button class="btn btn-outline w-full md:w-fit"
                        onclick="document.getElementById('message_modal_{{ $student->id }}').showModal();">
                        Kirim Pesan
                    </button>
                    <dialog id="message_modal_{{ $student->id }}" class="modal">
                        <div class="modal-box">
                            <h3 class="font-semibold text-lg">Kirim Pesan ke {{ $student->user->name }}</h3>
                            <form method="POST"
                                action="{{ route('dashboard.teacher.send', ['studentId' => $student->id]) }}">
                                @csrf
                                <div class="form-control">
                                    <label for="message" class="label">Pesan</label>
                                    <textarea id="message" name="message" class="textarea textarea-bordered" required></textarea>
                                </div>
                                <div class="modal-action">
                                    <button type="button" class="btn"
                                        onclick="document.getElementById('message_modal_{{ $student->id }}').close();">Batalkan</button>
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </div>
            @endforeach
        </div>
    @endif
</div>
