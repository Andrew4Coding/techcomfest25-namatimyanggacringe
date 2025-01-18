@php
    $selectedCourseParam = request()->input('course', $courses[0]->id);
    $selectedCourse = $courses->firstWhere('id', $selectedCourseParam);
@endphp

<div class="p-5 md:p-10 bg-white shadow-smooth rounded-xl h-full">
    <h3 class="h-16 mb-2 flex items-center w-full">Absensi Siswa</h3>
    <form action="">
        <select name="course" id="" class="select" onchange="this.form.submit()">
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @if ($course->id == $selectedCourseParam) selected @endif>
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Use Chart JS --}}
    <canvas id="absensiChart" style="width: 100%; height: 100%;"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        /*
            List of {date, rate} objects
            */
        const data = @json($selectedCourse->attendanceRates);

        // Group data by date
        const groupedData = data.reduce((acc, item) => {
            const date = new Date(item.date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
            });
            if (!acc[date]) {
            acc[date] = [];
            }
            acc[date].push(item.rate);
            return acc;
        }, {});

        // Calculate average rate for each date
        const labels = Object.keys(groupedData);
        const attendanceRates = labels.map(date => {
            const rates = groupedData[date];
            const sum = rates.reduce((a, b) => a + b, 0);
            return (sum / rates.length) * 100; // Convert to percentage
        });
        
        var ctx = document.getElementById('absensiChart').getContext('2d');

        // Create gradient
        var gradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
        gradient.addColorStop(0, 'rgba(1, 122, 228, 1)');
        gradient.addColorStop(0.5, 'rgba(4, 162, 224, 0.5)');
        gradient.addColorStop(1, 'rgba(7, 197, 220, 0.2)');

        var absensiChart = new Chart(ctx, {
            type: 'line',
            data: {
            labels: labels,
            datasets: [{
                label: 'Persentase Kehadiran',
                data: attendanceRates,
                backgroundColor: gradient,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: true,
                tension: 0.4 // This makes the line curvy
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true,
                max: 100
                }
            }
            }
        });
    </script>

</div>
