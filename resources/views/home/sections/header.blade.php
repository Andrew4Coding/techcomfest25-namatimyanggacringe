<section class="h-screen flex flex-col items-center justify-center gap-5 relative">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('home/circle1.png') }}" alt="Icon" class="w-96 h-96 absolute bottom-20 -left-72">
        <img src="{{ asset('home/circle2.png') }}" alt="Icon" class="w-96 h-96 absolute top-0 -right-72">
        <img src="{{ asset('home/line.png') }}" alt="Icon" class="w-full absolute bottom-1/2 translate-y-20 left-1/2 transform -translate-x-1/2">
    </div>
    <img src="{{ asset('mindora-mascot.png') }}" alt="Icon" class="hidden lg:flex absolute z-30 w-72 h-auto bottom-1/2 translate-y-40 right-10 lg:right-10 xl:right-20 -rotate-12">

    <div class="z-10 flex flex-col items-center justify-center gap-5">
        <h1 class="text-center max-w-2xl">
            Solusi Belajar dan Mengajar Lebih Pintar dengan
            <span class="bg-gradient-to-r from-blue-500 to-[#945AC6] text-transparent bg-clip-text">
                Mindora AI.
            </span>
        </h1>
        <div class="text-center">
            <p>
                Belajar lebih efektif, mengajar lebih efisien
            </p>
            <b>
                —semua dalam satu platform.
            </b>
        </div>
        <a href="/dashboard">
            <button class="btn btn-primary px-20">
                @if (Auth::check())
                    Lanjutkan Belajar
                @else
                    Mulai Sekarang
                @endif
            </button>
        </a>
    </div>
</section>