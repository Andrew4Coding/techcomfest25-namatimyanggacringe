@extends('layout.layout')

@section('content')
    <div class="lg:h-screen grid grid-cols-1 lg:grid-cols-2 overflow-hidden">
        <section class="relative max-h-[300px] lg:max-h-full">
            <img src="{{ asset('grouped-circle.png') }}" alt="Login"
                class="hidden lg:block absolute min-w-[1000px] h-auto -bottom-80 -left-80">
            <img src="{{ asset('mindora-mascot.png') }}" alt="Login"
                class="absolute w-[200px] hidden lg:block lg:w-[500px] h-auto rotate-12 bottom-0 lg:bottom-20 left-1/2 transform -translate-x-1/2">
            <div class="pt-20 mx-auto lg:pt-20 lg:pl-32 text-center lg:text-left flex flex-col items-center lg:items-start">
                <img src="{{ asset('mindora-mascot.png') }}" alt="Login" class="w-[150px] lg:hidden">
                <h3 class="font-semibold text-2xl lg:text-4xl">
                    Welcome Back to
                </h3>
                <h1 class="text-4xl lg:text-6xl font-semibold gradient-blue text-transparent bg-clip-text">
                    Mindora AI
                </h1>
            </div>
        </section>
        <section class="flex items-start lg:items-center justify-center mb-20 lg:mb-0">
            <div class="w-96">
                <div class="mb-8 text-center lg:text-left">
                    <h1 class="text-3xl font-semibold mb-2">Apa peran anda??</h1>

                    <p class="text-[#17194C]/75 text-sm">Pilih menu sesuai dengan peran Anda</p>
                </div>
                <a href="/register/teacher">
                    <div
                        class="w-full bg-white rounded-xl shadow-smooth p-8 mb-8 max-h-52 flex flex-col items-center justify-center hover:shadow-md hover:scale-105 hover:bg-gray-100 duration-300">
                        <img src="{{ asset('mascot-teacher.png') }}" alt="Teacher" class="max-h-32 w-auto">
                        <h3 class="font-semibold">
                            Guru
                        </h3>
                    </div>
                </a>
                <a href="/register/student">
                    <div
                        class="w-full bg-white rounded-xl shadow-smooth p-8 mb-8 max-h-52 flex flex-col items-center justify-center hover:shadow-md hover:scale-105 hover:bg-gray-100 duration-300">
                        <img src="{{ asset('mascot-love.png') }}" alt="Teacher" class="max-h-32 w-auto">
                        <h3 class="font-semibold">
                            Siswa
                        </h3>
                    </div>
                </a>
                <div class="flex items-center justify-between w-full">
                    <button type="submit" class="btn btn-primary w-full">
                        Continue
                    </button>
                </div>
            </div>
            {{-- Direct to Login --}}
            <div class="mt-4 text-[#17194C]/75 text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="underline hover:text-blue-500 duration-300">Sign In</a>
            </div>
        </section>
    @endsection
