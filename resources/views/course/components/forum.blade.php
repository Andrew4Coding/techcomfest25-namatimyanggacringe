@extends('course.components.course_item')
@section('courseitem')
    <a 
        class="w-full"
        href="{{ route('forum.index', ['forumId' => $item->courseItemable->id]) }}">
        <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5">
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center">
                    <x-lucide-message-square class="w-4 h-4" />
                </div>
                <div>
                    <p class="font-semibold">{{ $item['name'] }}</p>
                    <p>{{ $item['description'] }}</p>
                </div>
            </div>
        </div>
    </a>
@endsection