<div class="p-5 md:p-10 bg-white shadow-smooth rounded-xl h-full">
    <h3 class="mb-5">Deadline Terdekat</h3>
    @if (!$deadlines || count($deadlines) === 0)
        <div class="p-5 w-full h-full flex items-center justify-center flex-col min-h-72 text-center gap-2">
            <img src="{{ asset('mascot-nunjuk.png') }}" alt="No Deadlines" class="w-[150px]">
            <h4 class="font-medium">Tidak Ada Deadline</h4>
            <p class="text-gray-400 text-sm">Belum ada deadline tugas yang mendekat</p>
        </div>
    @else
        <div class="w-full flex flex-col gap-4 h-full overflow-y-auto overflow-x-hidden mb-5 max-h-[300px]">
            @foreach ($deadlines as $deadline)
                @php
                    $selected_theme = $deadline['course']['subject'];
                    $theme = config('constants.theme')[$selected_theme];
                @endphp
                <a href="/submission/{{ $deadline['id'] }}">
                    <div class="w-full relative overflow-hidden shadow-lg rounded-3xl hover:scale-[101%] duration-300 course-{{ $selected_theme }} e"
                        style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] ?? $theme['secondary'] }}">
                        @if ($selected_theme == 'sosiologi')
                            <img src="{{ asset('corner/yellow-corner-left.png') }}" alt=""
                                class="absolute bottom-0 left-0 w-40 h-20 object-contain z-0">
                            <img src="{{ asset('corner/yellow-corner-right.png') }}" alt=""
                                class="absolute top-0 right-0 w-40 h-20 object-contain z-0">
                        @elseif ($selected_theme == 'ekonomi')
                            <img src="{{ asset('corner/green-corner-left.png') }}" alt=""
                                class="absolute bottom-0 -left-12 w-40 h-20 object-contain z-0">
                            <img src="{{ asset('corner/green-corner-right.png') }}" alt=""
                                class="absolute top-0 -right-12 w-40 h-20 object-contain z-0">
                        @elseif ($selected_theme == 'bahasa')
                            <img src="{{ asset('corner/blue-corner.png') }}" alt=""
                                class="absolute top-0 left-0 object-contain z-0">
                        @endif
                        <div class="px-6 py-4 flex flex-col justify-between ">
                            <div class="flex justify-between items-center">
                                <div class="">
                                    <div class="badge badge-primary font-normal text-sm mb-2 text-white"
                                        style="background-color: {{ $theme['secondary'] }};">
                                        {{ ucfirst($deadline['course']['subject']) }}</div>
                                    <div class="font-semibold text-lg mb-2" style="color: {{ $theme['tertiary'] }}">
                                        {{ $deadline['courseItem']['name'] }}</div>
                                </div>
                                <span
                                    class="">{{ $deadline['studentCount'] > 0 ? number_format(($deadline['submissionCount'] / $deadline['studentCount']) * 100, 2) : '0.00' }}%
                                    Terkumpul</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
