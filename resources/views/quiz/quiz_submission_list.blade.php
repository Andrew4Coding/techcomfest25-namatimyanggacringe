@extends('layout.layout')

@section('content')
    <button class="relative -top-10 btn">Back</button>
    <div class="max-h-full overflow-x-auto">
        <table class="table table-zebra table-pin-rows table-auto w-full">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th class="text-center">Nilai</th>
                <th class="text-center">Komentar</th>
                <th class="text-center">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($submissions as $submission)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $submission->student->user->name }}</td>
                    <td>{{ $submission->student->class }}</td>
                    <td>{{ $submission->score }}</td>
                    <td>{{ $submission->comment }}</td>

                    <td class="flex gap-2 justify-center items-center">
                        <a href="{{ route('quiz.solution', ['quizId' => $submission->quiz->id]) }}?id={{ $submission->student->id }}"
                           class="btn btn-sm btn-info">Edit</a>
                        <button class="btn btn-sm btn-error" onclick="delete_modal_{{ $loop->index }}.showModal()">
                            Delete
                        </button>
                        <dialog id="delete_modal_{{ $loop->index }}" class="modal modal-bottom sm:modal-middle">
                            <div class="modal-box">
                                <h3 class="text-lg font-bold">Warning!</h3>
                                <p class="py-4">Anda yakin untuk menghapus submisi?</p>
                                <div class="modal-action">
                                    <form method="dialog" class="flex gap-2">
                                        <!-- if there is a button in form, it will close the modal -->
                                        <button class="btn btn-primary">Tidak</button>
                                        <a href="{{ route('quiz.submission.delete', ['submissionId' => $submission->id]) }}"
                                           class="btn btn-error btn-outline">Ya</a>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
