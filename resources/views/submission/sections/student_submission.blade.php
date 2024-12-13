@php
    $role = auth()->user()->userable_type;
@endphp
@props(['submission', 'submissionItem', 'canSubmit'])

<!-- Submission Status -->
<section class="bg-gray-100 shadow-md rounded-lg p-6 mb-8">
    <h3 class="font-bold text-2xl text-gray-800 mb-4">Submission Status</h3>
    <p class="text-gray-600">Check your submission status here (placeholder for future implementation).</p>
    <div class="flex flex-col gap-4 mt-4">
        <div class="flex items-center justify-between bg-gray-200 p-4 rounded-md">
            @if ($submissionItem)
                <a href="{{ $submissionItem->submission_urls }}" class="text-blue-500 hover:underline" target="_blank">
                    {{ basename($submissionItem->submission_urls) }}
                </a>
            @else
                <p class="text-gray-600">No submission yet.</p>
            @endif
        </div>
    </div>
</section>

<!-- Number of Attempts -->
<section class="bg-gray-100 shadow-md rounded-lg p-6 mb-8">
    <h3 class="font-bold text-2xl text-gray-800 mb-4">Number of Attempts</h3>
    <p class="text-gray-600">You have tried to submit {{ $submissionItem->attempts ?? 0 }} / {{ $submission->max_attempts }} times.</p>
</section>

<!-- Submit Your Work -->
<section class="bg-gray-100 shadow-md rounded-lg p-6 mb-8">
    <h3 class="font-bold text-2xl text-gray-800 mb-4">Submit Your Work</h3>
    @if ($canSubmit)
        <form action="{{ route('submission.submit', $submission->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf
            <div class="flex flex-col items-start gap-2">
                <label for="submission_file" class="font-semibold text-lg text-gray-700">Upload File</label>
                <input required type="file" name="file" id="submission_file" accept=".pdf,.zip" class="file-input file-input-primary p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <button type="submit" class="btn btn-primary bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                {{ $submissionItem ? 'Resubmit' : 'Submit' }}
            </button>
        </form>
    @else
        <p class="text-gray-600">Submission is currently closed.</p>
    @endif
</section>

<!-- Grade and Comments -->
<section class="bg-gray-100 shadow-md rounded-lg p-6 mb-8">
    <h3 class="font-bold text-2xl text-gray-800 mb-4">Grade and Comments</h3>
    <div class="flex flex-col gap-4">
        <div class="flex items-center">
            <b class="text-gray-700 mr-2">Grade:</b>
            <p class="@if ($submissionItem->grade > 80) text-green-500 @elseif($submissionItem->grade > 60) text-orange-500 @elseif($submissionItem->grade > 50) text-yellow-500 @else text-red-500 @endif">
                {{ $submissionItem->grade ?? 'Not graded yet' }}
            </p>
        </div>
        <div class="flex flex-col">
            <b class="text-gray-700 mb-2">Teacher's Comments:</b>
            <p class="text-gray-600">{{ $submissionItem->comment ?? 'No comments yet' }}</p>
        </div>
    </div>
</section>
