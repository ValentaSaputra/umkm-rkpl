<?php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data target penjualan terbaru
        $salesTarget = SalesTarget::latest()->first(); // Mengambil target terakhir yang ditambahkan

        // Jika tidak ada data, set nilai default
        if (!$salesTarget) {
            $salesTarget = new \stdClass();
            $salesTarget->monthly_target = 0;
            $salesTarget->current_achievement = 0;
        }

        return view('dashboard', [
            'monthlyTarget' => $salesTarget->monthly_target,
            'currentAchievement' => $salesTarget->current_achievement,
            'remaining' => $salesTarget->monthly_target - $salesTarget->current_achievement, // Sisa target
        ]);
    }

    // Fungsi untuk update target penjualan
    public function updateTarget(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'monthlyTarget' => 'required|numeric',
        ]);

        // Menyimpan atau update target penjualan
        SalesTarget::create([
            'monthly_target' => $validated['monthlyTarget'],
            'current_achievement' => 0, // Reset pencapaian ke 0 setiap kali target diperbarui
        ]);

        return redirect()->route('dashboard')->with('success', 'Target berhasil diperbarui!');
    }
}
