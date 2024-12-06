@extends('layout.layout')

@section('content')
    <main class="w-full px-20 pt-32">
        <h1>Chat with GPT</h1>
        <div id="chat-box" style="border: 1px solid #ddd; padding: 10px; height: 300px; overflow-y: auto;">
            @isset($response)
                <p><strong>GPT:</strong> {{ $response }}</p>
            @else
                <p>No conversation yet. Start by typing a message!</p>
            @endisset
        </div>
        <form id="chat-form" action="{{ route('chat.send') }}" method="POST">
            @csrf
            <input type="text" name="message" id="message" placeholder="Type your message here..." style="width: 80%;"
                required>
            <button type="submit">Send</button>
        </form>


        <form id="upload-form" action="{{route('quiz.generate')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-bladewind::filepicker name="file" accepted_file_types=".pdf" placeholder="Upload PDF" max_file_size="10"
                required />
            <button type="submit">Get Text</button>
        </form>
    </main>
@endsection
