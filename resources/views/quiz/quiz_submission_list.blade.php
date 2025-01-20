@extends('layout.layout')

@section('content')
    <button class="relative -top-10 btn" onclick="window.history.back()">
        <x-lucide-arrow-left class="w-4 h-4" />
    </button>
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
                @if ($submissions->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Belum ada submisi</td>
                    </tr>
                @endif
                @foreach ($submissions as $submission)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $submission->student->user->name }}</td>
                        <td>{{ $submission->student->class }}</td>
                        <td>{{ $submission->score }}</td>
                        <td>{{ $submission->comment }}</td>

                        <td class="flex gap-2 justify-center items-center">
                            <div class="tooltip" data-tip="Periksa Hasil">
                                <a href="{{ route('quiz.solution', ['quizId' => $submission->quiz->id]) }}?id={{ $submission->student->id }}"
                                    class="btn btn-primary">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                            </div>
                            <div class="tooltip" data-tip="Hapus Submisi">
                                <button class="btn btn-error" onclick="delete_modal_{{ $loop->index }}.showModal()">
                                    <x-lucide-trash class="w-4 h-4" />
                                </button>
                            </div>
                            <dialog id="delete_modal_{{ $loop->index }}" class="modal modal-bottom sm:modal-middle">
                                <div class="modal-box">
                                    <h3 class="text-lg font-bold">Warning!</h3>
                                    <p class="py-4">Anda yakin untuk menghapus submisi?</p>
                                    <div class="modal-action">
                                        <form method="dialog" class="flex gap-2">
                                            <!-- if there is a button in form, it will close the modal -->
                                            <button class="btn btn-primary">Tidak</button>
                                            <a href="{{ route('quiz.submission.delete', ['submissionId' => $submission->id]) }}"
                                                class="btn btn-error">Ya</a>
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
