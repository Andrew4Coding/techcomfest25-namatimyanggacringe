@php
    $role = auth()->user()->userable_type;
@endphp

@extends('layout.layout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs text-sm mb-6">
            <ul class="flex space-x-2 text-gray-600">
                <li><a href="/courses" class="hover:text-blue-500">Courses</a></li>
                <li><a href="/">{{ $submission->courseItem->courseSection->course->name }}</a></li>
                <li><a class="">{{ $submission->courseItem->courseSection->name }}</a></li>
                <li><a href="#" class="hover:text-blue-500">Submissions</a></li>
                <li><a class="font-semibold">{{ $submission->courseItem->name }}</a></li>
            </ul>
        </div>

        <!-- Submission Details -->
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 mb-4">{{ $submission->courseItem->name }}</h1>
            <p class="text-sm text-gray-600 mb-6">{{ $submission->courseItem->description }}</p>
            <p class="text-sm text-gray-800 mb-6">{{ $submission->content }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <b class="text-gray-700 mr-2">Opened At:</b>
                    <p class="text-gray-600">{{ $submission->opened_at }}</p>
                </div>
                <div class="flex items-center">
                    <b class="text-gray-700 mr-2">Due At:</b>
                    <p class="text-gray-600">{{ $submission->due_date }}</p>
                </div>
            </div>
            <button
            class="btn btn-primary mt-4"
                onclick="document.getElementById('edit_submission_modal').showModal();">
                Edit Submission Detail
            </button>
        </div>

        
        @if ($role == 'App\Models\Teacher')
            @include('submission.sections.teacher_submission')
        @else
            @include('submission.sections.student_submission')
        @endif
@endsection