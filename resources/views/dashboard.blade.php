<x-app-layout>
    <div class="container mx-auto p-4">
        <!-- Grafik 1: Perbandingan Target Keuntungan dan Keuntungan Saat Ini -->
        <div class="mb-8 text-center">
            <h3 class="text-xl font-semibold mb-4">Perbandingan Target Keuntungan dan Keuntungan Saat Ini</h3>
            <div class="bg-white p-4 rounded-lg shadow-lg w-1/2 mx-auto">
                <canvas id="grafikKeuntungan1" class="w-full h-32"></canvas>
            </div>
        </div>

        <!-- 2 Box di bawah grafik -->
        <div class="flex space-x-4 mt-8 gap-4 mb-8 justify-center">
            <!-- Box 1: Kalkulator HPP -->
            <div class="flex flex-col items-center bg-white p-4 rounded-lg shadow-lg w-1/4 cursor-pointer hover:bg-gray-100 transition duration-200"
                onclick="window.location.href='{{ route('kalkulator.hpp') }}'">
                {{-- <img src="path_to_your_calculator_icon.jpg" alt="Kalkulator HPP" class="w-16 h-16 mb-4"> --}}
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-blue-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <span class="font-semibold text-gray-700">Kalkulator HPP</span>
            </div>

            <!-- Box 2: Target Penjualan -->
            <div class="flex flex-col items-center bg-white p-4 rounded-lg shadow-lg w-1/4 cursor-pointer hover:bg-gray-100 transition duration-200"
                onclick="window.location.href='{{ route('target.penjualan') }}'">
                {{-- <img src="path_to_your_target_icon.jpg" alt="Target Penjualan" class="w-16 h-16 mb-4"> --}}
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                        </path>
                    </svg>
                </div>

                <span class="font-semibold text-gray-700">Target Penjualan</span>
            </div>
            {{-- order history --}}
            <div class="flex flex-col items-center bg-white p-4 rounded-lg shadow-lg w-1/4 cursor-pointer hover:bg-gray-100 transition duration-200"
                onclick="window.location.href='{{ route('target.penjualan') }}'">
                {{-- <img src="path_to_your_target_icon.jpg" alt="Target Penjualan" class="w-16 h-16 mb-4"> --}}
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                        </path>
                    </svg>
                </div>

                <span class="font-semibold text-gray-700">Target Penjualan</span>
            </div>
        </div>

        <!-- Script untuk Grafik -->
        <script>
            const targetKeuntungan = {{ $targetKeuntungan }};
            const keuntunganSaatIni = {{ $keuntunganSaatIni }};

            const warnaTarget = 'rgba(34, 197, 94, 0.2)';
            const warnaKeuntunganSaatIni = keuntunganSaatIni > targetKeuntungan ? 'rgba(34, 197, 94, 0.2)' :
                'rgba(255, 99, 132, 0.2)';
            const borderTarget = 'rgba(34, 197, 94, 1)';
            const borderKeuntunganSaatIni = keuntunganSaatIni > targetKeuntungan ? 'rgba(34, 197, 94, 1)' :
                'rgba(255, 99, 132, 1)';

            var ctx1 = document.getElementById('grafikKeuntungan1').getContext('2d');
            var grafikKeuntungan1 = new Chart(ctx1, {
                type: 'bar',
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
    </div>
</x-app-layout>
