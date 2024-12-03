@extends('layout.layout')
@section('content')
    <main class="px-20 py-40">
        <div class="">
            <h1 class="text-3xl font-extrabold mb-4">{{ $course->name }}</h1>
            <p class="text-lg mb-6">{{ $course->description }}</p>
        </div>
        {{-- Show all sections --}}
        <div class="mt-8 flex flex-col gap-4">
            @foreach ($course->sections as $section)
                @include('course.components.section', ['section' => $section])
            @endforeach
        </div>
        <x-bladewind::button 
             onclick="showModal('tnc-agreement-titled')"
            class="w-full">
            + Add Section
        </x-bladewind::button>
        <x-bladewind::modal 
            ok_button_label="+ Create"
            title="Create new Section"
            name="tnc-agreement-titled"> 
            <form  method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Section Name</label>
                    <x-bladewind::input type="text" name="name" id="name" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <x-bladewind::textarea name="description" id="description" rows="3" required />
                </div>
            </form>
        </x-bladewind::modal>
    </main>
@endsection
