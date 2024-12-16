<div class="flex w-full justify-between items-center gap-10">
    @if ($item->course_itemable_type === 'App\Models\Material')
        <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5 justify-between">
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                    <x-lucide-file class="w-4 h-4" />
                </div>
                <div class="flex flex-col justify-center">
                    <p class="font-medium">{{ $item['name'] }}</p>
                    @if ($item['description'])
                        <p>{{ $item['description'] }}</p>
                    @endif
                </div>
            </div>
            <a href="{{ $item->courseItemable->file_url }}" target="_blank">
                <button class="btn btn-primary text-white">
                    <x-lucide-download class="w-4 h-4" />
                    <span class="hidden md:block">
                        Download
                    </span>
                </button>
            </a>
        </div>
    @elseif ($item->course_itemable_type === 'App\Models\Submission')
        <a href="{{ route('submission.show', ['submissionId' => $item->courseItemable->id]) }}" class="w-full">
            <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5 justify-between">
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                        <x-lucide-archive class="w-4 h-4 text-white" />
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="font-medium">{{ $item['name'] }}</p>
                        @if ($item['description'])
                            <p>{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @elseif ($item->course_itemable_type === 'App\Models\Forum')
        <a class="w-full" href="{{ route('forum.index', ['forumId' => $item->courseItemable->id]) }}">
            <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5">
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <x-lucide-message-square class="w-4 h-4 text-white" />
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="font-medium">{{ $item['name'] }}</p>
                        @if ($item['description'])
                            <p>{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @elseif ($item->course_itemable_type === 'App\Models\Attendance')
        <a href="{{ route('attendance.show', ['id' => $item->courseItemable->id]) }}" class="w-full">
            <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5 justify-between">
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <x-lucide-user class="w-4 h-4 text-white" />
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="font-medium">{{ $item['name'] }}</p>
                        @if ($item['description'])
                            <p>{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @elseif ($item->course_itemable_type === 'App\Models\Quiz')
        <a href="{{ route('quiz.show', ['quizId' => $item->courseItemable->id]) }}" class="w-full">
            <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5 justify-between">
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                        <x-lucide-clipboard class="w-4 h-4 text-white" />
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="font-medium">{{ $item['name'] }}</p>
                        @if ($item['description'])
                            <p>{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @endif

    @if ($isEdit)
        <div class="flex gap-4 mt-5 mr-5">
            <form method="POST" action="{{ route('course.item.toggle', ['id' => $item->id]) }}">
                @csrf
                <button type="submit">
                    @if ($item->isPublic)
                        <div class="tooltip tooltip-top" data-tip="Hide from students">
                            <x-lucide-eye class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-blue-500 hover:rotate-12" />
                        </div>
                    @else
                        <div class="tooltip tooltip-top" data-tip="Show to students">
                            <x-lucide-eye-off class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-blue-500 hover:rotate-12" />
                        </div>
                    @endif
                </button>
            </form>

            <div class="tooltip tooltip-top" data-tip="Delete Course Item">
                <x-lucide-trash onclick="document.getElementById('delete_courseitem_modal_{{ $item->id }}').showModal();" class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-red-500 hover:rotate-12" />
            </div>
        </div>
    @else
    <div class="tooltip tooltip-top">
        <div class="flex items-center mr-5">
            <input type="checkbox" class="checkbox checkbox-primary h-5 w-5 text-blue-600" {{ $item->courseItemProgress && $item->courseItemProgress->is_completed ? 'checked' : '' }} onchange="toggleCompletion('{{ $item->id }}', this.checked)" id="completion-checkbox-{{ $item->id }}">
        </div>
    </div>
    <script>
        function toggleCompletion(courseItemId, isChecked) {
            const checkbox = document.getElementById(`completion-checkbox-${courseItemId}`);
            checkbox.disabled = true;
            fetch(`/course-item/check/${courseItemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ is_completed: isChecked })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    location.reload();
                } else {
                    console.error(data.error);
                    checkbox.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                checkbox.disabled = false;
            });
        }
    </script>
    @endif
</div>
