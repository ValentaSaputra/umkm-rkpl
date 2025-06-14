<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="container mx-auto p-4">
        <!-- Grafik 1: Perbandingan Target Keuntungan dan Keuntungan Saat Ini -->
        <div class="mb-8 text-center">
            <h3 class="text-xl font-semibold mb-4">Perbandingan Target Keuntungan dan Keuntungan Saat Ini</h3>
            <!-- Widget dengan background, border, dan padding, serta di tengah halaman -->
            <div class="bg-white p-4 rounded-lg shadow-lg w-1/2 mx-auto">
                <canvas id="grafikKeuntungan1" class="w-full h-32"></canvas>
            </div>
        </div>
    </div>

    <!-- Script untuk Grafik -->
    <script>
        // Mendapatkan nilai target keuntungan dan keuntungan saat ini
        const targetKeuntungan = {{ $targetKeuntungan }};
        const keuntunganSaatIni = {{ $keuntunganSaatIni }};

        // Menentukan warna berdasarkan kondisi
        const warnaTarget = 'rgba(34, 197, 94, 0.2)'; // Hijau untuk target keuntungan
        const warnaKeuntunganSaatIni = keuntunganSaatIni > targetKeuntungan ? 'rgba(34, 197, 94, 0.2)' :
            'rgba(255, 99, 132, 0.2)'; // Hijau jika lebih dari target, merah jika tidak

        const borderTarget = 'rgba(34, 197, 94, 1)'; // Hijau border untuk target keuntungan
        const borderKeuntunganSaatIni = keuntunganSaatIni > targetKeuntungan ? 'rgba(34, 197, 94, 1)' :
            'rgba(255, 99, 132, 1)'; // Hijau border jika lebih dari target, merah jika tidak

        var ctx1 = document.getElementById('grafikKeuntungan1').getContext('2d');
        var grafikKeuntungan1 = new Chart(ctx1, {
            type: 'bar', // Jenis grafik
            data: {
                labels: ['Target Keuntungan', 'Keuntungan Saat Ini'],
                datasets: [{
                    label: 'Keuntungan',
                    data: [targetKeuntungan, keuntunganSaatIni],
                    backgroundColor: [warnaTarget, warnaKeuntunganSaatIni],
                    borderColor: [borderTarget, borderKeuntunganSaatIni],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
