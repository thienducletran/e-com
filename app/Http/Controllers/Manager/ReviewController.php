<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store || $store->status !== 'approved') {
            return redirect()->route('manager.dashboard')->with('error', 'Gian hàng chưa được duyệt hoặc chưa đăng ký.');
        }

        $reviews = Review::whereHas('product', function($q) use ($store) {
            $q->where('store_id', $store->id);
        })->with(['user', 'product'])->latest()->paginate(10);

        return view('manager.reviews.index', compact('reviews', 'store'));
    }

    public function reply(Request $request, Review $review)
    {
        $store = Auth::user()->store;
        
        // Verify this review is for a product of this store
        $isStoreProduct = $review->product->store_id == $store->id;

        if (!$isStoreProduct) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'reply' => $request->reply,
        ]);

        return redirect()->back()->with('success', 'Đã phản hồi đánh giá thành công!');
    }
}
