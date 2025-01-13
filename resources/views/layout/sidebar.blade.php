@php
    $PATH = env('AWS_URL');
@endphp

<div x-data="{ open: false }" class="relative h-screen max-w-[120px] hidden sm:block" @click.away="open = false">
    <div
        :class="open ? 'w-[250px]' : 'w-[120px]'" 
        class="z-50 top-0 left-0 h-screen bg-[#F6F9FA] flex flex-col items-center py-5 transform transition-all duration-500 ease-[cubic-bezier(0.4, 0.0, 0.2, 1)] relative">

        <!-- Toggle Button -->
        <div 
            @click="open = !open" 
            class="top-8 h-12 bg-black absolute -right-0 flex items-center justify-center rounded-l-lg cursor-pointer transition-transform duration-500 ease-[cubic-bezier(0.4, 0.0, 0.2, 1)]">
            <div
                class="duration-300"
                :class="open ? 'rotate-180' : 'rotate-0'"
            >
                <x-lucide-chevron-right class="w-4 h-4 text-white"/>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="flex flex-col gap-6 items-center mt-5">
            <img src="{{ asset('mindora-icon.png') }}" alt="Icon" class="w-8 h-8">
            <a href="/dashboard" class="w-full">
                <div class="tooltip tooltip-right w-full" data-tip="Dashboard">
                    <button 
                        class="btn w-full h-14 bg-transparent shadow-none hover:bg-[#E3ECF1] border-none rounded-full flex justify-start">
                        <x-lucide-layout-grid class="w-6 h-6" />
                        <span
                            x-show="open"
                            x-transition:enter="transition-opacity ease-out duration-300 delay-500"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity ease-in duration-100 delay-0"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="ml-4 font-semibold"
                        >
                            Dashboard
                        </span>
                    </button>
                </div>
            </a>
            <a href="/courses" class="w-full">
                <div class="tooltip tooltip-right w-full" data-tip="Courses">
                    <button
                        class="btn w-full h-14 bg-transparent shadow-none hover:bg-[#E3ECF1] border-none rounded-full flex justify-start">
                        <x-lucide-laptop class="w-6 h-6" />
                        <span
                            x-show="open"
                            x-transition:enter="transition-opacity ease-out duration-300 delay-500"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity ease-in duration-100 delay-0"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="ml-4 font-semibold"
                        >
                            Courses
                        </span>
                    </button>
                </div>
            </a>
            <a href="/" class="w-full">
                <div class="tooltip tooltip-right w-full" data-tip="AI Forum">
                    <button
                        class="btn w-full h-14 bg-transparent shadow-none hover:bg-[#E3ECF1] border-none rounded-full flex justify-start">
                        <x-lucide-messages-square class="w-6 h-6" />
                        <span
                            x-show="open"
                            x-transition:enter="transition-opacity ease-out duration-300 delay-500"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity ease-in duration-100 delay-0"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="ml-4 font-semibold"
                        >
                            Forum
                        </span>
                    </button>
                </div>
            </a>
            <a href="/flashcard" class="w-full">
                <div class="tooltip tooltip-right w-full" data-tip="AI Flashcard">
                    <button
                        class="btn w-full h-14 bg-transparent shadow-none hover:bg-[#E3ECF1] border-none rounded-full flex justify-start">
                        <x-lucide-sparkles class="w-6 h-6" />
                        <span
                            x-show="open"
                            x-transition:enter="transition-opacity ease-out duration-300 delay-500"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity ease-in duration-100 delay-0"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="ml-4 font-semibold"
                        >
                            Flashcard
                        </span>
                    </button>
                </div>
            </a>
        </div>

        <!-- Footer Section -->
        <div class="flex flex-col h-full items-end justify-end gap-6">
            <div class="tooltip tooltip-right w-full" data-tip="Logout">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    <button class="btn w-full h-14 bg-transparent shadow-none hover:bg-[#E3ECF1] border-none rounded-full">
                        @csrf
                        <x-lucide-log-out class="w-6 h-6" />
                        <span
                            x-show="open"
                            x-transition:enter="transition-opacity ease-out duration-300 delay-500"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity ease-in duration-100 delay-0"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="ml-4 font-semibold"
                        >
                            Logout
                        </span>

                    </button>
                </form>
            </div>

            <a href="/profile" class="w-full">
                <div class="tooltip tooltip-right w-full flex justify-center" data-tip="Profile">
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
</div>
