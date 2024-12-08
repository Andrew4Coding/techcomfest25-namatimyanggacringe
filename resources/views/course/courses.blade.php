@extends('layout.layout')
@section('content')

    <section class="flex justify-between">
        <div class="space-y-2">
            <h1 class="text-3xl font-bold">Course List</h1>
            <p class="font-medium bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 text-transparent bg-clip-text">
                Siap untuk melanjutkan perjalanan belajarmu?
            </p>
        </div>
    
        <button onclick="add_course_modal.showModal()" class="btn btn-primary">
            Add Course
        </button>
    </section>

    <dialog id="add_course_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Create new Course</h3>
            <form method="POST" action="{{ route('course.create') }}" id="add_course_form">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Course Name</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="class_code" class="block text-sm font-medium text-gray-700">Class Code (5
                        Letters)</label>
                    <input name="class_code" id="class_code" class="input input-bordered w-full" required />
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="hideModal('tnc-agreement-titled')">Cancel</button>
                    <button type="submit" class="btn btn-primary">+ Create</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

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
@endsection
