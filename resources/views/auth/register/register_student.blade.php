@extends('layout.layout')
@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <form action="/register/student?role=student" method="POST" class="w-full max-w-sm" enctype="multipart/form-data">
            @csrf
            <h1 class="text-2xl font-bold mb-6">Register as Student</h1>

            <!-- Image Preview -->
            <div class="mb-4">
                <img id="image_preview" src="#" alt="Profile Picture Preview" class="hidden w-32 h-32 rounded-full object-cover">
            </div>

            <!-- Name -->
            <label for="name" class="block text-sm">Name</label>
            <input type="text" name="name" id="name" required class="w-full border p-2 rounded mb-4">

            <!-- Email -->
            <label for="email" class="block text-sm">Email</label>
            <input type="email" name="email" id="email" required class="w-full border p-2 rounded mb-4">

            <!-- Phone -->
            <label for="phone_number" class="block text-sm">Phone</label>
            <input type="text" name="phone_number" id="phone_number" required class="w-full border p-2 rounded mb-4">

            <!-- Password -->
            <label for="password" class="block text-sm">Password</label>
            <input type="password" name="password" id="password" required class="w-full border p-2 rounded mb-4">

            <!-- Confirm Password -->
            <label for="password_confirmation" class="block text-sm">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                class="w-full border p-2 rounded mb-4">

            <!-- Profile Picture -->
            <label for="profile_picture" class="block text-sm">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="w-full border p-2 rounded mb-4" onchange="previewImage(event)">

            {{-- Direct to Login --}}
            <div class="mt-4">
                <a href="{{ route('login') }}" class="text-blue-500">Login</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Register</button>

        </form>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('image_preview');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
