<div class="mb-10">
    <h1 class="text-xl md:text-3xl font-bold">Halo, Teacher!</h1>
    <p class="font-medium gradient-blue text-transparent bg-clip-text text-sm md:text-base">
        Siap untuk mengecek progres murid?
    </p>
</div>

@include('dashboard.commons.progress_pembelajaran')

<section class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-10 h-full">
    @include('dashboard.role.teacher.sections.progress_murid')
    @include('dashboard.role.teacher.sections.rate_absensi', ['attendanceRate' => $courses[0]->attendanceRates])
    @include('dashboard.commons.deadline_terdekat')
    @include('dashboard.role.teacher.sections.pesan_murid')
</section>
