@extends('layout.layout')
@section('content')
    <main class="w-full px-20 pt-10 bg-gradient-to-r from-blue-500 to-purple-600 min-h-screen flex items-center justify-center">
        <div class="text-center text-white">
            <h1 class="font-bold text-5xl mb-4">Welcome to Class AI</h1>
            <p class="text-xl mb-8">Your journey to mastering AI starts here.</p>
            <a href="{{ url('/get-started') }}" class="bg-white text-blue-500 px-6 py-3 rounded-full font-semibold hover:bg-gray-200 transition duration-300">Get Started</a>
        </div>
    </main>
@endsection