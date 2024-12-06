@extends('layout.layout')
@section('content')
    <main class="px-20 py-40">
        {{-- <form id="upload-pdf-form">
            <label for="file" class="block text-sm font-medium text-gray-700">Upload PDF</label>
            <x-bladewind::filepicker name="file" accepted_file_types=".pdf" placeholder="Upload PDF" max_file_size="2"
                required />

            <button type="button" id="upload-pdf-button" class="w-full">
                Upload PDF
            </button>
        </form> --}}

        <div>
            <h1 class="text-3xl font-extrabold mb-4">{{ $course->name }}</h1>
            <p class="text-lg mb-6">{{ $course->description }}</p>
        </div>
        {{-- Show all sections --}}
        <div class="mt-8 flex flex-col gap-4">
            @foreach ($course->courseSections as $section)
                @include('course.components.section', ['section' => $section])
            @endforeach
        </div>
        <x-bladewind::button onclick="showModal('tnc-agreement-titled')" class="w-full">
            + Add Section
        </x-bladewind::button>
        <x-bladewind::modal ok_button_label="+ Create" title="Create new Section" name="tnc-agreement-titled"
            ok_button_action="document.getElementById('course-section-form').submit();"
        >
            <form method="POST" action="{{route('course.section.create', ['id' => $course->id])}}" id="course-section-form">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Section Name</label>
                    <x-bladewind::input type="text" name="name" id="name" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <x-bladewind::textarea name="description" id="description" rows="3" required />
                </div>
            </form>
        </x-bladewind::modal>
    </main>
    <script>
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
                        const url = "https://techcomfest.s3.ap-southeast-2.amazonaws.com/"+cleaned;
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
