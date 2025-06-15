<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target Penjualan - {{ $currentMonth }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out;
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

        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .progress-bar {
            transition: width 0.5s ease-in-out;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
            transform: translateX(500px);
            transition: transform 0.3s ease-in-out;
        }

        .notification.show {
            transform: translateX(0);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <!-- Notification -->
    @if (session('success'))
        <div class="notification bg-green-500 text-white p-4 rounded-lg shadow-lg" id="notification">
            <div class="flex items-center">
                <span class="text-xl mr-2">✅</span>
                <div>
                    <p class="font-semibold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="closeNotification()" class="ml-4 text-white hover:text-gray-200">
                    <span class="text-xl">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="notification bg-red-500 text-white p-4 rounded-lg shadow-lg" id="notification">
            <div class="flex items-center">
                <span class="text-xl mr-2">❌</span>
                <div>
                    <p class="font-semibold">Error!</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
                <button onclick="closeNotification()" class="ml-4 text-white hover:text-gray-200">
                    <span class="text-xl">&times;</span>
                </button>
            </div>
        </div>
    @endif

    <div class="container mx-auto px-4 py-6 max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-8 fade-in">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                🎯 Target Penjualan
            </h1>
            <p class="text-gray-600 text-sm md:text-base">
                Kelola dan monitor target penjualan bisnis Anda - {{ $currentMonth }}
            </p>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Target Bulanan -->
            <div class="bg-white rounded-xl shadow-lg p-6 gradient-primary text-white slide-up">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Target Bulanan</p>
                        <p class="text-xl font-bold" id="monthlyTargetDisplay">Rp
                            {{ number_format($monthlyTarget, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl">📅</span>
                    </div>
                </div>
            </div>

            <!-- Pencapaian -->
            <div class="bg-white rounded-xl shadow-lg p-6 gradient-success text-white slide-up"
                style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Pencapaian</p>
                        <p class="text-xl font-bold" id="achievementDisplay">Rp
                            {{ number_format($currentAchievement, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                </div>
            </div>

            <!-- Persentase -->
            <div class="bg-white rounded-xl shadow-lg p-6 gradient-warning text-white slide-up"
                style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Persentase</p>
                        <p class="text-xl font-bold" id="percentageDisplay">{{ $achievementPercentage }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl">📊</span>
                    </div>
                </div>
            </div>

            <!-- Sisa Target -->
            <div class="bg-white rounded-xl shadow-lg p-6 gradient-info text-white slide-up"
                style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Sisa Target</p>
                        <p class="text-xl font-bold" id="remainingDisplay">Rp
                            {{ number_format($remaining, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl">⏰</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Input Target -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 fade-in" style="animation-delay: 0.4s">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                ⚙️ Atur Target Penjualan
            </h2>

            <form action="{{ route('sales-target.update') }}" method="POST" id="targetForm" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                    <div>
                        <label for="monthlyTarget" class="block text-sm font-medium text-gray-700 mb-2">
                            📈 Target Penjualan Bulanan (Rp)
                        </label>
                        <input type="number" id="monthlyTarget" name="monthlyTarget"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('monthlyTarget') border-red-500 @enderror"
                            placeholder="25000000" min="0" step="100000"
                            value="{{ old('monthlyTarget', $monthlyTarget) }}" required>
                        @error('monthlyTarget')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Contoh: 25000000 untuk target 25 juta</p>
                    </div>

                    {{-- <div>
                        <label for="currentAchievement" class="block text-sm font-medium text-gray-700 mb-2">
                            💰 Pencapaian Saat Ini (Rp) - Opsional
                        </label>
                        <input type="number" id="currentAchievement" name="currentAchievement"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="5000000" min="0" step="100000"
                            value="{{ old('currentAchievement', $currentAchievement) }}">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah pencapaian</p>
                    </div> --}}
                </div>

                <!-- Tombol Submit -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white p-4 rounded-lg font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="submitText">🚀 Update Target Penjualan</span>
                    <span id="loadingText" class="hidden">⏳ Memproses...</span>
                </button>
            </form>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 fade-in" style="animation-delay: 0.5s">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Progress Target</h3>
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Pencapaian Target</span>
                <span class="text-sm font-semibold text-gray-800"
                    id="progressPercent">{{ $achievementPercentage }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                <div class="progress-bar bg-gradient-to-r from-green-400 to-blue-500 h-4 rounded-full" id="progressBar"
                    style="width: {{ min($achievementPercentage, 100) }}%"></div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Tercapai: </span>
                    <span class="font-semibold" id="progressAchieved">Rp
                        {{ number_format($currentAchievement, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Sisa: </span>
                    <span class="font-semibold" id="progressRemaining">Rp
                        {{ number_format($remaining, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Achievement Status -->
        @if ($achievementPercentage >= 100)
            <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8 fade-in"
                style="animation-delay: 0.6s">
                <div class="flex items-center">
                    <span class="text-4xl mr-4">🎉</span>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">Selamat! Target Tercapai!</h3>
                        <p class="text-green-600">Anda telah mencapai {{ $achievementPercentage }}% dari target bulan
                            ini.</p>
                    </div>
                </div>
            </div>
        @elseif($achievementPercentage >= 80)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8 fade-in"
                style="animation-delay: 0.6s">
                <div class="flex items-center">
                    <span class="text-4xl mr-4">🔥</span>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-800">Hampir Tercapai!</h3>
                        <p class="text-yellow-600">Anda sudah mencapai {{ $achievementPercentage }}% dari target.
                            Sedikit lagi!</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tips & Strategi -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 fade-in" style="animation-delay: 0.8s">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">💡 Tips Mencapai Target</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl mb-2">🎯</div>
                    <h4 class="font-semibold text-gray-800 mb-2">Focus Strategi</h4>
                    <p class="text-sm text-gray-600">Identifikasi produk terlaris dan fokuskan promosi pada produk
                        tersebut</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl mb-2">📱</div>
                    <h4 class="font-semibold text-gray-800 mb-2">Digital Marketing</h4>
                    <p class="text-sm text-gray-600">Manfaatkan media sosial dan platform digital untuk menjangkau
                        lebih banyak customer</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-2xl mb-2">🤝</div>
                    <h4 class="font-semibold text-gray-800 mb-2">Customer Retention</h4>
                    <p class="text-sm text-gray-600">Berikan pelayanan terbaik untuk mempertahankan customer lama</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm fade-in" style="animation-delay: 0.9s">
            <p>© 2025 Target Penjualan - Raih target dengan strategi yang tepat</p>
        </div>
    </div>

    <script>
        // Global variables
        let monthlyTarget = {{ $monthlyTarget }};
        let currentAchievement = {{ $currentAchievement }};
        let remaining = {{ $remaining }};

        // Format Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        // Update dashboard statistics
        function updateStats() {
            const percentage = monthlyTarget > 0 ? Math.round((currentAchievement / monthlyTarget) * 100) : 0;
            remaining = Math.max(0, monthlyTarget - currentAchievement);

            // Update display values
            document.getElementById('monthlyTargetDisplay').textContent = formatRupiah(monthlyTarget);
            document.getElementById('achievementDisplay').textContent = formatRupiah(currentAchievement);
            document.getElementById('percentageDisplay').textContent = percentage + '%';
            document.getElementById('remainingDisplay').textContent = formatRupiah(remaining);

            // Update progress bar
            document.getElementById('progressPercent').textContent = percentage + '%';
            document.getElementById('progressBar').style.width = Math.min(percentage, 100) + '%';
            document.getElementById('progressAchieved').textContent = formatRupiah(currentAchievement);
            document.getElementById('progressRemaining').textContent = formatRupiah(remaining);

            // Change progress bar color based on achievement
            const progressBar = document.getElementById('progressBar');
            if (percentage >= 100) {
                progressBar.className = 'progress-bar bg-gradient-to-r from-green-400 to-green-600 h-4 rounded-full';
            } else if (percentage >= 80) {
                progressBar.className = 'progress-bar bg-gradient-to-r from-yellow-400 to-orange-500 h-4 rounded-full';
            } else {
                progressBar.className = 'progress-bar bg-gradient-to-r from-blue-400 to-purple-500 h-4 rounded-full';
            }
        }

        // Handle form submission
        document.getElementById('targetForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');

            // Show loading state
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            loadingText.classList.remove('hidden');
        });

        // Update form values when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateStats();

            // Show notification if exists
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                // Auto close after 5 seconds
                setTimeout(() => {
                    closeNotification();
                }, 5000);
            }
        });

        // Close notification
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        // Real-time input formatting
        document.getElementById('monthlyTarget').addEventListener('input', function(e) {
            const value = parseFloat(e.target.value) || 0;
            monthlyTarget = value;
            updateStats();
        });

        document.getElementById('currentAchievement').addEventListener('input', function(e) {
            const value = parseFloat(e.target.value) || 0;
            currentAchievement = value;
            updateStats();
        });
    </script>
</body>

</html>
