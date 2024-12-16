<section class="text-center flex flex-col items-center justify-center gap-5">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <style>
        .swiper-container {
            width: 90%;
            max-width: 1200px;
        }

        .swiper-slide img {
            width: 100%;
            object-fit: cover;
        }
    </style>

    <div class="max-w-2xl space-y-4">
        <h3 class="font-semibold text-2xl">"Meningkatkan Pendidikan dengan Teknologi AI."</h3>
        <p class="text-[#17194C]/30">Web app kami adalah LMS berbasis AI yang menghadirkan pengalaman belajar personal
            untuk siswa dan alat bantu efisien untuk guru.</p>
    </div>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide max-w-2xl flex flex-col space-y-5">
                <img class="w-full min-h-[300px] rounded-2xl shadow-smooth" src="{{ asset('asset_landing/ai-flashcard.png') }}"  alt=""/>
                <div class="course-card">
                    <h3>Solusi lengkap untuk pendidikan modern.</h3>
                </div>
            </div>
            <div class="swiper-slide max-w-2xl flex flex-col space-y-5">
                <img class="w-full min-h-[300px] rounded-2xl shadow-smooth" src="{{ asset('asset_landing/lms-siswa.png') }}"  alt=""/>
                <div class="course-card">
                    <h3>LMS all-in-one untuk siswa dan guru</h3>
                </div>
            </div>
            <div class="swiper-slide max-w-2xl flex flex-col space-y-5">
                <img class="w-full min-h-[300px] rounded-2xl shadow-smooth" src="{{ asset('asset_landing/carousel-3.png') }}"  alt=""/>
                <div class="course-card">
                    <h3>Personalisasi belajar dengan AI</h3>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            loop: true, // Infinite looping
            slidesPerView: 'auto', // Dynamic slide width
            centeredSlides: true, // Center the current slide
            spaceBetween: 30, // Space between slides
            effect: 'coverflow', // Enable coverflow effect
            coverflowEffect: {
                rotate: 0, // No rotation
                stretch: 0, // No stretch
                depth: 200, // Depth effect
                modifier: 1, // Intensity of the depth
                slideShadows: false, // Disable slide shadows
                scale: 0.8, // Shrink inactive slides to 80%
            },
            grabCursor: true, // Enables "grab" hand cursor
            autoplay: {
                delay: 3000, // Delay between transitions
                disableOnInteraction: false, // Continue autoplay after user interactions
            },
        });
    </script>
</section>
