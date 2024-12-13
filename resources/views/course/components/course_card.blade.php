@php
    $selected_theme = $course->theme;

    $theme = config('constants.theme')[$selected_theme];
@endphp

@props(['course'])
<a href="{{ url('/courses/' . $course->id) }}" class="no-underline text-black h-fit">
    <div class="max-w-sm overflow-hidden shadow-lg h-full rounded-xl min-h-52 md:max-h-64 lg:max-h-80 hover:scale-105 duration-300 course-{{$course->theme}} "
        style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] ?? $theme['secondary']}}">
        <div class="px-6 py-4 flex flex-col justify-between h-full">
            <div class="flex justify-between">
                <div class="font-bold text-xl mb-2">{{ $course->name }} Kasiyah</div>
                <div class="font-medium px-5 rounded-full w-fit h-fit flex items-center text-white"
                    style="background-color: {{ $theme['secondary'] }}; color: {{ $theme['tertiary'] }}">{{ $course->class_code }}</div>
            </div>
            <div class="font-medium text-sm mb-2">{{ 'Dr. kasiyah' }}</div>
        </div>
    </div>
</a>
