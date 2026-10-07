<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $store = Auth::user()->store;
        
        if (!$store || $store->status !== 'approved') {
            return redirect()->route('manager.dashboard')->with('error', 'Gian hàng chưa được duyệt hoặc chưa đăng ký.');
        }

        $filter = $request->input('filter', 'month'); // day, month, year

        $query = OrderItem::whereHas('product', function($q) use ($store) {
            $q->where('store_id', $store->id);
        })->whereHas('order', function($q) {
            $q->where('status', '!=', 'cancelled');
        });

        $labels = [];
        $data = [];
        $totalRevenue = 0;

        if ($filter == 'day') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $sum = (clone $query)->whereHas('order', function($q) use ($date) {
                    $q->whereDate('created_at', $date->format('Y-m-d'));
                })->sum(DB::raw('price * quantity'));
                
                $labels[] = $date->format('d/m');
                $data[] = (float) $sum;
                $totalRevenue += $sum;
            }
        } elseif ($filter == 'year') {
            // Last 5 years
            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i)->format('Y');
                $sum = (clone $query)->whereHas('order', function($q) use ($year) {
                    $q->whereYear('created_at', $year);
                })->sum(DB::raw('price * quantity'));
                
                $labels[] = $year;
                $data[] = (float) $sum;
                $totalRevenue += $sum;
            }
        } else {
            // month (default): Last 7 months
            for ($i = 6; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $sum = (clone $query)->whereHas('order', function($q) use ($month) {
                    $q->whereMonth('created_at', $month->format('m'))
                      ->whereYear('created_at', $month->format('Y'));
                })->sum(DB::raw('price * quantity'));
                
                $labels[] = 'T' . $month->format('m');
                $data[] = (float) $sum;
                $totalRevenue += $sum;
            }
        }

        // Get recent orders for this store
        $recentOrders = OrderItem::whereHas('product', function($q) use ($store) {
            $q->where('store_id', $store->id);
        })->whereHas('order', function($q) {
            $q->where('status', '!=', 'cancelled');
        })->with(['order.user', 'product'])
          ->latest()
          ->take(5)
          ->get();

        return view('manager.revenue.index', compact('store', 'labels', 'data', 'totalRevenue', 'filter', 'recentOrders'));
    }
}
