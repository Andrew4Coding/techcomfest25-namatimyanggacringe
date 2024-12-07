@extends('layout.layout')
@section('content')
    <main class="w-full">
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="/courses">Courses</a></li>
                <li>{{ $course->name }}</li>
            </ul>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold mb-4">{{ $course->name }}</h1>
            <p class="text-lg mb-6">{{ $course->description }}</p>
        </div>

        <div class="flex w-full gap-6">
            <button class="btn btn-error"
                onclick="document.getElementById('delete_course_modal_{{ $course->id }}').showModal();">
                <x-lucide-trash class="w-6 h-6" />
                Delete Course
            </button>
    
            <button onclick="edit_course_modal.showModal()" class="btn btn-secondary">
                Edit Course
            </button>
    
            <button onclick="add_section_modal.showModal()" class="btn btn-primary">
                + Add Section
            </button>
        </div>

        {{-- Show all sections --}}
        <div class="mt-8 flex flex-col gap-4">
            @foreach ($courseSections as $section)
                @include('course.components.section', ['section' => $section])
            @endforeach
        </div>

        @include('course.components.dialogs')
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
