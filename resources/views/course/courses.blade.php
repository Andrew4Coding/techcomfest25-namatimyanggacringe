@extends('layout.layout')
@section('content')
    <main class="px-20 pt-40">
        <x-bladewind::button onclick="showModal('tnc-agreement-titled')" class="w-full">
            + Add Course
        </x-bladewind::button>
        <x-bladewind::modal ok_button_label="+ Create" title="Create new Course" name="tnc-agreement-titled"
            ok_button_action="document.getElementById('add_course_form').submit();">
            <form method="POST" action="{{ route('course.create') }}" id="add_course_form">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Course Name</label>
                    <x-bladewind::input type="text" name="name" id="name" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <x-bladewind::textarea name="description" id="description" rows="3" required />
                </div>
            </form>
        </x-bladewind::modal>

        <div class="grid grid-cols-3 gap-4 min-h-[100vh]">
            @if ($courses->isEmpty())
                <p>No courses available.</p>
            @else
                @foreach ($courses as $course)
                    <a href="{{ url('/courses/' . $course->id) }}" class="no-underline text-black">
                        <div class="max-w-sm rounded overflow-hidden shadow-lg h-full">
                            <img class="w-full" src="{{ $course->image }}" alt="{{ $course->title }}">
                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2">{{ $course->title }}</div>
                                <p class="text-gray-700 text-base">
                                    {{ $course->description }}
                                </p>
                            </div>
                            <div class="px-6 pt-4 pb-2">
                                {{-- Map all Sections --}}
                                <span
                                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">#{{ $course->code }}</span>
                                <span
                                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{ $course->duration }}
                                    hours</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </main>
@endsection
