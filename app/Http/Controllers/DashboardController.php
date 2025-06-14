<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // Target Penjualan dan Target Keuntungan (dapat diambil dari database atau diinput)
        $targetPenjualan = 500;  // contoh nilai target penjualan
        $targetKeuntungan = 10000;  // contoh nilai target keuntungan
        $keuntunganSaatIni = 7500; // contoh nilai keuntungan saat ini

        // Kirim data ke view
        return view('dashboard', compact('targetPenjualan', 'targetKeuntungan', 'keuntunganSaatIni'));
    }
}
