@extends('layout.layout')

@section('content')
    <main class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ Auth::user()->name }}</h1>
                <p class="text-sm text-gray-600">Email: {{ Auth::user()->email }}</p>
                <p class="text-sm text-gray-600">Joined: {{ Auth::user()->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </main>
@endsection
