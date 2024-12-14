@php
    $role = auth()->user()->userable_type;
    $isEdit = $role == 'App\Models\Teacher';
@endphp

@extends('layout.layout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs text-sm mb-6">
            <ul class="flex space-x-2 text-gray-600">
                <li><a href="/courses" class="hover:text-blue-500">Courses</a></li>
                <li><a href="{{route('course.show', ['id'=>$submission->courseItem->courseSection->course->id])}}">{{ $submission->courseItem->courseSection->course->name }}</a></li>
                <li><a href="{{route('course.show', ['id'=>$submission->courseItem->courseSection->course->id])}}">{{ $submission->courseItem->courseSection->name }}</a></li>
                <li><a href="#" class="hover:text-blue-500">Submissions</a></li>
                <li><a class="font-semibold">{{ $submission->courseItem->name }}</a></li>
            </ul>
        </div>

        <!-- Submission Details -->
        <div class="mb-10">
            <div class="flex gap-4 items-center mb-4"> 
                <h1 class="text-3xl font-extrabold text-gray-800">{{ $submission->courseItem->name }}</h1>
                @if ($isEdit)
                    <x-lucide-pencil class="w-4 h-4 text-gray-600 cursor-pointer" onclick="document.getElementById('edit_submission_modal').showModal();"/>
                @endif
            </div>
            <p class="text-sm text-gray-600 mb-6">{{ $submission->courseItem->description }}</p>
            <div class="flex flex-col gap-4 max-w-[350px] text-sm">
                <div class="grid grid-cols-2">
                    <b class="text-gray-700 mr-2 font-medium">Opened At:</b>
                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($submission->opened_at)->format('d M Y, h:i A') }}</p>
                </div>
                <div class="grid grid-cols-2">
                    <b class="text-gray-700 mr-2 font-medium">Due At:</b>
                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($submission->due_date)->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>

        
        @if ($role == 'App\Models\Teacher')
            @include('submission.sections.teacher_submission')
        @else
            @include('submission.sections.student_submission')
        @endif
@endsection