@extends('layout.layout')
@section('content')
    <main>
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="/dashboard">Dashboard</a></li>
                <li>
                    <a href="{{ route('dashboard.progress') }}">
                        Student Progress
                    </a>
                </li>
            </ul>
        </div>
        <div class="space-y-2 mb-4 md:mb-10">
            <h1 class="text-3xl font-semibold">Student Progress</h1>
            <p class="font-medium gradient-blue text-transparent bg-clip-text">
                Pastikan siswa Anda selalu berada di jalur yang benar.
            </p>
        </div>
        <section class="flex flex-col w-full gap-4">
            <form class="flex gap-4" method="get">
                <input value="{{ request('search') }}" type="text" name="search" id=""
                    class="input input-white w-full md:max-w-[300px]" placeholder="Cari Kelas" />


                <select name="course" id="" class="select select-white  max-w-[300px]"
                    onclick="this.form.submit()">
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary w-full max-w-[100px]">
                    <x-lucide-search class="w-4 h-4" />
                    Cari
                </button>
            </form>

            <table class="table table-zebra w-full rounded-sm overflow-hidden">
                <thead class="text-left bg-blue-500 text-white">
                    <tr>
                        <th>Nomor</th>
                        <th>Nama</th>
                        <th>Average Score</th>

                        @if (!empty($topStudents))
                            @foreach ($topStudents[0]->submissionGrades as $submission)
                                <th>{{ $submission->name }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (empty($topStudents))
                        <tr>
                            <td colspan="4" class="text-center">No Data</td>
                        </tr>
                    @endif
                    @foreach ($topStudents as $key => $student)
                        <tr>
                            <td>{{ $student->rank }}</td>
                            <td>{{ $student->user->name }}</td>
                            <td>{{ $student->averageScore }}</td>
                            @foreach ($student->submissionGrades as $submission)
                                <td class="">{{ $submission->grade }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        @include('components.pagination', ['route' => 'dashboard.progress', 'page' => $page, 'availablePages' => $availablePages, 'search' => request('search'), 'course' => request('course')])
    </main>
@endsection
