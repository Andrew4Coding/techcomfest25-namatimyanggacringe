@php
    $studyGoals = [
        (object) [
            'title' => 'Mengerjakan Tugas 1',
            'description' => 'Mengerjakan tugas 1 yang diberikan oleh guru',
        ],
        (object) [
            'title' => 'Mengerjakan Tugas 2',
            'description' => 'Mengerjakan tugas 2 yang diberikan oleh guru',
        ],
        (object) [
            'title' => 'Mengerjakan Tugas 3',
            'description' => 'Mengerjakan tugas 3 yang diberikan oleh guru',
        ],
    ];
@endphp
@extends('layout.layout')
@section('content')
    @include('dashboard.sections.teacher')
@endsection
