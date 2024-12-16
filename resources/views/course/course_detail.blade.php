@php
    $selected_theme = $course->subject;

    $theme = config('constants.theme')[$selected_theme];
@endphp

@extends('layout.layout')
@section('content')
    <main class="w-full">
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="/courses">Courses</a></li>
                <li>
                    <a href="{{ route('course.show', ['id' => $course->id]) }}">
                        {{ $course->name }}
                    </a>
                </li>
                @if ($isEdit)
                    <li>Edit</li>
                @endif
            </ul>
        </div>

        <div class="p-4 mb-10 rounded-xl relative min-h-52 overflow-hidden"
            style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] }};">

            <!-- Adjusted Image Styling -->
            <div class="absolute inset-0 pointer-events-none top-10 left-20">
                <img src="{{ asset('subject-mascots/' . $course->subject . '.png') }}" alt="Icon"
                    class="w-80 object-contain z-0">
            </div>

            <div class="z-10">
                <div class="flex justify-between items-center mb-4 w-full">
                    <h1 class="text-xl font-bold">{{ $course->name }}</h1>
                    <div class="flex gap-4 items-center">
                        <span class="badge badge-primary py-2 font-medium text-sm border-none text-white"
                            style="background-color: {{ $theme['secondary'] }};"
                            onclick="copyToClipboard('{{ $course->class_code }}')">
                            {{ $course->class_code }}
                        </span>

                        @if (Auth::user()->userable_type == 'App\Models\Teacher')
                            @if ($isEdit)
                                <x-lucide-pencil class="min-w-4 h-4 hover:scale-105 duration-150 cursor-pointer"
                                    onclick="edit_course_modal.showModal()" />
                            @else
                                <a href="{{ route('course.show.edit', ['id' => $course->id]) }}">
                                    <x-lucide-pencil class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer" />
                                </a>
                            @endif
                        @else
                            <button
                                onclick="document.getElementById('unenroll_course_modal_{{ $course->id }}').showModal();">
                                <x-lucide-door-open class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer" />
                            </button>
                            <dialog id="unenroll_course_modal_{{ $course->id }}" class="modal text-black">
                                <div class="modal-box">
                                    <h3 class="font-semibold text-lg">Confirm Unenrollment</h3>
                                    <p>Are you sure you want to unenroll from this Course?</p>
                                    <div class="modal-action">
                                        <button type="button" class="btn"
                                            onclick="document.getElementById('unenroll_course_modal_{{ $course->id }}').close();">Cancel</button>
                                        <form method="POST"
                                            action="{{ route('course.unenroll', ['courseId' => $course->id]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-error">Unenroll</button>
                                        </form>
                                    </div>
                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button>close</button>
                                </form>
                            </dialog>
                        @endif

                        @if ($isEdit)
                            <div class="flex w-full gap-6">
                                <x-lucide-trash
                                    class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-red-500 hover:rotate-12"
                                    onclick="document.getElementById('delete_course_modal_{{ $course->id }}').showModal();" />
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex justify-between items-center absolute right-4 bottom-4">
                    <span class="text-sm font-medium" style="color: {{ $theme['tertiary'] }};">
                        {{ $course->teacher->user ? $course->teacher->user->name : 'Teacher' }}
                    </span>
                </div>
            </div>
        </div>

        @if ($tab == 'overview' || $tab == '')
            @include('course.sections.course_list', [
                'course' => $course,
                'courseSections' => $courseSections,
            ])
        @endif

    </main>

    <script>
        // on DOM load detect if session contains error
        document.addEventListener('DOMContentLoaded', function() {
            if (sessionStorage.getItem('error')) {
                alert(sessionStorage.getItem('error'));
            }
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Class code copied to clipboard');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endsection
