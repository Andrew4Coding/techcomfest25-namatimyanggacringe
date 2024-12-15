@php
    $PATH = env('AWS_URL');
@endphp
@extends('layout.layout')

@section('content')
    <main class="flex">
        <div class="text-center flex flex-col items-center">
            <img class="w-32 h-32 rounded-full object-cover mb-4"
                src="
                    @if (auth()->user()->profile_picture) {{ env('AWS_URL') . auth()->user()->profile_picture }}                        
                    @else
                        https://ui-avatars.com/api/?name={{ Auth::user()->name }}&color=7F9CF5&background=EBF4FF @endif
                "
                alt="Profile Picture">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ Auth::user()->name }}</h1>
            <p class="text-sm text-gray-600 mb-1"><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p class="text-sm text-gray-600 mb-1"><strong>Phone:</strong> {{ Auth::user()->phone_number }}</p>
            <p class="text-sm text-gray-600 mb-1"><strong>Joined:</strong> {{ Auth::user()->created_at->format('M d, Y') }}
            </p>
        </div>
    </main>
@endsection
