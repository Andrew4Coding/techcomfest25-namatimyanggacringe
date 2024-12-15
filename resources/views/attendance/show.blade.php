@php
    $role = Auth::user()->userable_type;
@endphp
@extends('layout.layout')
@section('content')
    <div class="breadcrumbs text-sm mb-6">
        <ul class="flex space-x-2 text-gray-600">
            <li><a href="/courses" class="hover:text-blue-500">Courses</a></li>
            <li><a
                    href="{{ route('course.show', ['id' => $attendance->courseItem->courseSection->course->id]) }}">{{ $attendance->courseItem->courseSection->course->name }}</a>
            </li>
            <li><a
                    href="{{ route('course.show', ['id' => $attendance->courseItem->courseSection->course->id]) }}">{{ $attendance->courseItem->courseSection->name }}</a>
            </li>
            <li><a href="#" class="hover:text-blue-500">Attendances</a></li>
            <li><a class="font-semibold">{{ $attendance->courseItem->name }}</a></li>
        </ul>
    </div>
    <div class="container mx-auto">
        <div class="flex items-center gap-4 mt-5 justify-between">
            <div class="flex gap-4">
                <div>
                    <h1 class="font-semibold text-2xl">{{ $attendance->courseItem->name }}</h1>
                    <p>{{ $attendance->courseItem->description }}</p>
                </div>
            </div>
        </div>

        @if ($role == 'App\Models\Teacher')
            @include('attendance.sections.teacher-show', [
                'attendanceSubmissions' => $attendanceSubmissions,
            ])
        @else
            @include('attendance.sections.student-show', [
                'attendance' => $attendance,
                'alreadySubmitted' => $alreadySubmitted,
            ])
        @endif
    </div>
@endsection
