@extends('layout.layout')
@section('content')
    @if (Auth::user()->userable_type === 'App\Models\Student')
        @include('dashboard.sections.student')
    @else
        @include('dashboard.sections.teacher')
    @endif
@endsection
