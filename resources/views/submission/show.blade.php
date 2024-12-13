@extends('layout.layout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs text-sm mb-6">
            <ul class="flex space-x-2 text-gray-600">
                <li><a href="/courses" class="hover:text-blue-500">Courses</a></li>
                <li><a href="#" class="hover:text-blue-500">Submissions</a></li>
                <li><a class="font-semibold">{{ $submission->id }}</a></li>
            </ul>
        </div>

        <!-- Submission Details -->
        <div class="rounded-lg mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-4">{{ $submission->courseItem->name }}</h1>
            <p class="text-lg text-gray-600 mb-6">{{ $submission->courseItem->description }}</p>
            <p class="text-lg text-gray-800 mb-6">{{ $submission->content }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <b class="text-gray-700 mr-2">Opened At:</b>
                    <p class="text-gray-600">{{ $submission->opened_at }}</p>
                </div>
                <div class="flex items-center">
                    <b class="text-gray-700 mr-2">Due At:</b>
                    <p class="text-gray-600">{{ $submission->due_date }}</p>
                </div>
            </div>
            <button
                class="mt-4 px-4 py-2 bg-yellow-500 text-white font-bold rounded-md hover:bg-yellow-600 transition duration-300"
                onclick="document.getElementById('edit_submission_modal').showModal();">
                Edit Submission Detail
            </button>
        </div>

        <!-- Submission Status -->
        <section class="bg-gray-100 shadow-md rounded-lg p-6 mb-8">
            <h3 class="font-bold text-2xl text-gray-800 mb-4">Submission Status</h3>
            <p class="text-gray-600">Check your submission status here (placeholder for future implementation).</p>
            {{-- Show Submissions --}}
            <div class="flex flex-col gap-4 mt-4">
                <div class="flex items center justify-between bg-gray-200 p-4 rounded-md">
                    @foreach ($submissionItems as $submissionItem)
                        <div class="flex items-center justify-between bg-gray-200 p-4 rounded-md">
                            <a href="{{ $submissionItem -> submission_urls }}" class="text-blue-500 hover:underline" target="_blank">{{ basename($submissionItem->submission_urls) }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Submit Your Work -->
        <section class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-bold text-2xl text-gray-800 mb-4">Submit Your Work</h3>
            <form action="{{ route('submission.submit', $submission->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div class="flex flex-col items-start">
                    <label for="submission_file" class="font-semibold text-lg text-gray-700">Upload File</label>
                    <input type="file" name="file" id="submission_file"
                        class="mt-2 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white font-bold rounded-md hover:bg-blue-600 transition duration-300">
                    Submit
                </button>
            </form>
        </section>
    </div>

    <!-- Edit Submission Modal -->
    <dialog id="edit_submission_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Edit Submission Detail</h3>
            <form method="POST" action="{{ route('submission.update', $submission->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full"
                        value="{{ $submission->courseItem->name }}" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full" required>{{ $submission->courseItem->description }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <input type="text" name="content" id="content" class="input input-bordered w-full"
                        value="{{ $submission->content }}" required />
                </div>
                <div class="mb-4">
                    <label for="opened_at" class="block text-sm font-medium text-gray-700">Opened At</label>
                    <input type="datetime-local" name="opened_at" id="opened_at" class="input input-bordered w-full"
                        value="{{ \Carbon\Carbon::parse($submission->opened_at)->format('Y-m-d\TH:i') }}" required />
                </div>
                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="datetime-local" name="due_date" id="due_date" class="input input-bordered w-full"
                        value="{{ \Carbon\Carbon::parse($submission->due_date)->format('Y-m-d\TH:i') }}" required />
                </div>
                <div class="mb-4">
                    <label for="file_types" class="file-input block text-sm font-medium text-gray-700">File Types</label>
                    <input type="text" name="file_types" id="file_types" class="input input-bordered w-full"
                        value="{{ $submission->file_types }}" required />
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                        onclick="document.getElementById('edit_submission_modal').close();">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endsection
