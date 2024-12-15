<div class="flex w-full justify-between items-center gap-10 pr-5">
    @if ($item->course_itemable_type === 'App\Models\Material')
        <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5 justify-between">
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                    <x-lucide-file class="w-4 h-4" />
                </div>
                <div>
                    <p class="font-semibold">{{ $item['name'] }}</p>
                    <p>{{ $item['description'] }}</p>
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
                    <div>
                        <p class="font-semibold">{{ $item['name'] }}</p>
                        <p>{{ $item['description'] }}</p>
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
                    <div>
                        <p class="font-semibold">{{ $item['name'] }}</p>
                        <p>{{ $item['description'] }}</p>
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
                    <div>
                        <p class="font-semibold">{{ $item['name'] }}</p>
                        <p>{{ $item['description'] }}</p>
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
                    <div>
                        <p class="font-semibold">{{ $item['name'] }}</p>
                        <p>{{ $item['description'] }}</p>
                    </div>
                </div>
            </div>
        </a>
    @endif

    @if ($isEdit)
        <div class="flex gap-4 mt-5">
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
    @endif
</div>
