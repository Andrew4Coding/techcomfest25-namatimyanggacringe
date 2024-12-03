@extends('layout.layout')
@section('content')
    <main class="px-20 pt-40">
        <div class="grid grid-cols-3 gap-4">
            @foreach ($courses as $course)
            <a href="{{ url('/courses/'.$course->id) }}" class="no-underline text-black">
                <div class="max-w-sm rounded overflow-hidden shadow-lg">
                    <img class="w-full" src="{{ $course->image }}" alt="{{ $course->title }}">
                    <div class="px-6 py-4">
                    <div class="font-bold text-xl mb-2">{{ $course->title }}</div>
                    <p class="text-gray-700 text-base">
                        {{ $course->description }}
                    </p>
                    </div>
                    <div class="px-6 pt-4 pb-2">
                        {{-- Map all Sections --}}
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">#{{ $course->code }}</span>
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{ $course->duration }} hours</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </main>
@endsection