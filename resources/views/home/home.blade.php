@php
    $courses = [
        [
            'title' => 'Introduction to AI',
            'description' => 'Learn the basics of Artificial Intelligence and its applications.',
            'image' => 'https://example.com/images/ai-course.jpg',
        ],
        [
            'title' => 'Advanced Machine Learning',
            'description' => 'Dive deep into machine learning algorithms and techniques.',
            'image' => 'https://example.com/images/ml-course.jpg',
        ],
        [
            'title' => 'Data Science with Python',
            'description' => 'Master data analysis and visualization using Python.',
            'image' => 'https://example.com/images/ds-course.jpg',
        ],
    ];
@endphp
@extends('layout.layout')
@section('content')
    @include('home.sections.header')
    @include('home.sections.carousel')
    @include('home.sections.features')
@endsection
