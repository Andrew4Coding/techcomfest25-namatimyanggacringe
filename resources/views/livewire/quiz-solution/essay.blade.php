@php use App\Models\Teacher;use Illuminate\Support\Facades\Auth; @endphp
<section class="bg-white flex-1 p-10 shadow-md rounded-lg rounded-tl-none relative">
    {{-- Question and Pagination Info --}}
    <div class="flex justify-between items-center">
        <h1 id="question-content" class="text-lg font-medium">{{ $question['content'] }}</h1>
        <span class="text-sm text-gray-500">Question {{ $page }} / {{ $questionCount }}</span>
    </div>

    {{-- Answer Options  --}}
    <div class="my-4">
        <textarea
            class="textarea textarea-bordered w-full my-2 textarea-lg"
            placeholder="..." readonly>{{ $answer }}</textarea>
    </div>


    {{-- Feedback --}}
    <div class="w-full mt-10 flex flex-col items-start ">
        <div class="w-full flex items-center justify-between">
            <h2 class="text-base font-semibold">Feedback</h2>
            @if (Auth::user()->userable_type === Teacher::class)
                <button class="btn btn-sm" onclick="feedback_modal.showModal()">
                    <x-lucide-pencil class="w-4 h-4" />
                </button>
            @endif
        </div>
        <p class="block mt-4 text-sm text-gray-700">
            @if ($submissionItem->feedback === '')
                <span class="text-gray-500">Belum ada feedback</span>
            @else
                {{ $submissionItem->feedback }}
            @endif
        </p>
    </div>

    {{-- Actions: Flag, Next, Submit --}}
    <div class="mt-10 flex justify-between items-center">
        {{-- Flag Question Button --}}
        <label
            class="flex items-center gap-2 px-4 py-2 rounded-lg @if($flagged)  text-yellow-100 bg-yellow-400 hover:bg-yellow-500 @else text-yellow-500 bg-yellow-100 hover:bg-yellow-200 @endif">
            <input wire:model="flagged" readonly type="checkbox" class="hidden"
            />
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                 stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6.75 4.5h10.5a1.5 1.5 0 011.5 1.5v10.5a1.5 1.5 0 01-1.5 1.5H6.75M9 6.75l4.5 4.5L9 15.75"/>
            </svg>
            Flag Question
        </label>
        <button wire:click="$parent.prev" class="btn w-1/4 @if($page <= 1) btn-disabled @else btn-primary @endif">
            Back
        </button>
        <button wire:click="$parent.next"
                class="btn w-1/4 @if($page >= $questionCount) btn-disabled @else btn-primary @endif">
            Next
        </button>
        <dialog id="feedback_modal" class="modal">
            <div class="modal-box w-11/12 max-w-5xl">
                <h3 class="text-lg font-bold mb-3">Give Feedback</h3>
                <textarea wire:model="feedback" class="textarea w-full" rows="4"></textarea>
                <div class="modal-action">
                    <form method="dialog">
                        <!-- if there is a button, it will close the modal -->
                        <button class="btn" wire:click="saveFeedback">Simpan</button>
                    </form>
                </div>
            </div>
        </dialog>
    </div>
</section>
