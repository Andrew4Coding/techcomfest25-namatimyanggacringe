@extends('layout.layout')
@section('content')
    <section class="flex flex-col md:flex-row justify-between">
        <dialog id="enroll_class_modal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Enroll in a Class</h3>
                <form method="POST" action="{{ route('course.enroll') }}" id="enroll_class_form">
                    @csrf
                    <div class="mb-4">
                        <label for="enroll_class_code" class="block text-sm font-medium text-gray-700">Class Code</label>
                        <input type="text" name="class_code" id="enroll_class_code" class="input input-bordered w-full"
                            required />
                    </div>
                    <div class="modal-action">
                        <button type="button" class="btn" onclick="hideModal('enroll_class_modal')">Cancel</button>
                        <button type="submit" class="btn btn-primary">Enroll</button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

        <div class="space-y-2 mb-4 md:mb-0">
            <h1 class="text-3xl font-bold">Course List</h1>
            <p class="font-medium bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 text-transparent bg-clip-text">
                Siap untuk melanjutkan perjalanan belajarmu?
            </p>
        </div>

        {{-- If User is a teacher --}}
        @if (Auth::user()->userable_type == 'App\Models\Teacher')
            <button onclick="add_course_modal.showModal()" class="btn btn-primary">
                Add Course
            </button>
        @else
            @if (!$courses->isEmpty())
                <button onclick="enroll_class_modal.showModal()" class="btn btn-primary">
                    Enroll a Class
                </button>
            @endif
        @endif
    </section>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 min-h-[50vh] py-4">
        @if ($courses->isEmpty())
            <div class="w-full flex flex-col gap-4 items-center justify-center col-span-3">
                @if (Auth::user()->userable_type == 'App\Models\Teacher')
                    <h1 class="text-xl font-semibold">Kamu belum membuat course apapun</h1>
                    <button class="btn btn-primary" onclick="add_course_modal.showModal()">
                        Add Course
                    </button>
                @else
                    <h1 class="text-xl font-semibold">Kamu belum memiliki course apapun</h1>
                    <button class="btn btn-primary" onclick="enroll_class_modal.showModal()">
                        Enroll a Class
                    </button>
                @endif
            </div>
        @else
            @foreach ($courses as $course)
                @include('course.components.course_card', ['course' => $course])
            @endforeach
        @endif
    </div>

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
                    <input name="class_code" id="class_code" class="input input-bordered w-full" required pattern="[A-Za-z]{5}" title="Class code must be 5 letters" />
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="hideModal('add_course_modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">+ Create</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endsection
