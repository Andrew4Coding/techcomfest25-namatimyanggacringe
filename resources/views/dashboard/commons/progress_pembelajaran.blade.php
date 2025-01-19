<section class="w-full p-5 md:p-10 bg-white shadow-smooth rounded-xl">
    <h3 class="mb-5">Progres Pembelajaran</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @if (!$courses || count($courses) === 0)
            <div
                class="p-5 w-full h-full flex items-center justify-center flex-col min-h-72 gap-2 col-span-4 text-center">
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
                @include('dashboard.commons.course_card')
            @endforeach
        @endif
    </div>
</section>