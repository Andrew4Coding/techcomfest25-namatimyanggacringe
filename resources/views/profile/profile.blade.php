@php
    $PATH = env('AWS_URL');
@endphp
@extends('layout.layout')

@section('content')
    <section class="flex flex-col md:flex-row justify-between items-start space-y-4 md:space-y-0 md:space-x-4">
        <div class="space-y-2 mb-4 md:mb-0">
            <h1 class="text-3xl font-semibold">Profile</h1>
            <p class="font-medium gradient-blue text-transparent bg-clip-text">
                Temukan datamu di page ini
            </p>
        </div>
    </section>
    <main class="flex items-center justify-center w-full relative">
        <img src="{{ asset('lanyard-left.png') }}" alt="Mascot" class="absolute w-80 hidden md:block -top-[370px] -translate-x-16 z-0">

        <div class="text-center flex flex-col items-center bg-white rounded-3xl shadow-smooth p-10 relative">
            <img src="{{ asset('lanyard-right.png') }}" alt="Mascot" class="absolute w-80 hidden md:block -top-[320px] translate-x-20 z-10">

            <img src="{{ asset('mindora-icon.png') }}" alt="Mascot" class="absolute w-12 h-auto left-5 top-10">
            <img src="{{ asset('mindora-mascot.png') }}" alt="Mascot" class="absolute w-32 h-auto right-5 top-40 rotate-12">
            
            <img class="w-48 h-4w-48 rounded-xl object-cover mb-4"
                src="
                    @if (auth()->user()->profile_picture) {{ env('AWS_URL') . auth()->user()->profile_picture }}                        
                    @else
                        https://ui-avatars.com/api/?name={{ Auth::user()->name }}&color=7F9CF5&background=EBF4FF @endif
                "
                alt="Profile Picture">
            <h1 class="text-sm text-gray-800 mb-2">
                {{ Auth::user()->userable_type == 'App\Models\Teacher' ? 'Teacher' : 'Student' }}</h1>
            <h1 class="text-xl font-semibold text-gray-800 mb-2">{{ Auth::user()->name }}</h1>
            <p class="text-sm text-gray-600 mb-1 flex gap-2">
                <x-lucide-mail class="w-4 h-4 mr-1 flex gap-2" />
                {{ Auth::user()->email }}
            </p>
            <p class="text-sm text-gray-600 mb-1 flex gap-2">
                <x-lucide-phone class="w-4 h-4 mr-1" />
                {{ Auth::user()->phone_number }}
            </p>
            <a href="/profile/edit" class="w-full">
                <button
                    class="btn btn-primary w-full mt-4"
                >
                    <x-lucide-pencil class="w-4 h-4 mr-1" />
                    Edit Profile
                </button>
            </a>
        </div>
    </main>
@endsection
