@extends('layout.layout')
@section('content')
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 overflow-hidden pb-20 lg:pb-0">
        <section class="relative max-h-[300px] lg:max-h-full">
            <img src="{{ asset('grouped-circle.png') }}" alt="Login" class="hidden lg:block absolute min-w-[1000px] h-auto -bottom-80 -left-80">
            <img src="{{ asset('mindora-mascot.png') }}" alt="Login" class="absolute w-[200px] hidden lg:block lg:w-[500px] h-auto rotate-12 bottom-0 lg:bottom-20 left-1/2 transform -translate-x-1/2">
            <div class="pt-20 mx-auto lg:pt-20 lg:pl-32 text-center lg:text-left flex flex-col items-center lg:items-start">
                <img src="{{ asset('mindora-mascot.png') }}" alt="Login" class="w-[150px] lg:hidden">
                <h3 class="font-semibold text-2xl lg:text-4xl">
                    Welcome Back to
                </h3>
                <h1
                    class="text-4xl lg:text-6xl font-semibold gradient-blue text-transparent bg-clip-text"
                >
                    Mindora AI
                </h1>
            </div>
        </section>
        <section class="flex items-start lg:items-center justify-center">
            <form action="{{ route('login') }}" method="POST" class="w-full max-w-sm">
                @csrf
                <div class="mb-8 text-center lg:text-left">
                    <h1 class="text-3xl font-semibold mb-2">Sign In</h1>
        
                    <p 
                        class="text-[#17194C]/75 text-sm"
                    >Selamat Datang! Mohon masuk ke akun Anda.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-smooth">
                    <!-- Email -->
                    <label for="email" class="block text-sm">Email</label>
                    <input type="email" name="email" id="email" required class="input w-full border p-2 rounded mb-4"
                        placeholder="user@gmail.com"
                    >
        
                    <!-- Password -->
                    <label for="password" class="block text-sm">Password</label>
                    <input type="password" name="password" id="password" required class="input w-full border p-2 rounded mb-4"
                        placeholder="Password"
                    >
        
                    <!-- Remember Me -->
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="remember" id="remember" class="mr-2">
                        <label for="remember" class="text-sm">Ingat Saya</label>
                    </div>
        
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full">Log In</button>
        
                </div>
                {{-- to Register --}}
                <div class="mt-4 text-[#17194C]/75 text-center text-sm">
                    Don’t have an account?
                    <a href="{{ route('role.select') }}" class="underline hover:text-blue-500 duration-300">Sign Up</a>
                </div>
            </form>
        </section>
    </div>
@endsection
