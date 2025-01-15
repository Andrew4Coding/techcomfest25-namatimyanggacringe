<a href="{{ url('/courses/' . $course->id) }}" class="no-underline text-black h-full">
    <div class="w-full overflow-hidden shadow-lg h-full rounded-xl hover:scale-[102%] duration-300 course-{{ $course->theme }}"
        style="background-color: {{ $theme['primary'] }}; color: {{ $theme['secondary'] ?? $theme['secondary'] }}">
        <div class="px-6 py-4 flex flex-col justify-between h-full relative min-h-32 gap-4">
            <div class="flex justify-between items-center">
                <div class="font-bold text-xl w-full overflow-clip" style="color: {{ $theme['tertiary'] }}">
                    {{ $course->name }}</div>
                <span class="badge badge-primary text-white min-w-[80px]"
                    style="background-color: {{ $theme['secondary'] }};">Score:
                    {{ $course->averageScore }}</span>
            </div>
            <div class="w-full bg-white rounded-xl p-3 text-sm">
                <div class="flex justify-between">
                    <span
                        class="text-xs"
                        style="color: {{ $theme['tertiary'] }};"
                    >
                        Lesson {{ $course->courseItemProgressCount }} / {{ $course->totalCourseItem }}
                    </span>
                    @if ($course->totalAssignment > 0)
                        <span class="text-xs">
                            Tugas {{ $course->assignmentProgressCount }} /
                            {{ $course->totalAssignment }}
                        </span>
                    @endif
                </div>
                <div class="flex gap-2 items-center">
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full"
                            style="width: {{ $course->totalCourseItem > 0 ? ($course->courseItemProgressCount / $course->totalCourseItem) * 100 : 0 }}%; background-color: {{ $theme['tertiary'] }};">
                        </div>
                    </div>
                    <div class="text-right text-xs"
                        style="color: {{ $theme['tertiary'] }};"
                    >
                        {{ $course->totalCourseItem > 0 ? number_format(($course->courseItemProgressCount / $course->totalCourseItem) * 100, 2) : '0.00' }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>
