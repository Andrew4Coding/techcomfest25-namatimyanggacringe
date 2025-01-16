@extends('layout.layout')
@section('content')
    <div class="lg:h-screen grid grid-cols-1 lg:grid-cols-2 overflow-hidden">
        <section class="relative max-h-[300px] lg:max-h-full mb-10">
            <img src="{{ asset('grouped-circle.png') }}" alt="Register" class="hidden lg:block absolute min-w-[1000px] h-auto -bottom-80 -left-80">
            <img src="{{ asset('mindora-mascot.png') }}" alt="Register" class="absolute w-[200px] hidden lg:block lg:w-[500px] h-auto rotate-12 bottom-0 lg:bottom-20 left-1/2 transform -translate-x-1/2">
            <div class="pt-20 mx-auto lg:pt-20 lg:pl-32 text-center lg:text-left flex flex-col items-center lg:items-start">
                <img src="{{ asset('mindora-mascot.png') }}" alt="Register" class="w-[150px] lg:hidden">
                <h3 class="font-semibold text-2xl lg:text-4xl">
                    Welcome Student to
                </h3>
                <h1
                    class="text-4xl lg:text-6xl font-semibold gradient-blue text-transparent bg-clip-text"
                >
                    Mindora AI
                </h1>
            </div>
        </section>
        <section class="flex items-start lg:items-center justify-center mb-20 lg:mb-0">
            <form action="/register/student?role=student" method="POST" class="w-full max-w-sm" enctype="multipart/form-data">
                @csrf
                <div class="bg-white p-8 rounded-xl shadow-smooth flex flex-col gap-2">
                    <div class="space-y-1">
                        <!-- Name -->
                        <label for="name" class="block text-sm">Name</label>
                        <input type="text" name="name" id="name" required class="input" placeholder="John Doe">
                    </div>

                    <div class="space-y-1">
                        <!-- Email -->
                        <label for="email" class="block text-sm">Email</label>
                        <input type="email" name="email" id="email" required class="input" placeholder="mindora@gmail.com">
                    </div>

                    <div class="space-y-1">
                        <!-- Phone -->
                        <label for="phone_number" class="block text-sm">Phone</label>
                        <input type="text" name="phone_number" id="phone_number" required class="input" placeholder="081234567890">
                    </div>

                    <div class="space-y-1">
                        <!-- Class -->
                        <label for="class" class="block text-sm">Class</label>
                        <input type="text" name="class" id="class" required class="input" placeholder="XII MIPA 1">
                    </div>

                    <div class="space-y-1">
                        <!-- Password -->
                        <label for="password" class="block text-sm">Password</label>
                        <input type="password" name="password" id="password" required class="input" placeholder="Password">
                    </div>

                    <div class="space-y-1">
                        <!-- Confirm Password -->
                        <label for="password_confirmation" class="block text-sm">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="input" placeholder="Confirm Password">
                    </div>

                    <div class="space-y-1">
                        <!-- Profile Picture -->
                        <label for="profile_picture" class="block text-sm">Profile Picture</label>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="file-input file-input-primary w-full" onchange="previewImage(event)">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full">Sign Up</button>
                </div>
                {{-- Direct to Login --}}
                <div class="mt-4 text-[#17194C]/75 text-center text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="underline hover:text-blue-500 duration-300">Sign In</a>
                </div>
            </form>
        </section>
    </div>
@endsection
