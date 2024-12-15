@php
    $selected_theme = $course->subject;

    $theme = config('constants.theme')[$selected_theme];
@endphp

@props(['course'])
<a href="{{ url('/courses/' . $course->id) }}" class="no-underline text-black h-fit">
    <div class="w-full overflow-hidden shadow-lg h-full rounded-xl md:max-h-64 lg:max-h-80 hover:scale-105 duration-300 course-{{$course->theme}}"
        style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] ?? $theme['secondary']}}">
        <div class="px-6 py-4 flex flex-col justify-between h-full relative grow min-h-52">
            <div class="absolute inset-0 flex items-center justify-center -bottom-20">
                <img src="{{ asset('subject-mascots/' . $course->subject . '.png') }}" alt="Icon" class="w-80 h-full object-contain">
            </div>
            <div class="flex justify-between">
                <div class="font-bold text-xl mb-2"
                    style="color: {{ $theme['tertiary'] }}"
                >{{ $course->name }}</div>
                <div class="font-medium px-5 rounded-full w-fit h-fit flex items-center text-white"
                    style="background-color: {{ $theme['secondary'] }}; }}">{{ $course->class_code }}</div>
            </div>
            <div class="font-medium text-sm mb-2 absolute bottom-4 right-4"
                style="color: {{ $theme['tertiary'] }}"
            >
                {{ $course -> teacher -> user ? $course -> teacher -> user -> name : 'Teacher' }}
            </div>
        </div>
    </div>
</a>
