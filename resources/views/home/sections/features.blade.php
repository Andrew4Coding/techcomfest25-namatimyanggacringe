@php
    $features = [
        [
            'title' => 'Learning Management System (LMS)',
            'description' =>
                'Mengelola pembelajaran jadi lebih mudah! Dengan LMS kami, guru bisa mengunggah materi, membuat kuis, dan ujian dengan cepat. Semua tugas dan ujian siswa bisa dikelola dalam satu platform yang efisien dan terstruktur.',
        ],
        [
            'title' => 'Analisis Data Pendidikan',
            'description' =>
                'Dapatkan wawasan mendalam tentang performa siswa dengan analisis data kami. Identifikasi kekuatan dan kelemahan siswa untuk meningkatkan strategi pengajaran.',
        ],
        [
            'title' => 'Platform Kolaborasi',
            'description' =>
                'Fasilitasi kolaborasi antara siswa dan guru dengan platform kami. Diskusi, proyek kelompok, dan komunikasi jadi lebih mudah dan terorganisir.',
        ],
        [
            'title' => 'Perpustakaan Digital',
            'description' =>
                'Akses ribuan buku dan jurnal akademik secara online. Perpustakaan digital kami memudahkan siswa dan guru untuk menemukan referensi yang mereka butuhkan.',
        ],
        [
            'title' => 'Sistem Penilaian Otomatis',
            'description' =>
                'Hemat waktu dengan sistem penilaian otomatis kami. Ujian dan tugas siswa dapat dinilai secara cepat dan akurat.',
        ],
        [
            'title' => 'Pembelajaran Adaptif',
            'description' =>
                'Sesuaikan materi pembelajaran dengan kebutuhan masing-masing siswa. Pembelajaran adaptif kami memastikan setiap siswa mendapatkan pengalaman belajar yang optimal.',
        ],
        [
            'title' => 'Manajemen Kelas',
            'description' =>
                'Atur jadwal, absensi, dan kegiatan kelas dengan mudah. Manajemen kelas kami membantu guru untuk tetap terorganisir dan efisien.',
        ],
        [
            'title' => 'Pelatihan Guru',
            'description' =>
                'Tingkatkan keterampilan mengajar dengan pelatihan guru kami. Dapatkan akses ke berbagai kursus dan workshop untuk pengembangan profesional.',
        ],
        [
            'title' => 'Portal Orang Tua',
            'description' =>
                'Libatkan orang tua dalam proses pendidikan dengan portal kami. Orang tua dapat memantau perkembangan anak mereka dan berkomunikasi dengan guru.',
        ],
        [
            'title' => 'Keamanan Data',
            'description' =>
                'Pastikan data siswa dan sekolah aman dengan solusi keamanan data kami. Kami menggunakan teknologi terbaru untuk melindungi informasi penting.',
        ],
    ];
@endphp

<section class="flex flex-col items-center my-20 gap-10">
    <div class="flex w-full justify-center gap-5 items-center">
        <button class="btn btn-primary">
            Murid
        </button>
        <button class="btn btn-outline">
            Guru
        </button>
    </div>
    <div class="max-w-2xl space-y-4 text-center">
        <h3 class="font-semibold text-2xl">"Data dan Teknologi untuk Pendidikan yang Lebih Baik."</h3>
        <p class="text-[#17194C]">
            – Fitur Utama Kami -
        </p>
    </div>

    @foreach ($features as $index => $feature)
        <div
            class="w-full flex flex-col-reverse md:flex-col lg:flex-row gap-10 {{ $index % 2 == 0 ? '' : 'lg:flex-row-reverse' }}">
            <div class="max-w-xl space-y-5 text-center md:text-left">
                <h3 class="font-semibold text-xl">{{ $feature['title'] }}</h3>
                <p class="leading-relaxed">
                    {{ $feature['description'] }}
                </p>
            </div>
            <div class="w-full bg-white h-[200px] md:h-[400px] p-5 shadow-xl rounded-xl">

            </div>
        </div>
    @endforeach
</section>
