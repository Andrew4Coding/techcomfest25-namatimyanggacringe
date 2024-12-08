@extends('layout.layout')
@section('content')
<div class="w-full flex flex-col items-center justify-center">
    <h1 class="text-5xl font-bold">
        Welcome to
        <span class="bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 bg-clip-text text-transparent">
             AI Forum!
        </span>
    </h1>
    <p>
        Tanyakan apapun disini :D
    </p>
    <form action="" class="w-full flex gap-2">
        <input type="text" name="" id="" placeholder="Tanyakan pertanyaamu disini ... " class="input w-full">
        <button class="btn btn-primary">
            Submit
        </button>
    </form>
</div>
@endsection