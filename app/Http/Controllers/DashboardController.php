<?php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data target penjualan terbaru
        $salesTarget = SalesTarget::latest()->first();

        // Hitung total penjualan hari ini dari data pesanan
        $todaySales = Order::whereDate('tanggal', today())
            ->sum(DB::raw('harga * jumlah'));

        // Jika tidak ada data target, set nilai default
        if (!$salesTarget) {
            $salesTarget = new \stdClass();
            $salesTarget->monthly_target = 0;
            $salesTarget->current_achievement = 0;
        } else {
            // Update pencapaian dengan data real dari pesanan
            $salesTarget->current_achievement = $todaySales;
        }

        return view('dashboard', [
            'monthlyTarget' => $salesTarget->monthly_target,
            'currentAchievement' => $salesTarget->current_achievement,
            'remaining' => $salesTarget->monthly_target - $salesTarget->current_achievement,
            'todaySales' => $todaySales // Data penjualan hari ini
        ]);
    }

    public function updateTarget(Request $request)
    {
        $validated = $request->validate([
            'monthlyTarget' => 'required|numeric|min:0',
        ]);

        // Hitung total penjualan bulan ini
        $currentMonthSales = Order::whereMonth('tanggal', now()->month)
            ->sum(DB::raw('harga * jumlah'));

        SalesTarget::create([
            'monthly_target' => $validated['monthlyTarget'],
            'current_achievement' => $currentMonthSales, // Gunakan data real
        ]);

        return redirect()->route('dashboard')->with('success', 'Target berhasil diperbarui!');
    }

    // API untuk mendapatkan data penjualan hari ini (untuk auto-update)
    public function getTodaySales()
    {
        $todaySales = Order::whereDate('tanggal', today())
            ->sum(DB::raw('harga * jumlah'));

        return response()->json([
            'total' => $todaySales,
            'formatted' => 'Rp ' . number_format($todaySales, 0, ',', '.')
        ]);
    }
}
