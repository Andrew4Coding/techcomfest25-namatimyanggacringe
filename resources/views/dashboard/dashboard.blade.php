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
    <div>
        <h1 class="text-3xl font-bold">Hello, {{ Auth::user()->name }}</h1>
        <p class="font-medium bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 text-transparent bg-clip-text">
            Siap untuk melanjutkan perjalanan belajarmu?
        </p>
        <section class="grid grid-cols-2">
            <div>
                <h2>Tugas Berikutnya</h2>
            </div>
        </section>
        <section class="bg-[#FCFCFC] rounded-2xl p-10 space-y-5">
            <h3>Study Goals</h3>
            <div class="grid grid-cols-3 gap-5">
                @foreach ($studyGoals as $goal)
                    <div class="bg-yellow-200 p-10 rounded-lg shadow-md">
                        <p>{{ $goal->description }}</p>
                    </div>
                @endforeach
                <div class="bg-white border-2 p-10 rounded-lg shadow-md flex items-center justify-center">
                    <div class="w-20 h-20 flex items-center justify-center bg-gray-50 rounded-full">
                        <x-lucide-plus class="w-6 h-6" />
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-[#FCFCFC] shadow-md rounded-xl p-10 space-y-5">
            <h3>Pesan dari Wali Kelas</h3>
            <div class="bg-[#F6F9FA] shadow-md p-5 rounded-xl text-[#3A4449] min-h-[150px]">
                Good Job! Pertahankan terus semangatmu yaa.
            </div>
        </section>
    </div>
@endsection
