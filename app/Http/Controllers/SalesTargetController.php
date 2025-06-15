<?php
// app/Http/Controllers/SalesTargetController.php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesTargetController extends Controller
{
    /**
     * Display the sales target dashboard
     */
    public function index()
    {
        // Dapatkan target bulan ini atau buat baru jika belum ada
        $target = SalesTarget::getCurrentMonthTarget();

        $data = [
            'monthlyTarget' => $target->monthly_target,
            'currentAchievement' => $target->current_achievement,
            'remaining' => $target->remaining_target,
            'achievementPercentage' => $target->achievement_percentage,
            'currentMonth' => Carbon::now()->format('F Y'),
            'target' => $target
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
            $target->monthly_target = $request->monthlyTarget;
            $target->save();

            return redirect()->back()->with('success', 'Target penjualan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui target: ' . $e->getMessage());
        }
    }

    /**
     * Update achievement (for sales recording)
     */
    public function updateAchievement(Request $request)
    {
        $request->validate([
            'achievement' => 'required|numeric|min:0',
            'action' => 'required|in:add,subtract,set'
        ]);

        try {
            $target = SalesTarget::getCurrentMonthTarget();

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

            $target->save();

            return redirect()->back()->with('success', 'Pencapaian berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pencapaian: ' . $e->getMessage());
        }
    }

    /**
     * Get target data for AJAX requests
     */
    public function getTargetData()
    {
        $target = SalesTarget::getCurrentMonthTarget();

        return response()->json([
            'monthlyTarget' => $target->monthly_target,
            'currentAchievement' => $target->current_achievement,
            'remaining' => $target->remaining_target,
            'achievementPercentage' => $target->achievement_percentage,
            'formatted' => [
                'monthlyTarget' => 'Rp ' . number_format($target->monthly_target, 0, ',', '.'),
                'currentAchievement' => 'Rp ' . number_format($target->current_achievement, 0, ',', '.'),
                'remaining' => 'Rp ' . number_format($target->remaining_target, 0, ',', '.')
            ]
        ]);
    }

    /**
     * Show target history
     */
    public function history()
    {
        $targets = SalesTarget::where('is_active', true)
            ->orderBy('target_year', 'desc')
            ->orderBy('target_month', 'desc')
            ->paginate(12);

        return view('sales-target.history', compact('targets'));
    }
}
