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
    </div>

    <!-- Submission Status -->
    <section class="bg-gray-100 shadow-md rounded-lg p-6 mb-8">
        <h3 class="font-bold text-2xl text-gray-800 mb-4">Submission Status</h3>
        <p class="text-gray-600">Check your submission status here (placeholder for future implementation).</p>
    </section>

    <!-- Submit Your Work -->
    <section class="bg-white shadow-md rounded-lg p-6">
        <h3 class="font-bold text-2xl text-gray-800 mb-4">Submit Your Work</h3>
        <form action="{{ route('submission.submit', $submission->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="flex flex-col items-start">
                <label for="submission_file" class="font-semibold text-lg text-gray-700">Upload File</label>
                <input type="file" name="file" id="submission_file" class="mt-2 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white font-bold rounded-md hover:bg-blue-600 transition duration-300">
                Submit
            </button>
        </form>
    </section>
</div>
@endsection
