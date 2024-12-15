<nav class="p-4 py-6 fixed w-full z-50 text-black bg-white/10 backdrop-blur-md">
    <div class="container mx-auto flex justify-between items-center bg-transparent">
        <div class="text-2xl font-semibold flex gap-4 items-center bg-none">
            <img src="{{ asset('mindora-icon.png') }}" alt="Icon" class="w-10 h-10">
            <a href="/">
                Mindora
            </a>
        </div>
        @auth
            <div class="flex gap-4">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" >
                    @csrf
                    <button class="btn btn-outline">
                        Log Out
                    </button>
                </form>
            </div>
        @else
            <div class="flex gap-4">
                <a href="{{ route('login') }}">
                    <button class="btn btn-outline px-4 md:px-16">
                        Log In
                    </button>
                </a>
                <a href="{{ route('role.select') }}">
                    <button class="btn btn-primary px-4 md:px-16">
                        Sign Up
                    </button>
                </a>
            </div>
        @endauth
    </div>
</nav>