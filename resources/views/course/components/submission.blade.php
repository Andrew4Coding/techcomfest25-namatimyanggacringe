@extends('course.components.course_item')
@section('courseitem')
    <a href="{{ route('submission.show', ['submissionId' => $item->courseItemable->id]) }}"
            class="w-full"
        >
        <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5 justify-between">
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-red-200 rounded-full flex items-center justify-center">
                    <x-lucide-file class="w-4 h-4" />
                </div>
                <div>
                    <p class="font-semibold">{{ $item['name'] }}</p>
                    <p>{{ $item['description'] }}</p>
                </div>
            </div>
        </div>
    </a>
@endsection