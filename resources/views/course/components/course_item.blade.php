<div class="flex w-full justify-between items-center gap-10 pr-5">
    @yield('courseitem')
    @if ($isEdit)
        <div class="flex gap-4">
            <form method="POST" action="{{ route('course.item.toggle', ['id' => $item->id]) }}">
                @csrf
                <button type="submit">
                    @if ($item->isPublic)
                        <div class="tooltip tooltip-right" data-tip="Hide from students">
                            <x-lucide-eye
                                class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-blue-500 hover:rotate-12" />
                        </div>
                    @else
                        <div class="tooltip tooltip-right" data-tip="Show to students">
                            <x-lucide-eye-off
                                class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-blue-500 hover:rotate-12" />
                        </div>
                    @endif
                </button>
            </form>

            <x-lucide-trash
                onclick="document.getElementById('delete_courseitem_modal_{{ $item->id }}').showModal();"
                class="w-4 h-4 hover:scale-105 duration-150 cursor-pointer hover:text-red-500 hover:rotate-12" />
        </div>
    @endif
</div>
