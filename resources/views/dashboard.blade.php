<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Business Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        /* Custom gradient backgrounds */
        .gradient-bg-1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-bg-2 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .gradient-bg-3 {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .gradient-bg-4 {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        .slide-up {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hover effects */
        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        /* Chart container responsive */
        .chart-container {
            position: relative;
            height: 300px;
        }

        @media (max-width: 640px) {
            .chart-container {
                height: 250px;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Header Section -->
        <div class="text-center mb-8 fade-in">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                📊 Dashboard Analytics
            </h1>
            <p class="text-gray-600 text-sm md:text-base">
                Monitor performa bisnis Anda secara real-time
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Card 1: Target Keuntungan -->
            <div class="bg-white rounded-xl shadow-lg p-6 gradient-bg-1 text-white hover-scale slide-up">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Target Keuntungan</p>
                        <p class="text-2xl font-bold" id="targetDisplay">
                            Rp {{ number_format($monthlyTarget, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl">🎯</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Keuntungan Saat Ini -->
            <div class="bg-white rounded-xl shadow-lg p-6 gradient-bg-2 text-white hover-scale slide-up"
                style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Keuntungan Hari Ini</p>
                        <p class="text-2xl font-bold" id="currentDisplay">
                            Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Persentase Pencapaian -->
            <div class="bg-white rounded-xl shadow-lg p-6 gradient-bg-3 text-white hover-scale slide-up"
                style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Pencapaian</p>
                        <p class="text-2xl font-bold" id="percentageDisplay">
                            {{ round(($todaySales / $monthlyTarget) * 100, 2) }}%
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl">📈</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Status -->
            <div class="bg-white rounded-xl shadow-lg p-6 gradient-bg-4 text-white hover-scale slide-up"
                style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Status</p>
                        <p class="text-xl font-bold" id="statusDisplay">
                            @php
                                $percentage = ($todaySales / $monthlyTarget) * 100;
                                if ($percentage >= 100) {
                                    echo 'Target Tercapai';
                                } elseif ($percentage >= 80) {
                                    echo 'Hampir Tercapai';
                                } elseif ($percentage >= 50) {
                                    echo 'Dalam Progress';
                                } else {
                                    echo 'Perlu Ditingkatkan';
                                }
                            @endphp
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl" id="statusIcon">
                            @php
                                if ($percentage >= 100) {
                                    echo '🎉';
                                } elseif ($percentage >= 80) {
                                    echo '🔥';
                                } elseif ($percentage >= 50) {
                                    echo '⚡';
                                } else {
                                    echo '📊';
                                }
                            @endphp
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden fade-in" style="animation-delay: 0.4s">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">
                    📊 Perbandingan Target vs Realisasi Hari Ini
                </h2>
                <p class="text-gray-600 text-sm">
                    Grafik perbandingan target keuntungan bulanan dengan pencapaian hari ini
                </p>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="grafikKeuntungan1"></canvas>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                🛠️ Tools & Analytics
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1: Input Pesanan -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-scale cursor-pointer transition-all duration-300 hover:shadow-xl slide-up"
                    onclick="window.location.href='{{ route('orders.create') }}'" style="animation-delay: 0.6s">
                    <div class="p-6">
                        <div class="w-16 h-16 gradient-bg-2 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Input Pesanan</h3>
                        <p class="text-gray-600 text-sm text-center">Menginput pesanan yang terjual</p>
                    </div>
                    <div class="px-6 pb-6">
                        <div class="w-full bg-pink-100 text-pink-800 text-center py-2 rounded-lg text-sm font-medium">
                            Input Pesanan
                        </div>
                    </div>
                </div>
                <!-- Card 2: Target Penjualan -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-scale cursor-pointer transition-all duration-300 hover:shadow-xl slide-up"
                    onclick="window.location.href='/target-penjualan'" style="animation-delay: 0.6s">
                    <div class="p-6">
                        <div class="w-16 h-16 gradient-bg-2 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Target Penjualan</h3>
                        <p class="text-gray-600 text-sm text-center">Atur dan monitor target penjualan</p>
                    </div>
                    <div class="px-6 pb-6">
                        <div class="w-full bg-pink-100 text-pink-800 text-center py-2 rounded-lg text-sm font-medium">
                            Atur Target
                        </div>
                    </div>
                </div>
                <!-- Card 3: Kalkulator Hpp -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-scale cursor-pointer transition-all duration-300 hover:shadow-xl slide-up"
                    onclick="window.location.href='/kalkulator-hpp'" style="animation-delay: 0.6s">
                    <div class="p-6">
                        <div class="w-16 h-16 gradient-bg-2 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Kalkulator Hpp</h3>
                        <p class="text-gray-600 text-sm text-center">Menghitung Harga Produksi Produk</p>
                    </div>
                    <div class="px-6 pb-6">
                        <div class="w-full bg-pink-100 text-pink-800 text-center py-2 rounded-lg text-sm font-medium">
                            Kalkulator
                        </div>
                    </div>
                </div>
                <!-- Card 4: Order History -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-scale cursor-pointer transition-all duration-300 hover:shadow-xl slide-up"
                    onclick="window.location.href='{{ route('order-history') }}'" style="animation-delay: 0.6s">
                    <div class="p-6">
                        <div
                            class="w-16 h-16 gradient-bg-2 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Order History</h3>
                        <p class="text-gray-600 text-sm text-center">Melihat pesanan yang terjual</p>
                    </div>
                    <div class="px-6 pb-6">
                        <div class="w-full bg-pink-100 text-pink-800 text-center py-2 rounded-lg text-sm font-medium">
                            Riwayat Pesanan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm fade-in" style="animation-delay: 0.9s">
            <p>© 2025 Business Analytics Dashboard - Kelola bisnis dengan cerdas</p>
        </div>
    </div>

    <script>
        // Data dari controller
        const targetKeuntungan = {{ $monthlyTarget }};
        const keuntunganHariIni = {{ $todaySales }};
        let profitChart = null;

        // Format Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        // Calculate percentage
        function calculatePercentage(current, target) {
            return Math.round((current / target) * 100);
        }

        // Update status card
        function updateStatus(percentage) {
            const statusDisplay = document.getElementById('statusDisplay');
            const statusIcon = document.getElementById('statusIcon');

            if (percentage >= 100) {
                statusDisplay.textContent = 'Target Tercapai';
                statusIcon.textContent = '🎉';
            } else if (percentage >= 80) {
                statusDisplay.textContent = 'Hampir Tercapai';
                statusIcon.textContent = '🔥';
            } else if (percentage >= 50) {
                statusDisplay.textContent = 'Dalam Progress';
                statusIcon.textContent = '⚡';
            } else {
                statusDisplay.textContent = 'Perlu Ditingkatkan';
                statusIcon.textContent = '📊';
            }
        }

        // Initialize Chart
        function initChart() {
            const percentage = calculatePercentage(keuntunganHariIni, targetKeuntungan);

            const warnaTarget = 'rgba(99, 102, 241, 0.8)';
            const warnaKeuntungan = percentage >= 100 ?
                'rgba(34, 197, 94, 0.8)' :
                (percentage >= 50 ? 'rgba(250, 204, 21, 0.8)' : 'rgba(248, 113, 113, 0.8)');

            const borderTarget = 'rgba(99, 102, 241, 1)';
            const borderKeuntungan = percentage >= 100 ?
                'rgba(34, 197, 94, 1)' :
                (percentage >= 50 ? 'rgba(250, 204, 21, 1)' : 'rgba(248, 113, 113, 1)');

            const ctx = document.getElementById('grafikKeuntungan1').getContext('2d');
            profitChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Target Bulanan', 'Pencapaian Hari Ini'],
                    datasets: [{
                        label: 'Keuntungan (Rupiah)',
                        data: [targetKeuntungan, keuntunganHariIni],
                        backgroundColor: [warnaTarget, warnaKeuntungan],
                        borderColor: [borderTarget, borderKeuntungan],
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatRupiah(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatRupiah(value);
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutBounce'
                    }
                }
            });
        }

        // Fetch today's sales data
        async function fetchTodaySales() {
            try {
                const response = await fetch('/api/today-sales');
                const data = await response.json();

                // Update cards
                document.getElementById('currentDisplay').textContent = data.formatted;

                const percentage = calculatePercentage(data.total, targetKeuntungan);
                document.getElementById('percentageDisplay').textContent = percentage + '%';
                updateStatus(percentage);

                // Update chart if exists
                if (profitChart) {
                    profitChart.data.datasets[0].data[1] = data.total;
                    profitChart.update();
                }
            } catch (error) {
                console.error('Error fetching today sales:', error);
            }
        }

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initChart();

            // Update data every 30 seconds
            setInterval(fetchTodaySales, 30000);
        });
    </script>
</body>

</html>
