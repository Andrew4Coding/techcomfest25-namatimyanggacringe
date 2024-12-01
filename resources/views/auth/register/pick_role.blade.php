@extends('layout.layout')

@section('content')
    <div class="mx-auto px-4 py-20 h-[100vh] flex items-center justify-center">
        <div class="min-w-[400px]">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-center mb-4">Choose Your Role</h1>
                <form action="{{ route('role.select') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Select Role</label>
                        <select name="role" id="role" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Select a role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-between w-full">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                            Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
