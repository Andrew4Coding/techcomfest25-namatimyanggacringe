@props(['course', 'courseSections'])

{{-- Show all sections --}}
<div class="mt-8 flex flex-col gap-10">
    @foreach ($courseSections as $section)
        @include('course.components.section', ['section' => $section])
    @endforeach
    @if ($courseSections->isEmpty())
        @if ($isEdit) 
            <p class="text-center text-gray-500">Kamu belum membuat section apapun</p>
        @else
            <p class="text-center text-gray-500">Menunggu guru membuat pelajaran menarik ...</p>
        @endif
    @endif
</div>
@if ($isEdit) 
    <button onclick="add_section_modal.showModal()" class="btn btn-primary w-full mt-4">
        + Add Section
    </button>
    @include('course.components.dialogs')
@endif
