@props(['course', 'courseSections'])
<div class="flex w-full gap-6">
    <button class="btn btn-error"
        onclick="document.getElementById('delete_course_modal_{{ $course->id }}').showModal();">
        <x-lucide-trash class="w-6 h-6" />
        Delete Course
    </button>

    <button onclick="edit_course_modal.showModal()" class="btn btn-secondary">
        Edit Course
    </button>

    <button onclick="add_section_modal.showModal()" class="btn btn-primary">
        + Add Section
    </button>
</div>

{{-- Show all sections --}}
<div class="mt-8 flex flex-col gap-4">
    @foreach ($courseSections as $section)
        @include('course.components.section', ['section' => $section])
    @endforeach
</div>

@include('course.components.dialogs')