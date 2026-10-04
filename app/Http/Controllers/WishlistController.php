<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Tampilkan Halaman Wishlist Pengguna (Auth Protected)
     */
    public function index()
    {
        $wishlists = Wishlist::with('product.variants', 'product.images')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle Tambah/Hapus Wishlist
     */
    public function toggle(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $user = Auth::user();

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = "Tas {$product->name} dihapus dari wishlist.";
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $status = 'added';
            $message = "Tas {$product->name} berhasil disimpan ke wishlist!";
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'total' => Wishlist::where('user_id', $user->id)->count(),
            ]);
        }

        return back()->with('success', $message);
    }
}
