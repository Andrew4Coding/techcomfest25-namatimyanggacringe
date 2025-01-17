<div class="p-10 bg-white shadow-smooth rounded-xl">
    <h3 class="mb-5">Pesan dari Guru</h3>

    @if (!$studentMessages || count($studentMessages) === 0)
        <div class="p-3 md:p-5 w-full h-full flex items-center justify-center flex-col min-h-72 text-center gap-2">
            <img src="{{ asset('mascot-teacher.png') }}" alt="No Students" class="w-[180px]">
            <h4 class="font-medium">Tidak ada pesan dari Guru Kelas</h4>
        </div>
    @else
        @foreach ($studentMessages as $message)
            <div class="w-full overflow-hidden shadow-lg rounded-3xl hover:scale-[102%] duration-300 bg-[#F6F9FA]">
                <div class="px-6 py-4 flex flex-col justify-between">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-4">
                            <img class="rounded-full w-14 h-14"
                                src="
                                    @if ($message->teacher->user->profile_photo_path) {{ asset('storage/' . $message->teacher->user->profile_photo_path) }}
                                    @elseif ($message->teacher->user->name)
                                        https://ui-avatars.com/api/?name={{ $message->teacher->user->name }}&color=7F9CF5&background=EBF4FF
                                    @else
                                        https://ui-avatars.com/api/?name=Guest&color=7F9CF5&background=EBF4FF @endif
                                "
                                alt="">
                            <div>
                                <div class="font-medium text-gray-700 text-base">
                                    {{ $message->teacher->user->name }}</div>
                                <div class="font-medium text-gray-700 text-sm mb-2">
                                    {{ $message->teacher->user->name }}</div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">{{ $message->message }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>