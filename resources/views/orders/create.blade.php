<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                📝 Input Pesanan Baru
            </h1>
            <p class="text-gray-600">
                Masukkan detail pesanan yang terjual
            </p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Produk -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" id="nama" name="nama" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Nama produk...">
                    </div>

                    <!-- Harga -->
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">Rp</span>
                            </div>
                            <input type="text" id="harga" name="harga" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="0" inputmode="numeric" pattern="[0-9.,]*">
                        </div>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <div class="relative">
                            <input type="number" id="jumlah" name="jumlah" min="1" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="0">
                            {{-- <div class="absolute right-3 top-3 flex flex-col space-y-1">
                                <button type="button" onclick="incrementQuantity()"
                                    class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <i class="fas fa-chevron-up text-xs"></i>
                                </button>
                                <button type="button" onclick="decrementQuantity()"
                                    class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Tanggal (readonly) -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="text" id="tanggal" readonly
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100"
                            value="{{ now()->format('d F Y') }}">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition text-center">
                        Kembali ke Dashboard
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i> Simpan Pesanan
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Pesanan Hari Ini -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Daftar Pesanan Hari Ini ({{ now()->format('d F Y') }})
            </h2>

            @if ($todayOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Produk</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($todayOrders as $index => $order)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $order->nama }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">Rp
                                        {{ number_format($order->harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->jumlah }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-blue-600">Rp
                                        {{ number_format($order->harga * $order->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-100 font-semibold">
                                <td colspan="4" class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    Total Keseluruhan:</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    @php
                                        $total = $todayOrders->sum(function ($order) {
                                            return $order->harga * $order->jumlah;
                                        });
                                    @endphp
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Belum ada pesanan hari ini. Silakan tambahkan pesanan baru di atas.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm">
            <p>© 2025 Business Analytics Dashboard - Input Pesanan</p>
        </div>
    </div>

    <script>
        // Format harga sebagai Rupiah
        document.getElementById('harga').addEventListener('input', function(e) {
            // Hapus semua karakter selain angka
            let value = this.value.replace(/[^0-9]/g, '');

            // Format angka dengan titik sebagai pemisah ribuan
            if (value.length > 0) {
                value = parseInt(value, 10).toLocaleString('id-ID');
            }

            this.value = value;
        });

        // Saat form disubmit, hapus format titik sebelum dikirim
        document.querySelector('form').addEventListener('submit', function(e) {
            const hargaInput = document.getElementById('harga');
            hargaInput.value = hargaInput.value.replace(/\./g, '');
        });

        // Fungsi untuk menambah jumlah
        function incrementQuantity() {
            const jumlahInput = document.getElementById('jumlah');
            let value = parseInt(jumlahInput.value) || 0;
            jumlahInput.value = value + 1;
        }

        // Fungsi untuk mengurangi jumlah
        function decrementQuantity() {
            const jumlahInput = document.getElementById('jumlah');
            let value = parseInt(jumlahInput.value) || 0;
            if (value > 1) {
                jumlahInput.value = value - 1;
            }
        }

        // Fokus ke input harga dan format saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const hargaInput = document.getElementById('harga');
            hargaInput.focus();

            // Format ulang jika ada nilai default
            if (hargaInput.value) {
                let value = hargaInput.value.replace(/[^0-9]/g, '');
                if (value.length > 0) {
                    hargaInput.value = parseInt(value, 10).toLocaleString('id-ID');
                }
            }
        });
    </script>
</body>

</html>
