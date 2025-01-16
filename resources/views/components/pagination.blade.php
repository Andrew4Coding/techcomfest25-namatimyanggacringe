<form
    action="{{ route($route, ['page' => $page, 'search' => $search, 'subject' => request('subject'), 'take' => request('take')]) }}"
    class="w-full flex flex-col items-center justify-center gap-4 mt-4 flex-wrap">
    <div class="flex items-center gap-4">
        @if ($page > 1)
            <a href="{{ route($route, ['page' => $page - 1, 'search' => $search, 'subject' => request('subject'), 'take' => request('take')]) }}"
                class="flex items-center justify-center w-10 h-10 text-sm rounded-full bg-white hover:scale-[102%]">
                <x-lucide-chevron-left class="w-6 h-6"></x-lucide-chevron-left>
            </a>
        @endif

        @if ($availablePages > 3)
            @if ($page > 2)
                <a href="{{ route($route, ['page' => 1, 'search' => $search, 'subject' => request('subject'), 'take' => request('take')]) }}"
                    class="flex items-center justify-center w-10 h-10 text-sm rounded-full bg-white hover:scale-[102%]">
                    <div
                        class="w-10 h-10 text-sm rounded-full text-black flex items-center justify-center hover:scale-[102%]">
                        1
                    </div>
                </a>
                @if ($page > 3)
                    <span class="flex items-center justify-center w-10 h-10 text-sm">...</span>
                @endif
            @endif

            @for ($i = max(1, $page - 1); $i <= min($availablePages, $page + 1); $i++)
                <a href="{{ route($route, ['page' => $i, 'search' => $search, 'subject' => request('subject'), 'take' => request('take')]) }}"
                    class="flex items-center justify-center w-10 h-10 text-sm rounded-full bg-white hover:scale-[102%]">
                    <div
                        class="w-10 h-10 text-sm rounded-full {{ $page == $i ? 'bg-primary text-white' : 'text-black' }} flex items-center justify-center hover:scale-[102%]">
                        {{ $i }}
                    </div>
                </a>
            @endfor

            @if ($page < $availablePages - 1)
                @if ($page < $availablePages - 2)
                    <span class="flex items-center justify-center w-10 h-10 text-sm">...</span>
                @endif
                <a href="{{ route($route, ['page' => $availablePages, 'search' => $search, 'subject' => request('subject'), 'take' => request('take')]) }}"
                    class="flex items-center justify-center w-10 h-10 text-sm rounded-full bg-white hover:scale-[102%]">
                    <div
                        class="w-10 h-10 text-sm rounded-full text-black flex items-center justify-center hover:scale-[102%]">
                        {{ $availablePages }}
                    </div>
                </a>
            @endif
        @else
            @for ($i = 1; $i <= $availablePages; $i++)
                <a href="{{ route($route, ['page' => $i, 'search' => $search, 'subject' => request('subject'), 'take' => request('take')]) }}"
                    class="flex items-center justify-center w-10 h-10 text-sm rounded-full bg-white hover:scale-[102%]">
                    <div
                        class="w-10 h-10 text-sm rounded-full {{ $page == $i ? 'bg-primary text-white' : 'text-black' }} flex items-center justify-center hover:scale-[102%]">
                        {{ $i }}
                    </div>
                </a>
            @endfor
        @endif

        @if ($page < $availablePages)
            <a href="{{ route($route, ['page' => $page + 1, 'search' => $search, 'subject' => request('subject'), 'take' => request('take')]) }}"
                class="flex items-center justify-center w-10 h-10 text-sm rounded-full bg-white hover:scale-[102%]">
                <x-lucide-chevron-right class="w-6 h-6"></x-lucide-chevron-right>
            </a>
        @endif

        @php
            $take = request('take') ?? 10;
        @endphp
    </div>

    <div class="flex items-center min-w-[200px] gap-4">
        <input type="hidden" name="search" value="{{ $search }}">
        <input type="hidden" name="subject" value="{{ request('subject') }}">
        <input type="hidden" name="page" value="{{ $page }}">
        <select name="take" id="" class="select select-white max-w-[70px]"
            onchange="this.form.submit()"
        >
            <option value="5" {{ $take == 5 ? 'selected' : '' }}>5</option>
            <option value="10" {{ $take == 10 ? 'selected' : '' }}>10</option>
            <option value="20" {{ $take == 20 ? 'selected' : '' }}>20</option>
            <option value="30" {{ $take == 30 ? 'selected' : '' }}>30</option>
            <option value="40" {{ $take == 40 ? 'selected' : '' }}>40</option>
            <option value="50" {{ $take == 50 ? 'selected' : '' }}>50</option>
        </select>
        <p class="text-xs">
            per page
        </p>
    </div>
</form>
