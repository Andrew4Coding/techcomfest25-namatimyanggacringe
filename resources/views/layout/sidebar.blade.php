@php
    $PATH = env('AWS_URL');
@endphp

<div x-data="{ open: false }" class="relative h-screen" @click.away="open = false">
    <div :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed z-30 top-0 left-0 h-screen min-w-[120px] bg-white flex flex-col items-center py-24 transform transition-transform duration-300 ease-in-out">
        <div class="flex flex-col gap-6">
            <a href="/dashboard">
                <div class="tooltip tooltip-right" data-tip="Dashboard">
                    <button class="btn rounded-full w-14 h-14">
                        <x-lucide-layout-grid class="w-6 h-6" />
                    </button>
                </div>
            </a>
            <a href="/courses">
                <div class="tooltip tooltip-right" data-tip="Courses">
                    <button class="btn rounded-full w-14 h-14">
                        <x-lucide-laptop class="w-6 h-6" />
                    </button>
                </div>
            </a>
            <a href="/">
                <div class="tooltip tooltip-right" data-tip="Dashboard">
                    <button class="btn rounded-full w-14 h-14">
                        <x-lucide-atom class="w-6 h-6" />
                    </button>
                </div>
            </a>
            <a href="/">
                <div class="tooltip tooltip-right" data-tip="Dashboard">
                    <button class="btn rounded-full w-14 h-14">
                        <x-lucide-file class="w-6 h-6" />
                    </button>
                </div>
            </a>
        </div>
        <div class="flex flex-col h-full items-end justify-end">
            <button class="btn rounded-full w-14 h-14 bg-white border-none ">
                <x-lucide-settings class="w-6 h-6" />
            </button>
            <div class="tooltip tooltip-right" data-tip="Logout">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    <button class="btn rounded-full w-14 h-14 bg-white border-none ">
                        @csrf
                        <x-lucide-door-open class="w-6 h-6" />
                    </button>
                </form>
            </div>

            <a href="/profile">
                <div class="tooltip tooltip-right" data-tip="Profile">
                    </button>
                    <div class="w-14 h-14 rounded-full overflow-hidden bg-gray-300">
                        <img class="w-full object-cover"
                            src="
                            @if (Auth::user() && Auth::user()->profile_picture) {{ $PATH . auth()->user()->profile_picture }}
                            @elseif (Auth::user() && !Auth::user()->profile_picture)
                                https://ui-avatars.com/api/?name={{ Auth::user()->name }}&color=7F9CF5&background=EBF4FF
                            @else
                                https://ui-avatars.com/api/?name=Guest&color=7F9CF5&background=EBF4FF @endif
                        "
                            class="">
                    </div>
                </div>
            </a>
        </div>
    </div>
    <button @click="open = !open"
        class="absolute z-50 top-10 left-5 md:left-9 transform -translate-y-1/2 bg-white rounded-full p-3 hover:scale-105 duration-150">
        <img src="{{ asset('mindora-icon.png') }}" alt="Icon" class="min-w-8 h-8">
    </button>
</div>
