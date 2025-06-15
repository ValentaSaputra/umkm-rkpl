<!-- resources/views/order-history/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Order History
            </h2>
            <a href="{{ route('orders.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Tambah Pesanan
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('order-history') }}" method="GET"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Date Range -->
                        <div>
                            <x-label for="start_date" value="Dari Tanggal" />
                            <x-input id="start_date" name="start_date" type="date" value="{{ $startDate }}"
                                class="w-full mt-1" />
                        </div>
                        <div>
                            <x-label for="end_date" value="Hingga Tanggal" />
                            <x-input id="end_date" name="end_date" type="date" value="{{ $endDate }}"
                                class="w-full mt-1" />
                        </div>

                        <!-- Search -->
                        <div>
                            <x-label for="search" value="Cari Produk" />
                            <x-input id="search" name="search" type="text" value="{{ $search }}"
                                placeholder="Nama produk..." class="w-full mt-1" />
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-end space-x-2">
                            <x-button>
                                Filter
                            </x-button>
                            <a href="{{ route('order-history') }}"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                                Reset
                            </a>
                            <a href="{{ route('order-history.export', request()->query()) }}"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                Export
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-800">Total Pesanan</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $orders->total() }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-green-800">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-green-600">Rp
                                {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-sm text-purple-800">Periode</p>
                            <p class="text-lg font-bold text-purple-600">
                                {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produk</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($orders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($order->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $order->nama }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp {{ number_format($order->harga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->jumlah }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            Rp {{ number_format($order->harga * $order->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data pesanan pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($orders->isNotEmpty())
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                            Total Pendapatan:
                                        </td>
                                        <td class="px-6 py-3 text-sm font-bold text-green-600">
                                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($orders->hasPages())
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
