<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    /**
     * Tampilkan Riwayat Pesanan Pengguna
     */
    public function index()
    {
        $user = Auth::user();

        // Tampilkan pesanan milik user yang sedang login
        $orders = Order::with('items.variant.product.images')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }
}
