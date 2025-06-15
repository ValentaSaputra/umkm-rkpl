<?php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesTargetController extends Controller
{
    /**
     * Display the sales target dashboard
     */
    public function index()
    {
        // Get current month target or create new if not exists
        $target = SalesTarget::getCurrentMonthTarget();

        // Calculate today's sales from orders
        $todaySales = Order::whereDate('tanggal', today())
            ->sum(DB::raw('harga * jumlah'));

        // Calculate month-to-date sales from orders
        $monthToDateSales = Order::whereMonth('tanggal', now()->month)
            ->sum(DB::raw('harga * jumlah'));

        // Update achievement with real data from orders
        $target->current_achievement = $monthToDateSales;
        $target->save();

        $data = [
            'monthlyTarget' => $target->monthly_target,
            'currentAchievement' => $target->current_achievement,
            'remaining' => $target->remaining_target,
            'achievementPercentage' => $target->achievement_percentage,
            'currentMonth' => Carbon::now()->format('F Y'),
            'target' => $target,
            'todaySales' => $todaySales,
            'monthToDateSales' => $monthToDateSales
        ];

        return view('target-penjualan', $data);
    }

    /**
     * Update the monthly target
     */
    public function updateTarget(Request $request)
    {
        $request->validate([
            'monthlyTarget' => 'required|numeric|min:0|max:999999999999.99'
        ], [
            'monthlyTarget.required' => 'Target penjualan harus diisi',
            'monthlyTarget.numeric' => 'Target penjualan harus berupa angka',
            'monthlyTarget.min' => 'Target penjualan tidak boleh kurang dari 0',
            'monthlyTarget.max' => 'Target penjualan terlalu besar'
        ]);

        try {
            $target = SalesTarget::getCurrentMonthTarget();

            // Calculate month-to-date sales from orders
            $monthToDateSales = Order::whereMonth('tanggal', now()->month)
                ->sum(DB::raw('harga * jumlah'));

            $target->update([
                'monthly_target' => $request->monthlyTarget,
                'current_achievement' => $monthToDateSales // Update with real data
            ]);

            return redirect()->back()
                ->with('success', 'Target penjualan berhasil diperbarui!')
                ->with('updatedTarget', $target);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui target: ' . $e->getMessage());
        }
    }

    /**
     * Update achievement (for manual adjustments)
     */
    public function updateAchievement(Request $request)
    {
        $request->validate([
            'achievement' => 'required|numeric|min:0',
            'action' => 'required|in:add,subtract,set'
        ]);

        try {
            $target = SalesTarget::getCurrentMonthTarget();
            $originalValue = $target->current_achievement;

            switch ($request->action) {
                case 'add':
                    $target->current_achievement += $request->achievement;
                    break;
                case 'subtract':
                    $target->current_achievement = max(0, $target->current_achievement - $request->achievement);
                    break;
                case 'set':
                    $target->current_achievement = $request->achievement;
                    break;
            }

            // Save adjustment difference
            $adjustment = $target->current_achievement - $originalValue;
            $target->manual_adjustment += $adjustment;
            $target->save();

            return redirect()->back()
                ->with('success', 'Pencapaian berhasil disesuaikan!')
                ->with('adjustment', $adjustment);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyesuaikan pencapaian: ' . $e->getMessage());
        }
    }

    /**
     * Get target data for AJAX requests
     */
    public function getTargetData()
    {
        $target = SalesTarget::getCurrentMonthTarget();

        // Get real-time sales data
        $todaySales = Order::whereDate('tanggal', today())
            ->sum(DB::raw('harga * jumlah'));

        $monthToDateSales = Order::whereMonth('tanggal', now()->month)
            ->sum(DB::raw('harga * jumlah'));

        return response()->json([
            'monthlyTarget' => $target->monthly_target,
            'currentAchievement' => $monthToDateSales,
            'remaining' => max(0, $target->monthly_target - $monthToDateSales),
            'achievementPercentage' => $target->monthly_target > 0 ?
                round(($monthToDateSales / $target->monthly_target) * 100, 2) : 0,
            'todaySales' => $todaySales,
            'formatted' => [
                'monthlyTarget' => 'Rp ' . number_format($target->monthly_target, 0, ',', '.'),
                'currentAchievement' => 'Rp ' . number_format($monthToDateSales, 0, ',', '.'),
                'remaining' => 'Rp ' . number_format(max(0, $target->monthly_target - $monthToDateSales), 0, ',', '.'),
                'todaySales' => 'Rp ' . number_format($todaySales, 0, ',', '.')
            ]
        ]);
    }

    /**
     * Show target history
     */
    public function history()
    {
        $targets = SalesTarget::with('achievementRecords')
            ->where('is_active', true)
            ->orderBy('target_year', 'desc')
            ->orderBy('target_month', 'desc')
            ->paginate(12);

        return view('sales-target.history', compact('targets'));
    }

    /**
     * Reset achievement to actual sales data
     */
    public function resetAchievement()
    {
        try {
            $target = SalesTarget::getCurrentMonthTarget();

            // Get actual sales from orders
            $actualSales = Order::whereMonth('tanggal', now()->month)
                ->sum(DB::raw('harga * jumlah'));

            $target->update([
                'current_achievement' => $actualSales,
                'manual_adjustment' => 0
            ]);

            return redirect()->back()
                ->with('success', 'Pencapaian berhasil direset ke data penjualan aktual!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mereset pencapaian: ' . $e->getMessage());
        }
    }
}
