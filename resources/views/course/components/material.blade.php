@extends('course.components.course_item')
@section('courseitem')
    <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5 justify-between">
        <div class="flex gap-4">
            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                <x-lucide-file class="w-4 h-4" />
            </div>
            <div>
                <p class="font-semibold">{{ $item['name'] }}</p>
                <p>{{ $item['description'] }}</p>
            </div>
        </div>
        <a href="{{ $item->courseItemable->file_url }}" target="_blank">
            <button class="btn btn-primary text-white">
                <x-lucide-download class="w-4 h-4" />
                <span class="hidden md:block">
                    Download
                </span>
            </button>
        </a>
    </div>
@endsection