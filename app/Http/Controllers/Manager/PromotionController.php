<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store || $store->status !== 'approved') {
            return redirect()->route('manager.dashboard')->with('error', 'Gian hàng chưa được duyệt hoặc chưa đăng ký.');
        }

        $coupons = Coupon::where('store_id', $store->id)->latest()->paginate(10);
        return view('manager.promotions.index', compact('coupons', 'store'));
    }

    public function store(Request $request)
    {
        $store = Auth::user()->store;
        
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'min_order_value' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create([
            'store_id' => $store->id,
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'usage_limit' => $request->usage_limit,
            'min_order_value' => $request->min_order_value,
            'expires_at' => $request->expires_at,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Tạo mã khuyến mãi thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $store = Auth::user()->store;
        if ($coupon->store_id != $store->id) {
            abort(403, 'Unauthorized action.');
        }

        $coupon->delete();
        
        return redirect()->back()->with('success', 'Đã xóa mã khuyến mãi!');
    }
}
