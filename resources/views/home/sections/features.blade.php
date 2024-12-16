@php
    $features = [
        [
            'title' => 'Learning Management System (LMS)',
            'description' =>
                'Akses materi pelajaran dan kerjakan tugas dengan mudah! Semua pelajaran, kuis, dan ujian terorganisir rapi dalam satu platform, membantu Anda belajar lebih terstruktur dan mandiri.',
            'image' => 'asset_landing/lms-siswa.png',
        ],
        [
            'title' => 'Dashboard Tracker',
            'description' =>
                'Tahu apa yang harus dikerjakan selanjutnya! Dengan dashboard pribadi, Anda bisa melacak progres belajar, melihat jadwal ujian mendatang, grafik nilai, dan rekomendasi belajar yang disesuaikan dengan kebutuhanmu.',
            'image' => 'asset_landing/dasbor-siswa.png',
        ],
        [
            'title' => 'AI Flashcards & Question Generator',
            'description' =>
                'Dapatkan flashcard otomatis dan soal latihan yang dihasilkan langsung dari materi PDF. Dengan bantuan AI, siswa dapat mempersiapkan ujian atau kuis lebih efisien, mengulang materi secara terstruktur, serta menjawab pertanyaan yang relevan untuk meningkatkan pemahaman.',
            'image' => 'asset_landing/ai-flashcard.png',
        ],
        [
            'title' => 'Learning Management System (LMS)',
            'description' =>
                'Mengelola pembelajaran jadi lebih mudah! Dengan LMS kami, guru bisa mengunggah materi, membuat kuis, dan ujian dengan cepat. Semua tugas dan ujian siswa bisa dikelola dalam satu platform yang efisien dan terstruktur.',
            'image' => 'asset_landing/lms-guru.png',
        ],
        [
            'title' => 'Dashboard Tracker',
            'description' =>
                'Pantau progres siswa secara real-time! Dashboard kami menyajikan grafik data, ranking kelas, jadwal harian, dan deadline tugas. Semua yang Anda butuhkan untuk memberikan umpan balik tepat waktu dan mendukung perkembangan siswa.',
            'image' => 'asset_landing/dasbor-guru.png',
        ],
        [
            'title' => 'AI-powered Content Extraction',
            'description' =>
                'Maksimalkan waktu Anda dengan teknologi AI! Cukup unggah materi PDF, dan biarkan AI menganalisisnya untuk menghasilkan soal ujian dan kuis yang relevan. Menjadi lebih efektif dalam membuat soal yang sesuai dengan materi.',
            'image' => 'asset_landing/ai-powered.png',
        ],
        [
            'title' => 'AI Essay Checker',
            'description' =>
                'Pengecekan esai otomatis yang menghemat waktu! Dengan AI, esai siswa akan diperiksa dan diberi umpan balik secara instan. Anda bisa melakukan cross-check dengan mudah, memastikan esai dinilai objektif dan akurat.',
            'image' => 'asset_landing/ai-essay-checker.png',
        ],
    ];

    $studentFeatures = array_slice($features, 0, 3);
    $teacherFeatures = array_slice($features, 3);
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
            Pengajar
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
