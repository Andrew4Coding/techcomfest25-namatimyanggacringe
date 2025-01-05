@php
    $selected_theme = $course->subject;

    $theme = config('constants.theme')[$selected_theme];
@endphp

@props(['course'])
<a href="{{ url('/courses/' . $course->id) }}" class="no-underline text-black h-fit">
    <div class="w-full overflow-hidden shadow-lg h-full rounded-xl md:max-h-64 lg:max-h-80 hover:scale-[102%] duration-300 course-{{ $course->theme }}"
        style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] ?? $theme['secondary'] }}">

        <div class="px-6 py-4 flex flex-col justify-between h-full relative grow min-h-52">
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
            <div class="absolute inset-0 flex items-center justify-center -bottom-20">
                <img src="{{ asset('subject-mascots/' . $course->subject . '.png') }}" alt="Icon"
                    class="w-80 h-full max-h-52 object-contain">
            </div>
            <div class="flex justify-between">
                <div class="font-bold text-xl mb-2 z-50" style="color: {{ $theme['tertiary'] }}">{{ $course->name }}</div>
                <div class="font-medium px-5 rounded-full w-fit h-fit flex items-center text-white z-20"
                    style="background-color: {{ $theme['secondary'] }}; }}">{{ $course->class_code }}</div>
            </div>
            <div class="font-medium text-sm mb-2 absolute bottom-4 right-4 bg-white px-3 py-1 rounded-full"
                style="color: {{ $theme['tertiary'] }}">
                {{ $course->teacher->user ? $course->teacher->user->name : 'Teacher' }}
            </div>
        </div>
    </div>
</a>
