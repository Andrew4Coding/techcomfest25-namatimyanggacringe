@php
    $features = [
        [
            'title' => 'Learning Management System (LMS)',
            'description' =>
                'Mengelola pembelajaran jadi lebih mudah! Dengan LMS kami, guru bisa mengunggah materi, membuat kuis, dan ujian dengan cepat. Semua tugas dan ujian siswa bisa dikelola dalam satu platform yang efisien dan terstruktur.',
            'image' => 'path/to/lms-image.jpg',
        ],
        [
            'title' => 'Analisis Data Pendidikan',
            'description' =>
                'Dapatkan wawasan mendalam tentang performa siswa dengan analisis data kami. Identifikasi kekuatan dan kelemahan siswa untuk meningkatkan strategi pengajaran.',
            'image' => 'path/to/analisis-image.jpg',
        ],
        [
            'title' => 'Platform Kolaborasi',
            'description' =>
                'Fasilitasi kolaborasi antara siswa dan guru dengan platform kami. Diskusi, proyek kelompok, dan komunikasi jadi lebih mudah dan terorganisir.',
            'image' => 'path/to/kolaborasi-image.jpg',
        ],
        [
            'title' => 'Perpustakaan Digital',
            'description' =>
                'Akses ribuan buku dan jurnal akademik secara online. Perpustakaan digital kami memudahkan siswa dan guru untuk menemukan referensi yang mereka butuhkan.',
            'image' => 'path/to/perpustakaan-image.jpg',
        ],
        [
            'title' => 'Sistem Penilaian Otomatis',
            'description' =>
                'Hemat waktu dengan sistem penilaian otomatis kami. Ujian dan tugas siswa dapat dinilai secara cepat dan akurat.',
            'image' => 'path/to/penilaian-image.jpg',
        ],
        [
            'title' => 'Pembelajaran Adaptif',
            'description' =>
                'Sesuaikan materi pembelajaran dengan kebutuhan masing-masing siswa. Pembelajaran adaptif kami memastikan setiap siswa mendapatkan pengalaman belajar yang optimal.',
            'image' => 'path/to/adaptif-image.jpg',
        ],
        [
            'title' => 'Manajemen Kelas',
            'description' =>
                'Atur jadwal, absensi, dan kegiatan kelas dengan mudah. Manajemen kelas kami membantu guru untuk tetap terorganisir dan efisien.',
            'image' => 'path/to/manajemen-image.jpg',
        ],
        [
            'title' => 'Pelatihan Guru',
            'description' =>
                'Tingkatkan keterampilan mengajar dengan pelatihan guru kami. Dapatkan akses ke berbagai kursus dan workshop untuk pengembangan profesional.',
            'image' => 'path/to/pelatihan-image.jpg',
        ],
        [
            'title' => 'Portal Orang Tua',
            'description' =>
                'Libatkan orang tua dalam proses pendidikan dengan portal kami. Orang tua dapat memantau perkembangan anak mereka dan berkomunikasi dengan guru.',
            'image' => 'path/to/portal-image.jpg',
        ],
        [
            'title' => 'Keamanan Data',
            'description' =>
                'Pastikan data siswa dan sekolah aman dengan solusi keamanan data kami. Kami menggunakan teknologi terbaru untuk melindungi informasi penting.',
            'image' => 'path/to/keamanan-image.jpg',
        ],
    ];

    $studentFeatures = array_slice($features, 0, 5);
    $teacherFeatures = array_slice($features, 5);
@endphp

<section class="flex flex-col items-center my-20 gap-10">
    <div class="max-w-2xl space-y-4 text-center">
        <h3 class="font-semibold text-2xl">"Data dan Teknologi untuk Pendidikan yang Lebih Baik."</h3>
        <p class="text-[#17194C]">
            – Fitur Utama Kami -
        </p>
    </div>
    <div class="grid grid-cols-2 justify-center gap-5 items-center bg-white w-full p-4 rounded-xl shadow-smooth">
        <button id="studentTab" class="btn btn-primary px-20 w-full" onclick="showFeatures('student')">
            Murid
        </button>
        <button id="teacherTab" class="btn btn-outline px-20 w-full" onclick="showFeatures('teacher')">
            Guru
        </button>
    </div>

    <div id="studentFeatures" class="features w-full space-y-10">
        @foreach ($studentFeatures as $index => $feature)
            <div
                class="w-full flex flex-col-reverse md:flex-col lg:flex-row gap-10 {{ $index % 2 == 0 ? '' : 'lg:flex-row-reverse' }}">
                <div class="max-w-xl space-y-5 text-center md:text-left">
                    <h3 class="font-semibold text-xl">{{ $feature['title'] }}</h3>
                    <p class="leading-relaxed">
                        {{ $feature['description'] }}
                    </p>
                </div>
                <div class="w-full bg-white h-[200px] md:h-[400px] p-5 shadow-xl rounded-xl">
                    <img src="{{ asset($feature['image']) }}" alt="{{ $feature['title'] }}" class="w-full h-full object-cover rounded-xl">
                </div>
            </div>
        @endforeach
    </div>

    <div id="teacherFeatures" class="features hidden w-full space-y-10">
        @foreach ($teacherFeatures as $index => $feature)
            <div
                class="w-full flex flex-col-reverse md:flex-col lg:flex-row gap-10 {{ $index % 2 == 0 ? '' : 'lg:flex-row-reverse' }}">
                <div class="max-w-xl space-y-5 text-center md:text-left">
                    <h3 class="font-semibold text-xl">{{ $feature['title'] }}</h3>
                    <p class="leading-relaxed">
                        {{ $feature['description'] }}
                    </p>
                </div>
                <div class="w-full bg-white h-[200px] md:h-[400px] p-5 shadow-xl rounded-xl">
                    <img src="{{ asset($feature['image']) }}" alt="{{ $feature['title'] }}" class="w-full h-full object-cover rounded-xl">
                </div>
            </div>
        @endforeach
    </div>
</section>

<script>
    function showFeatures(type) {
        const studentTab = document.getElementById('studentTab');
        const teacherTab = document.getElementById('teacherTab');
        const studentFeatures = document.getElementById('studentFeatures');
        const teacherFeatures = document.getElementById('teacherFeatures');

        if (type === 'student') {
            studentTab.classList.add('btn-primary');
            studentTab.classList.remove('btn-outline');
            teacherTab.classList.add('btn-outline');
            teacherTab.classList.remove('btn-primary');
            studentFeatures.classList.remove('hidden');
            teacherFeatures.classList.add('hidden');
        } else {
            teacherTab.classList.add('btn-primary');
            teacherTab.classList.remove('btn-outline');
            studentTab.classList.add('btn-outline');
            studentTab.classList.remove('btn-primary');
            teacherFeatures.classList.remove('hidden');
            studentFeatures.classList.add('hidden');
        }
    }
</script>
