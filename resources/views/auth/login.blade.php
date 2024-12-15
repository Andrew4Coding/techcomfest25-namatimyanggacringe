@extends('layout.layout')
@section('content')
    <div class="h-screen flex items-center justify-center">
        <form action="{{ route('login') }}" method="POST" class="w-full max-w-sm">
            @csrf
            <h1 class="text-2xl font-bold mb-6">Login</h1>

            <!-- Email -->
            <label for="email" class="block text-sm">Email</label>
            <input type="email" name="email" id="email" required class="input w-full border p-2 rounded mb-4">

            <!-- Password -->
            <label for="password" class="block text-sm">Password</label>
            <input type="password" name="password" id="password" required class="input w-full border p-2 rounded mb-4">

            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember" class="text-sm">Remember Me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Login</button>

            {{-- to Register --}}
            <div class="mt-4">
                <a href="{{ route('role.select') }}" class="text-blue-500">Register</a>
            </div>
        </form>
    </div>
@endsection
