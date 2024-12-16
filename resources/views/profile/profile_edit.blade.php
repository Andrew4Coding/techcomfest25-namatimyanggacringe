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
        <img src="{{ asset('lanyard-left.png') }}" alt="Mascot" class="absolute w-80 hidden md:block -top-[350px] -translate-x-16 z-0">

        <div class="text-center flex flex-col items-center bg-white rounded-3xl shadow-smooth p-10 relative">
            <img src="{{ asset('lanyard-right.png') }}" alt="Mascot" class="absolute w-80 hidden md:block -top-[360px] translate-x-20 z-10">

            <img src="{{ asset('mindora-icon.png') }}" alt="Mascot" class="absolute w-12 h-auto left-5 top-10">
            <img src="{{ asset('mindora-mascot.png') }}" alt="Mascot" class="absolute w-20 h-auto right-10 top-32 rotate-12">
            
            <img class="w-48 h-48 rounded-xl object-cover mb-4"
                src="
                    @if (auth()->user()->profile_picture) {{ env('AWS_URL') . auth()->user()->profile_picture }}                        
                    @else
                        https://ui-avatars.com/api/?name={{ Auth::user()->name }}&color=7F9CF5&background=EBF4FF @endif
                "
                alt="Profile Picture">
            <h1 class="text-sm text-gray-800 mb-2">
                {{ Auth::user()->userable_type == 'App\Models\Teacher' ? 'Teacher' : 'Student' }}</h1>


            <form action="{{route('profile.update.update')}}" method="POST" class="w-full">
                @csrf
                <div class="mb-4 flex gap-2 items-center">
                    <label for="name" class="block text-sm font-medium text-gray-700 w-[100px]">Name</label>
                    <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="input">
                </div>
                <div class="mb-4 flex gap-2 items-center">
                    <label for="email" class="block text-sm font-medium text-gray-700 w-[100px]">Email</label>
                    <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="input">
                </div>
                <div class="mb-4 flex gap-2 items-center">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 w-[100px]">Telpon</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ Auth::user()->phone_number }}" class="input">
                </div>
                <div class="flex gap-2 items-center w-full">
                    <a href="/profile" class="w-1/2">
                        <button class="btn btn-outline w-full">
                            Batalkan
                        </button>
                    </a>
                    <button type="submit" class="btn btn-primary w-1/2">
                        <x-lucide-pencil class="w-4 h-4 mr-1" />
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
