@php
    $PATH = env('AWS_URL');
@endphp
@extends('layout.layout')
@section('content')
    <section class="flex flex-col md:flex-row justify-between items-start space-y-4 md:space-y-0 md:space-x-4">
        <div class="space-y-2 mb-4 md:mb-0">
            <h1 class="text-3xl font-semibold">AI-Powered Forum</h1>
            <p class="font-medium gradient-blue text-transparent bg-clip-text">
                Belajar bersama menjadi menyenangkan dan praktis.
            </p>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-10">
        @foreach ($forums as $forum)
            @php
                $subject = $forum->courseItem->courseSection->course->subject;
                $selected_theme = $subject;
                $theme = config('constants.theme')[$selected_theme];
            @endphp

            <a href="{{ url('/forum/' . $forum->id) }}" class="no-underline text-black h-fit">
                <div class="w-full relative overflow-hidden shadow-lg h-full rounded-xl md:max-h-64 lg:max-h-80 hover:scale-105 duration-300"
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
                    <div class="px-6 py-4 flex flex-col justify-between h-full relative grow min-h-52 z-50">
                        <div class="absolute inset-0 flex items-center justify-center -bottom-20">
                            <img src="{{ asset('subject-mascots/' . $subject . '.png') }}" alt="Icon"
                                class="w-80 h-full object-contain">
                        </div>
                        <div class="font-bold text-xl mb-2" style="color: {{ $theme['tertiary'] }}">
                            {{ $forum->courseItem->name }}
                        </div>
                    </div>
                </div>
            </a>
        @endforeach

        @if (empty($forums))
            <div class="flex flex-col items-center justify-center space-y-4 h-full min-h-[500px] col-span-3">
                <img src="{{ asset('mascot-love.png') }}" alt="Icon" class="w-52 h-auto">
                <h1 class="text-xl font-medium">
                    Belum ada Forum Saat ini
                </h1>
            </div>
        @endif

    </section>
@endsection
