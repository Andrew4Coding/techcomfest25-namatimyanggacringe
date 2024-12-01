@extends('layout.layout')
@section('content')
<div class="min-h-screen flex items-center justify-center">
        <form action="{{ route('register') }}" method="POST" class="w-full max-w-sm">
            @csrf
            <h1 class="text-2xl font-bold mb-6">Register</h1>

            <!-- Name -->
            <label for="name" class="block text-sm">Name</label>
            <input type="text" name="name" id="name" required class="w-full border p-2 rounded mb-4">

            <!-- Email -->
            <label for="email" class="block text-sm">Email</label>
            <input type="email" name="email" id="email" required class="w-full border p-2 rounded mb-4">

            <!-- Password -->
            <label for="password" class="block text-sm">Password</label>
            <input type="password" name="password" id="password" required class="w-full border p-2 rounded mb-4">

            <!-- Confirm Password -->
            <label for="password_confirmation" class="block text-sm">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border p-2 rounded mb-4">

            {{-- Direct to Login --}}
            <div class="mt-4">
                <a href="{{ route('login') }}" class="text-blue-500">Login</a>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Register</button>

        </form>
    </div>
@endsection