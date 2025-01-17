<div class="mb-10">
    <h1 class="text-3xl font-bold">Halo, {{ Auth::user()->name }}!</h1>
    <p class="font-medium gradient-blue text-transparent bg-clip-text">
        Siap untuk melanjutkan perjalanan belajarmu?
    </p>
</div>
<section class="w-full p-10 bg-white shadow-smooth rounded-xl">
    <h3 class="mb-5">Progres Pembelajaran</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        @if (!$courses || count($courses) === 0)
            <div
                class="p-3 md:p-5 w-full h-full flex items-center justify-center flex-col min-h-72 gap-2 col-span-4 text-center">
                <img src="{{ asset('mindora-mascot.png') }}" alt="No Progress" class="w-[150px]">
                <h4 class="font-medium">Belum ada progres</h4>
                <p class="text-gray-400 text-sm">Belum ada progres pembelajaran yang dibuat oleh guru</p>
            </div>
        @else
            @foreach ($courses as $course)
                @php
                    $selected_theme = $course->subject;
                    $theme = config('constants.theme')[$selected_theme];
                @endphp
                @include('dashboard.components.student_course')
            @endforeach
        @endif
    </div>
</section>

<section class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 h-full">
    <div class="p-5 md:p-10 bg-white shadow-smooth rounded-xl h-full">
        <h3 class="mb-5">Deadline Terdekat</h3>
        @if (!$deadlines || count($deadlines) === 0)
            <div class="p-3 md:p-5 w-full h-full flex items-center justify-center flex-col min-h-72 text-center gap-2">
                <img src="{{ asset('mindora-mascot.png') }}" alt="No Deadlines" class="w-[150px]">
                <h4 class="font-medium">Tidak ada deadline</h4>
                <p class="text-gray-400 text-sm">Belum ada deadline tugas yang mendekat</p>
            </div>
        @else
            <div class="w-full flex flex-col gap-4 h-full overflow-y-auto overflow-x-hidden mb-5 max-h-[300px]">
                @foreach ($deadlines as $deadline)
                    @php
                        $selected_theme = $deadline['course']['subject'];
                        $theme = config('constants.theme')[$selected_theme];
                    @endphp
                    <a href="/submission/{{ $deadline['id'] }}">
                        <div class="w-full relative overflow-hidden shadow-lg rounded-3xl hover:scale-[101%] duration-300 course-{{ $selected_theme }} e"
                            style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] ?? $theme['secondary'] }}">
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
                            <div class="px-6 py-4 flex flex-col justify-between ">
                                <div class="flex justify-between items-center">
                                    <div class="">
                                        <div class="badge badge-primary font-normal text-sm mb-2 text-white"
                                            style="background-color: {{ $theme['secondary'] }};">
                                            {{ ucfirst($deadline['course']['subject']) }}</div>
                                        <div class="font-semibold text-lg mb-2"
                                            style="color: {{ $theme['tertiary'] }}">
                                            {{ $deadline['courseItem']['name'] }}</div>
                                    </div>
                                    <span
                                        class="">{{ $deadline['studentCount'] > 0 ? number_format(($deadline['submissionCount'] / $deadline['studentCount']) * 100, 2) : '0.00' }}%
                                        Terkumpul</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

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
</section>
