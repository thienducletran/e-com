<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\StoreNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store || $store->status !== 'approved') {
            return redirect()->route('manager.dashboard')->with('error', 'Gian hàng chưa được duyệt hoặc chưa đăng ký.');
        }

        $notifications = StoreNotification::where('store_id', $store->id)
            ->latest()
            ->paginate(15);

        // Mark as read
        StoreNotification::where('store_id', $store->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('manager.notifications.index', compact('notifications', 'store'));
    }

    public function destroy(StoreNotification $notification)
    {
        $store = Auth::user()->store;
        if ($notification->store_id != $store->id) {
            abort(403, 'Unauthorized action.');
        }

        $notification->delete();
        
        return redirect()->back()->with('success', 'Đã xóa thông báo!');
    }
}
