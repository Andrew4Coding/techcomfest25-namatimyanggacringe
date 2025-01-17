<div class="mb-10">
    <h1 class="text-3xl font-bold">Halo, {{ Auth::user()->name }}!</h1>
    <p class="font-medium gradient-blue text-transparent bg-clip-text">
        Siap untuk melanjutkan perjalanan belajarmu?
    </p>
</div>

@include('dashboard.commons.progress_pembelajaran')

<section class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 h-full">
    @include('dashboard.commons.deadline_terdekat')

    @include('dashboard.role.student.sections.pesan_dari_guru')
</section>
