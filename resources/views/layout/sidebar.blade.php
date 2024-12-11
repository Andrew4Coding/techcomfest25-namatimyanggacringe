<div class="h-screen min-w-[120px] bg-white flex flex-col items-center py-20 border-r-2">
    <div class="flex flex-col gap-6">
        <a href="/dashboard">
            <div class="tooltip tooltip-right" data-tip="Dashboard">
                <button class="btn rounded-full w-14 h-14">
                    <x-lucide-layout-grid class="w-6 h-6" />
                </button>
            </div>
        </a>
        <a href="/">
            <div class="tooltip tooltip-right" data-tip="Dashboard">
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
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            <button class="btn rounded-full w-14 h-14 bg-white border-none ">
                @csrf
                <x-lucide-door-open class="w-6 h-6" />
            </button>
        </form>
        <div class="w-14 h-14 rounded-full overflow-hidden bg-gray-300">
            <img class="w-full" src="" class="">
        </div>
    </div>
</div>
