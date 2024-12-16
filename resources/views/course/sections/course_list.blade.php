@props(['course', 'courseSections'])

{{-- Show all sections --}}
<div class="mt-8 flex flex-col gap-10">
    @foreach ($courseSections as $section)
        @include('course.components.section', ['section' => $section])
    @endforeach
    @if ($courseSections->isEmpty())
        @if ($isEdit)
            <div class="flex flex-col gap-4 items-center">
                <img src="{{ asset('mascot-teacher.png') }}" alt="Icon" class="w-40 h-auto">
                <p class="text-center text-gray-500">Belum ada section apapun</p>
            </div>
        @else
            @if (Auth::user()->userable_type === 'App\Models\Teacher')
                <div class="flex flex-col gap-4 items-center">
                    <img src="{{ asset('mascot-teacher.png') }}" alt="Icon" class="w-40 h-auto">
                    <p class="text-center text-gray-500">Belum ada section apapun</p>
                    <a href="{{ url()->current() }}/edit">
                        <button class="btn btn-primary">
                            <x-lucide-pencil class="w-4 h-4" />
                            Edit Kelas
                        </button>
                    </a>
                </div>
            @else
                <div class="flex flex-col gap-4 items-center">
                    <img src="{{ asset('mindora-mascot.png') }}" alt="Icon" class="w-20 h-auto">
                    <p class="text-center text-gray-500">Menunggu guru membuat section baru</p>
                </div>
            @endif
        @endif
    @endif
</div>
@if ($isEdit)
    <button onclick="add_section_modal.showModal()" class="btn btn-primary w-full mt-4">
        <x-lucide-plus class="w-6 h-6" />
        Tambah Section
    </button>
    @include('course.components.dialogs')
@endif
