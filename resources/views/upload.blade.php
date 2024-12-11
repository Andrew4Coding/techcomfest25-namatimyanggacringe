@extends('layout.layout')
@section('content')    
    <h1>Upload a PDF</h1>
    <form action="{{ route('pdf.upload.process') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="pdf" accept="application/pdf" required>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <p>
        @if (isset($result))
            {{$result}}
        @endif
    </p>
@endsection