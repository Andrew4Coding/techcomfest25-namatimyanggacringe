@php
    $role = auth()->user()->userable_type;
@endphp
@props(['submission', 'submissionItem', 'canSubmit'])
<!-- Submission Status -->
<section class="bg-gray-100 shadow-md rounded-lg p-6 mb-8">
    <h3 class="font-bold text-2xl text-gray-800 mb-4">Submission Status</h3>
    <p class="text-gray-600">Check your submission status here (placeholder for future implementation).</p>
    {{-- Show Submissions --}}
    <div class="flex flex-col gap-4 mt-4">
        <div class="flex items-center justify-between bg-gray-200 p-4 rounded-md">
            @if ($submissionItem)
                <a href="{{ $submissionItem->submission_urls }}" class="text-blue-500 hover:underline"
                    target="_blank">{{ basename($submissionItem->submission_urls) }}
                </a>
            @else
                <p class="text-gray-600">No submission yet.</p>
            @endif
        </div>
    </div>
</section>

<!-- Number of Attempts -->
<section class="rounded-lg">
    <h3 class="font-bold text-2xl text-gray-800 mb-4">Number of Attempts</h3>
    <p class="text-gray-600">You have tried to submit {{ $submissionItem->attempts }} /
        {{ $submission->max_attempts }} times.</p>
</section>

<!-- Submit Your Work -->
@if ($canSubmit)
    <section class="rounded-lg">
        <h3 class="font-bold text-2xl text-gray-800 mb-4">Submit Your Work</h3>
        <form action="{{ route('submission.submit', $submission->id) }}" method="POST" enctype="multipart/form-data"
            class="flex flex-col gap-4">
            @csrf
            <div class="flex flex-col items-start gap-2">
                <label for="submission_file" class="font-semibold text-lg text-gray-700">Upload File</label>
                <input required type="file" name="file" id="submission_file" accept=".pdf,.zip"
                    class="file-input file-input-primary -mt-2 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <button type="submit" class="btn btn-primary">
                {{ $submissionItem ? 'Submit' : 'Resubmit' }}
            </button>
        </form>
    </section>
@else
    <section class="rounded-lg">
        <h3 class="font-bold text-2xl text-gray-800 mb-4">Submit Your Work</h3>
        <p class="text-gray-600">Submission is currently closed.</p>
    </section>
@endif
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
                <label for="file_types" class="file-input block text-sm font-medium text-gray-700">File
                    Types</label>
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

<!-- Grade and Comments -->
<section class="rounded-lg">
    <h3 class="font-bold text-2xl text-gray-800 mb-4">Grade and Comments</h3>
    <div class="flex flex-col gap-4">
        <div class="flex items-center">
            <b class="text-gray-700 mr-2">Grade:</b>
            <p class="text-gray-600">{{ $submission->grade ?? 'Not graded yet' }}</p>
        </div>
        <div class="flex flex-col">
            <b class="text-gray-700 mb-2">Teacher's Comments:</b>
            <p class="text-gray-600">{{ $submission->comments ?? 'No comments yet' }}</p>
        </div>
    </div>
</section>
