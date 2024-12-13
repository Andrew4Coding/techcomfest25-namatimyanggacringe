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
                <li>{{ $course->name }}</li>
            </ul>
        </div>

        <div class="p-5 mb-10 course-{{$selected_theme}} rounded-xl">
            <h1 class="text-xl font-extrabold mb-4">{{ $course->name }}</h1>
            <div class="flex gap-2 items-center">
                <p class="text-sm mb-6 font-medium">{{ $course->description }}</p>
                <san class="badge badge-primary py-2 font-medium text-sm mb-6"
                    style="background-color: {{ $theme['secondary'] }}; color: {{ $theme['tertiary'] }}"
                >{{ $course->class_code }}</span>
            </div>
        </div>


        @include('course.sections.course_list', ['course' => $course, 'courseSections' => $courseSections])
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
    </script>
@endsection
