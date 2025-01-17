<div class="p-5 md:p-10 bg-white shadow-smooth rounded-xl h-full">
    <h3>Absensi Siswa</h3>

    {{-- Use Chart JS --}}
    <canvas id="absensiChart" style="width: 100%; height: 100%;"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('absensiChart').getContext('2d');
        var absensiChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                datasets: [{
                    label: 'Jumlah Kehadiran',
                    data: [12, 19, 3, 5, 2], // Replace with your actual data
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
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
