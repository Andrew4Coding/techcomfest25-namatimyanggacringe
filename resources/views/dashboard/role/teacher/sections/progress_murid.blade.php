<div class="p-5 md:p-10 bg-white shadow-smooth rounded-xl h-full">
    <div class="w-full flex items-center justify-between">
        <h3>Progress Murid</h3>
        <button class="btn btn-outline">
            View All
        </button>
    </div>
    <div class="flex flex-col gap-5 mt-5">
        @if (count($topStudents) == 0 || $topStudents[0]->averageScore == 0)
            <p class="text-center text-sm text-gray-500">Belum ada murid yang mengikuti kelas ini</p>

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