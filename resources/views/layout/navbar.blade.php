<nav class="bg-gray-800 p-4 py-6 fixed w-full">
    <div class="container mx-auto flex justify-between items-center">
        <div class="text-white text-lg font-bold">
            <a href="/">Mindora</a>
        </div>
        <ul class="flex space-x-4">
            <li><a href="/" class="text-gray-300 hover:text-white">Home</a></li>
            <li><a href="/courses" class="text-gray-300 hover:text-white">Courses</a></li>
            <li><a href="/services" class="text-gray-300 hover:text-white">Services</a></li>
            <li><a href="/posts" class="text-gray-300 hover:text-white">Blog</a></li>
        </ul>
        @auth
            <div class="text-white flex gap-4">
                <a href="{{ route('profile') }}"">
                    Welcome, {{ Auth::user()->name }}!
                </a>
                <x-buk-logout class="text-gray-500" />

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        @else
            <div>
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">Login</a>
                <a href="{{ route('role.select') }}" class="text-gray-300 hover:text-white">Register</a>
            </div>
        @endauth
    </div>
</nav>