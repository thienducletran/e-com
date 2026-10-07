<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store || $store->status !== 'approved') {
            return redirect()->route('manager.dashboard')->with('error', 'Gian hàng chưa được duyệt hoặc chưa đăng ký.');
        }

        // Lấy danh sách sản phẩm và số lượng
        $products = Product::where('store_id', $store->id)
            ->orderBy('stock', 'asc') // Sắp xếp theo số lượng từ thấp đến cao để thấy SP sắp hết
            ->paginate(15);

        return view('manager.inventory.index', compact('products', 'store'));
    }

    public function update(Request $request, Product $product)
    {
        $store = Auth::user()->store;
        
        if ($product->store_id != $store->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'stock' => $request->stock,
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật số lượng tồn kho thành công!');
    }
}
