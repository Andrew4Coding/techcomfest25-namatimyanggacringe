<div class="mb-10">
    <h1 class="text-3xl font-bold">Halo, Teacher!</h1>
    <p class="font-medium gradient-blue text-transparent bg-clip-text">
        Siap untuk mengecek progres murid?
    </p>
</div>
<section class="w-full p-10 bg-white shadow-smooth rounded-xl">
    <h3 class="mb-5">Progres Pembelajaran</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        @if (!$courses || count($courses) === 0)
            <div
                class="p-5 w-full h-full flex items-center justify-center flex-col min-h-72 gap-2 col-span-4 text-center">
                <img src="{{ asset('mindora-mascot.png') }}" alt="No Progress" class="w-[150px]">
                <h4 class="font-medium">Belum ada progres</h4>
                <p class="text-gray-400 text-sm">Belum ada progres pembelajaran yang dibuat oleh guru</p>
            </div>
        @else
            @foreach ($courses as $course)
                @php
                    $selected_theme = $course->subject;
                    $theme = config('constants.theme')[$selected_theme];
                @endphp
                <a href="{{ url('/courses/' . $course->id) }}" class="no-underline text-black h-fit">
                    <div class="w-full overflow-hidden shadow-lg h-full rounded-xl md:max-h-64 lg:max-h-80 hover:scale-105 duration-300 course-{{ $course->theme }}"
                        style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] ?? $theme['secondary'] }}">
                        <div class="px-6 py-4 flex flex-col justify-between h-full relative min-h-32">
                            <div class="flex justify-between">
                                <div class="font-bold text-xl mb-2" style="color: {{ $theme['tertiary'] }}">
                                    {{ $course->name }}</div>
                                <span class="badge badge-primary absolute top-4 right-6"
                                    style="background-color: {{ $theme['secondary'] }};">Score:
                                    {{ $course->averageScore }}</span>
                            </div>
                            <div class="w-full bg-white rounded-xl p-3 text-sm">
                                <div class="flex justify-between">
                                    <span>
                                        Lesson {{ $course->courseItemProgressCount }} / {{ $course->totalCourseItem }}
                                    </span>
                                    @if ($course->totalAssignment > 0)
                                        <span>
                                            Tugas {{ $course->assignmentProgressCount }} /
                                            {{ $course->totalAssignment }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex gap-2 items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-[#A6C3D1] h-2.5 rounded-full"
                                            style="width: {{ $course->totalCourseItem > 0 ? ($course->courseItemProgressCount / $course->totalCourseItem) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <div class="text-right text-xs">
                                        {{ $course->totalCourseItem > 0 ? number_format(($course->courseItemProgressCount / $course->totalCourseItem) * 100, 2) : '0.00' }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</section>

<section class="grid grid-cols-2">
    <div>

    </div>
</section>
<section class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 h-full">
    <div class="p-10 bg-white shadow-smooth rounded-xl">
        <h3 class="mb-5">Deadline Terdekat</h3>
        @if (!$deadlines || count($deadlines) === 0)
            <div class="p-5 w-full h-full flex items-center justify-center flex-col min-h-72 text-center gap-2">
                <img src="{{ asset('mindora-mascot.png') }}" alt="No Deadlines" class="w-[150px]">
                <h4 class="font-medium">Tidak ada deadline</h4>
                <p class="text-gray-400 text-sm">Belum ada deadline tugas yang mendekat</p>
            </div>
        @else
            @foreach ($deadlines as $deadline)
                @php
                    $selected_theme = $deadline['course']['subject'];
                    $theme = config('constants.theme')[$selected_theme];
                @endphp
                <div class="w-full overflow-hidden shadow-lg rounded-3xl hover:scale-[102%] duration-300 course-{{ $selected_theme }} e"
                    style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] ?? $theme['secondary'] }}">
                    <div class="px-6 py-4 flex flex-col justify-between ">
                        <div class="flex justify-between items-center">
                            <div class="">
                                <div class="badge badge-primary font-normal text-sm mb-2 text-white"
                                    style="background-color: {{ $theme['secondary'] }};">
                                    {{ ucfirst($deadline['course']['subject']) }}</div>
                                <div class="font-bold text-xl mb-2" style="color: {{ $theme['tertiary'] }}">
                                    {{ $deadline['courseItem']['name'] }}</div>
                            </div>
                            <span
                                class="">{{ $deadline['studentCount'] > 0 ? number_format(($deadline['submissionCount'] / $deadline['studentCount']) * 100, 2) : '0.00' }}%
                                Terkumpul</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="p-10 bg-white shadow-smooth rounded-xl">
        <div class="flex gap-4 items-center mb-5">
            <h3 class="w-1/2">Pesan untuk murid</h3>
        </div>

        @if (!$students || count($students) === 0)
            <div class="p-5 w-full h-full flex items-center justify-center flex-col min-h-72 text-center gap-2">
                <img src="{{ asset('mindora-mascot.png') }}" alt="No Students" class="w-[150px]">
                <h4 class="font-medium">Tidak ada murid yang terdaftar</h4>
                <p class="text-sm text-gray-400">Sebar kode kelas untuk menerima murid</p>
            </div>
        @else
            @foreach ($students as $student)
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border-2 border-gray-50">

                     {{ $stud
                    @if ($student->user->profile_picture)
                        <img src="{{ $student->user->profile_picture }}" alt="{{ $student->user->name }}"
                            class="w-12 h-12 rounded-full mr-4">
                    @else
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-300">
                            <img class="w-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ $student->user->name }}&color=7F9CF5&background=EBF4FF"
                                alt="{{ $student->user->name }}">
                        </div>
                    @endif
                    <div class="flex-1">
                        <div class="font-medium">{{ $student->user->name }}</div>
                    </div>
                    <button class="btn btn-outline"
                        onclick="document.getElementById('message_modal_{{ $student->id }}').showModal();">
                        Kirim Pesan
                    </button>
                    <dialog id="message_modal_{{ $student->id }}" class="modal">
                        <div class="modal-box">
                            <h3 class="font-semibold text-lg">Kirim Pesan ke {{ $student->user->name }}</h3>
                            <form method="POST"
                                action="{{ route('dashboard.teacher.send', ['studentId' => $student->id]) }}">
                                @csrf
                                <div class="form-control">
                                    <label for="message" class="label">Pesan</label>
                                    <textarea id="message" name="message" class="textarea textarea-bordered" required></textarea>
                                </div>
                                <div class="modal-action">
                                    <button type="button" class="btn"
                                        onclick="document.getElementById('message_modal_{{ $student->id }}').close();">Batalkan</button>
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </div>
            @endforeach
        @endif
    </div>
</section>
