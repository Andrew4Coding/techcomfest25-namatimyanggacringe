@php
    $selectedCourse = request()->input('course-progress', $courses[0]->id);    
    $topStudents = $courses->find($selectedCourse)->topStudents;
@endphp
<div class="p-5 md:p-10 bg-white shadow-smooth rounded-xl h-full">
    <div class="w-full flex items-center justify-between mb-2">
        <h3>Progress Murid</h3>
        <a href="/dashboard/progress">
            <button class="btn btn-outline">
                View All
            </button>
        </a>
    </div>
    <form action="">
        <select name="course-progress" id="" class="select" onchange="this.form.submit()">
            @foreach ($courses as $course)
                <option value="{{$course->id}}"
                    @if ($course->id == $selectedCourse)
                        selected
                    @endif
                    >
                    {{$course->name}}
                </option>
            @endforeach
        </select>
    </form>
    <div class="flex flex-col gap-5 mt-5">
        @if (count($topStudents) == 0)
            <div class="w-full flex flex-col items-center justify-center gap-5">
                <img src="{{ asset('mascot-nyari.png') }}" alt="Login" class="w-[100px]">
                <p class="text-center text-sm text-gray-500">Belum ada murid yang mengikuti kelas ini</p>
            </div>
        @else
            @foreach ($topStudents as $student)
                <div class="flex items-center justify-between rounded-xl text-sm text-gray-500">
                    <p>{{$student->user->name}}</p>
                    <p>{{$student->averageScore}}</p>
                </div>
            @endforeach
        @endif
    </div>
</div>