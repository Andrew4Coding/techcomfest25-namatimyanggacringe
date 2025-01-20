@php use Carbon\Carbon; @endphp
@extends('layout.layout')

@section('content')
    <div class="w-full h-full">
        <button class="relative -top-10 btn" onclick="window.history.back()">
            <x-lucide-arrow-left class="w-4 h-4"/>
        </button>
        <div class="w-full h-full flex justify-center items-center">
            <div class="px-12 py-6 rounded-xl shadow-xl flex flex-col gap justify-between items-center">
                <h1>{{ $quiz->courseItem->name }}</h1>
                <div class="p-4 rounded-xl bg-blue-500 flex flex-col text-sm text-white gap-2">
                <span class="flex gap-2 items-center">
                    <x-lucide-timer class="w-4 h-4"/>
                    Mulai: {{ Carbon::parse($quiz->start)->format('d M Y, H:i') }}
                </span>
                    <span class="flex gap-2 items-center">
                    <x-lucide-timer class="w-4 h-4"/>
                    Berakhir: {{ Carbon::parse($quiz->finish)->format('d M Y, H:i') }}
                </span>
                    <span class="flex gap-2 items-center">
                    <x-lucide-hourglass class="w-4 h-4"/>
                    Durasi: {{ $quiz->duration }} menit
                </span>
                </div>
                <div class="flex items-center gap-2 my-5">
                    <div class="px-4 py-2 text-sm font-bold text-white rounded-xl bg-primary my-2">
                        {{ $quiz->quiz_submissions_count }} / {{ $siswaCount }}
                    </div>
                    <span class="text-sm ">
                    Siswa Telah Selesai
                </span>
                </div>
                <a href="{{ route('quiz.submission.list', ['quizId' => $quiz->id]) }}" class="w-full">
                    <button class="btn btn-primary w-full">
                        Periksa
                    </button>
                </a>
            </div>
        </div>
    </div>
@endsection
