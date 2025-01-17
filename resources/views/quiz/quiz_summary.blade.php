@extends('layout.layout')

@section('content')
    <div class="w-full h-full flex justify-center items-center">
        <div class="p-4 rounded-xl shadow-xl bg-base-200 flex flex-col gap justify-between items-center">
            <h1>{{ $quiz->courseItem->name }}</h1>
            <div class="p-4 rounded-xl bg-info flex flex-col text-sm text-gray-700 gap-2">
            <span class="flex gap-2 items-center">
                <x-lucide-timer class="w-4 h-4"/>
                Mulai: {{ $quiz->start }}
            </span>
                <span class="flex gap-2 items-center">
                <x-lucide-timer class="w-4 h-4"/>
                Berakhir: {{ $quiz->finish }}
            </span>
                <span class="flex gap-2 items-center">
                <x-lucide-hourglass class="w-4 h-4"/>
                Durasi: {{ $quiz->duration }} menit
            </span>
            </div>
            <div class="flex items-center gap-2">
            <span class="px-4 py-2 text-sm font-bold text-white rounded-xl bg-primary my-2">
                {{ $quiz->quiz_submissions_count }} / {{ $siswaCount }}
            </span>
                <span class="text-sm ">
                Siswa Telah Selesai
            </span>
            </div>
            <a href="{{ route('quiz.submission.list', ['quizId' => $quiz->id]) }}" class="btn btn-primary w-full mt-4">
                Periksa
            </a>
        </div>
    </div>

@endsection

