@extends('layout.layout')
@section('content')
    @if (Auth::user()->userable_type === 'App\Models\Student')
        @include('dashboard.role.student.index')
    @else
        @include('dashboard.role.teacher.index')
    @endif
@endsection
