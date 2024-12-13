@php
    $colorSet = [
        [
            'bg' => '#C6E4DC',
            'fg' => '#39816E',
        ],
        [
            'bg' => '#DACCEF',
            'fg' => '#5E4584',
        ],
        [
            'bg' => '#C4DDF1',
            'fg' => '#3C82A3',
        ],
        [
            'bg' => '#F7EDC9',
            'fg' => '#E2BF3F',
            'text' => '#705801'
        ],
        [
            'bg' => '#F2C4E0',
            'fg' => '#6D2752',
        ],
    ];

    $randomColor = $colorSet[array_rand($colorSet)];

@endphp

@props(['course'])
<a href="{{ url('/courses/' . $course->id) }}" class="no-underline text-black h-fit">
    <div class="max-w-sm overflow-hidden shadow-lg h-full rounded-xl min-h-52 md:max-h-64 lg:max-h-80 hover:scale-105 duration-300"
        style="background-color: {{ $randomColor['bg'] }}; color: {{ $randomColor['text'] ?? $randomColor['fg']}}">
        <div class="px-6 py-4 flex flex-col justify-between h-full">
            <div class="flex justify-between">
                <div class="font-bold text-xl mb-2">{{ $course->name }} Kasiyah</div>
                <div class="font-medium px-5 rounded-full w-fit h-fit flex items-center text-white"
                    style="background-color: {{ $randomColor['fg'] }}; color: {{
                    // if it has text color, use it, otherwise use white
                    $randomColor['text'] ?? 'white'
                    }}">{{ $course->class_code }}</div>
            </div>
            <div class="font-medium text-sm mb-2">{{ 'Dr. kasiyah' }}</div>
        </div>
    </div>
</a>
