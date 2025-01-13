<div class="flex w-full justify-between items-center gap-10 pl-5"
    style="{{
        $item->is_public
            ? 'border-left: 4px solid #2563EB;'
            : 'border-left: 4px solid #F87171; opacity: 0.4;'
    }}"
>
    @if ($item->course_itemable_type === 'App\Models\Material')
        <div class="w-full flex items-center gap-4 my-5 justify-between">
            <div class="flex gap-4">
                <div class="w-10 h-10 flex items-center justify-center">
                    <x-lucide-file class="w-4 h-4 text-black" />
                </div>
                <div class="flex flex-col justify-center">
                    <a href="{{ $item->courseItemable->file_url }}" target="_blank" class="font-medium">
                        {{ $item['name'] }}
                    </a>
                    @if ($item['description'])
                        <p class="text-xs md:text-sm text-gray-500">{{ $item['description'] }}</p>
                    @endif
                </div>
            </div>
            <div class="tooltip tooltip-top mr-5 hidden lg:block" data-tip="Unduh Materi">
                <a href="{{ $item->courseItemable->file_url }}" target="_blank">
                    <button class="btn btn-primary text-white">
                        <x-lucide-download class="w-4 h-4" />
                    </button>
                </a>
            </div>
        </div>
    @elseif ($item->course_itemable_type === 'App\Models\Submission')
        <a href="{{ route('submission.show', ['submissionId' => $item->courseItemable->id]) }}" class="w-full">
            <div class="w-full flex items-center gap-4 my-5 justify-between">
                <div class="flex gap-4">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <x-lucide-archive class="w-4 h-4 text-black" />
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="font-medium">{{ $item['name'] }}</p>
                        @if ($item['description'])
                            <p class="text-xs md:text-sm text-gray-500">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @elseif ($item->course_itemable_type === 'App\Models\Forum')
        <a class="w-full" href="{{ route('forum.index', ['forumId' => $item->courseItemable->id]) }}">
            <div class="w-full flex items-center gap-4 my-5">
                <div class="flex gap-4">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <x-lucide-message-square class="w-4 h-4 text-black" />
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="font-medium">{{ $item['name'] }}</p>
                        @if ($item['description'])
                            <p class="text-xs md:text-sm text-gray-500">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @elseif ($item->course_itemable_type === 'App\Models\Attendance')
        <a href="{{ route('attendance.show', ['id' => $item->courseItemable->id]) }}" class="w-full">
            <div class="w-full flex items-center gap-4 my-5 justify-between">
                <div class="flex gap-4">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <x-lucide-user class="w-4 h-4 text-black" />
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="font-medium">{{ $item['name'] }}</p>
                        @if ($item['description'])
                            <p class="text-xs md:text-sm text-gray-500">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @elseif ($item->course_itemable_type === 'App\Models\Quiz')
        <a href="{{ route('quiz.edit', ['quizId' => $item->courseItemable->id]) }}" class="w-full">
            <div class="w-full flex items-center gap-4 my-5 justify-between">
                <div class="flex gap-4">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <x-lucide-clipboard class="w-4 h-4 text-black" />
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="font-medium">{{ $item['name'] }}</p>
                        @if ($item['description'])
                            <p class="text-xs md:text-sm text-gray-500">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @endif

    @if ($isEdit)
        <div class="flex gap-4 mr-5">
            <div class="tooltip tooltip-top" data-tip="{{ $item->is_public ? 'Hide from students' : 'Show to students' }}">
                @if ($item->is_public)
                    <x-lucide-eye
                        class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-blue-500 hover:rotate-12"
                        onclick="toggleVisibility('{{ $item->id }}', false)" />
                @else
                    <x-lucide-eye-off
                        class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-blue-500 hover:rotate-12"
                        onclick="toggleVisibility('{{ $item->id }}', true)" />
                @endif
            </div>

            <div class="tooltip tooltip-top" data-tip="Hapus Course Item">
                <x-lucide-trash
                    onclick="document.getElementById('delete_courseitem_modal_{{ $item->id }}').showModal();"
                    class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-red-500 hover:rotate-12" />
            </div>
        </div>

        <script>
            function toggleVisibility(courseItemId, isPublic) {
                fetch(`/courses/items/toggle/${courseItemId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            location.reload();
                        } else {
                            console.error(data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        </script>
    @else
        <div class="tooltip tooltip-top mr-5" data-tip="Tandai Selesai">
            <div class="flex items-center">
                <input type="checkbox" class="checkbox checkbox-primary h-5 w-5 text-blue-600"
                    {{ $item->courseItemProgress && $item->courseItemProgress->is_completed ? 'checked' : '' }}
                    onchange="toggleCompletion('{{ $item->id }}', this.checked)"
                    id="completion-checkbox-{{ $item->id }}">
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
                        body: JSON.stringify({
                            is_completed: isChecked
                        })
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
