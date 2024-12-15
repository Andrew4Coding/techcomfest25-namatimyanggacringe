@php
    $selected_theme = $course->theme;

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

        <div class="p-10 mb-10 course-{{ $selected_theme }} rounded-xl">
            <div class="flex justify-between items-center mb-4 w-full">
                <h1 class="text-2xl font-extrabold">{{ $course->name }}</h1>
                <div class="flex gap-4 items-center">
                    <span class="badge badge-primary py-2 font-medium text-sm border-none"
                        style="background-color: {{ $theme['secondary'] }}; color: {{ $theme['tertiary'] }}"
                        onclick="copyToClipboard('{{ $course->class_code }}')">
                        {{ $course->class_code }}</span>

                    @if (Auth::user()->userable_type == 'App\Models\Teacher')
                        @if ($isEdit)
                            <x-lucide-pencil class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer"
                                onclick="edit_course_modal.showModal()" />
                        @else
                            <a href="{{ route('course.show.edit', ['id' => $course->id]) }}">
                                <x-lucide-pencil class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer" />
                            </a>
                        @endif
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
            <div class="flex justify-between items-center">
                <p class="text-sm mb-6 font-normal leading-loose">{{ $course->description }}</p>
                <span class="text-sm font-medium">
                    {{$course->teacher->user->name}}
                </span>
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

        document.getElementById('upload-pdf-button').addEventListener('click', function() {
            var form = document.getElementById('upload-pdf-form');
            var formData = new FormData(form);

            console.log('Uploading PDF...');

            fetch("{{ route('course.upload.file', ['courseId' => $course->id]) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    if (data['file_path']) {
                        alert('PDF uploaded successfully');
                        const cleaned = data.file_path.replace(/\\/g, "/");
                        const url = "https://techcomfest.s3.ap-southeast-2.amazonaws.com/" + cleaned;
                        console.log(new URL(url).href);

                    } else {
                        alert('Failed to upload PDF');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while uploading the PDF');
                });
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
