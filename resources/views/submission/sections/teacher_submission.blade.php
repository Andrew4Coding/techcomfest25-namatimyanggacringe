@php
    use Illuminate\Support\Str;
    $role = auth()->user()->userable_type;
    $PATH = env('AWS_URL');
@endphp
@props(['submission', 'submissionItems'])
<table class="table table-zebra">
    <thead>
        <tr>
            <th>Student</th>
            <th>Submission</th>
            <th>Grade</th>
            <th>Comment</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($submissionItems as $item)
            <tr>
                <td class="flex gap-2 items-center">
                    <img src="
                        @if ($item->student->user->profile_photo) {{ $PATH . $item->student->user->profile_photo }}                        
                        @else
                            {{ asset('images/default-profile.png') }} @endif
                        "
                        class="rounded-full w-8 h-8 mr-2" alt="">
                    {{ $item->student->user->name }}
                </td>
                <td>
                    <a href="{{ $PATH . $item->submission_urls }}" class="text-blue-500 hover:underline"
                        target="_blank">{{ basename($item->submission_urls) }}
                    </a>
                </td>
                <td>
                    @if ($item->grade)
                        <span
                            class="@if ($item->grade > 80) text-green-500 @elseif($item->grade > 60) text-orange-500 @elseif($item->grade > 50) text-yellow-500 @else text-red-500 @endif">
                            {{ $item->grade }}
                        </span>
                    @else
                        <span>Not graded</span>
                    @endif
                </td>
                <td>
                    {{ Str::limit($item->comment, 50) }}
                </td>
                <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                <td>{{ $item->updated_at->format('d M Y, H:i') }}</td>
                <td>
                    <button class="btn btn-primary"
                        onclick="document.getElementById('grade_submission_modal_{{ $item->id }}').showModal();">
                        @if ($item->grade)
                            Regrade
                        @else
                            Grade
                        @endif
                    </button>
                    <dialog id="grade_submission_modal_{{ $item->id }}" class="modal">
                        <div class="modal-box">
                            <h3 class="font-semibold text-lg">Grade Submission</h3>
                            <form method="POST"
                                action="{{ route('submission.grade', ['submissionItemId' => $item->id]) }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="grade" class="block text-sm font-medium text-gray-700">Grade</label>
                                    <input type="number" name="grade" id="grade" value="{{ $item->grade }}"
                                        class="input input-bordered w-full" required min="0" max="100" />
                                </div>
                                <div class="mb-4">
                                    <label for="comment" class="block text-sm font-medium text-gray-700">Comment
                                        (optional)
                                    </label>
                                    <textarea name="comment" id="comment" rows="3" class="textarea textarea-bordered w-full">{{ $item->comment }}</textarea>
                                </div>
                                <div class="modal-action">
                                    <button type="button" class="btn"
                                        onclick="document.getElementById('grade_submission_modal_{{ $item->id }}').close();">Batalkan</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No submissions found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Edit Submission Modal -->
<dialog id="edit_submission_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-semibold text-lg">Edit Submission Detail</h3>
        <form method="POST" action="{{ route('submission.update', $submission->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="input input-bordered w-full"
                    value="{{ $submission->courseItem->name }}" required />
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
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
            {{-- Max attemps --}}
            <div class="mb-4">
                <label for="attempts" class="block text-sm font-medium text-gray-700">Attempts</label>
                <input type="number" name="max_attempts" id="attempts" value="{{ $submission->max_attempts }}"
                    class="input input-bordered w-full" required min="1" />
            </div>
            <div class="mb-4">
                <label for="file_types" class="file-input block text-sm font-medium text-gray-700">File
                    Accepted File Types</label>
                <input type="text" name="file_types" id="file_types" class="input input-bordered w-full"
                    value="{{ $submission->file_types }}" required />
            </div>
            <div class="modal-action">
                <button type="button" class="btn"
                    onclick="document.getElementById('edit_submission_modal').close();">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
