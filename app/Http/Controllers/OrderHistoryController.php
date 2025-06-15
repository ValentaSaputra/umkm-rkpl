<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Default filter - last 30 days
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = Order::query()
            ->select('*', DB::raw('harga * jumlah as total'))
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc');

        // Apply search filter if exists
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $orders = $query->paginate(20);
        $totalRevenue = $query->sum(DB::raw('harga * jumlah'));

        return view('order-history.index', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'search' => $request->search ?? ''
        ]);
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $orders = Order::query()
            ->select('*', DB::raw('harga * jumlah as total'))
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total');

        $fileName = 'order-history-' . $startDate . '-to-' . $endDate . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($orders, $totalRevenue) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Order History Report']);
            fputcsv($file, ['']);

            // Column headers
            fputcsv($file, [
                'Tanggal',
                'Nama Produk',
                'Harga Satuan',
                'Jumlah',
                'Total',
            ]);

            // Order data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->tanggal,
                    $order->nama,
                    number_format($order->harga, 0, ',', '.'),
                    $order->jumlah,
                    number_format($order->total, 0, ',', '.'),
                ]);
            }

            // Footer with total
            fputcsv($file, ['']);
            fputcsv($file, ['Total Pendapatan', '', '', '', number_format($totalRevenue, 0, ',', '.')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
