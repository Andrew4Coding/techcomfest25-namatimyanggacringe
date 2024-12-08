@extends('layout.layout')
@section('content')
    <div>
        <h1 class="text-3xl font-bold">Hello, {{Auth::user()->name}}</h1>
        <p class="font-medium bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 text-transparent bg-clip-text">
            Siap untuk melanjutkan perjalanan belajarmu?
        </p>
    </div>
@endsection