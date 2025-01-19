@extends('layout.layout')
@section('content')
    <main class="w-full h-screen flex items-center justify-center flex-col gap-4">
        <img src="{{ asset('mascot-nyari.png') }}" alt="Login" class="w-[150px]">
        <h3 class="font-semibold text-xl text-center">
            Halaman tidak ditemukan!
        </h3>
        <a href="/">
            <button class="btn btn-primary">
                <x-lucide-chevron-left class="w-4 h-4 mr-2" />
                Kembali
            </button>
        </a>
    </main>
@endsection