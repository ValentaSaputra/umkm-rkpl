<?php

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create()
    {
        // Ambil pesanan hari ini
        $todayOrders = Order::whereDate('tanggal', today())->get();

        return view('orders.create', compact('todayOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
        ]);

        Order::create([
            'nama' => $validated['nama'],
            'harga' => $validated['harga'],
            'jumlah' => $validated['jumlah'],
            'tanggal' => now()->toDateString(),
        ]);

        // Redirect kembali ke form dengan data terbaru
        return redirect()->route('orders.create')->with([
            'success' => 'Pesanan berhasil disimpan!',
            'todayOrders' => Order::whereDate('tanggal', today())->get()
        ]);
    }
}
