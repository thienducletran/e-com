<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store || $store->status !== 'approved') {
            return redirect()->route('manager.dashboard')->with('error', 'Gian hàng chưa được duyệt hoặc chưa đăng ký.');
        }

        // Get orders that contain products from this store
        $orders = Order::whereHas('items.product', function ($query) use ($store) {
            $query->where('store_id', $store->id);
        })->with(['user', 'items' => function($q) use ($store) {
            $q->whereHas('product', function($q2) use ($store) {
                $q2->where('store_id', $store->id);
            })->with('product');
        }])->latest()->paginate(10);

        return view('manager.orders.index', compact('orders', 'store'));
    }

    public function update(Request $request, Order $order)
    {
        $store = Auth::user()->store;
        
        // Verify this order belongs to the store
        $hasStoreProduct = $order->items()->whereHas('product', function ($query) use ($store) {
            $query->where('store_id', $store->id);
        })->exists();

        if (!$hasStoreProduct) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công!');
    }
}
