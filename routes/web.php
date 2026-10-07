<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    $categorySlug = $request->query('category');
    
    $query = \App\Models\Product::query();
    
    if ($categorySlug) {
        $category = \App\Models\Category::where('slug', $categorySlug)->first();
        if ($category) {
            $query->where('category_id', $category->id);
        }
    }
    
    $products = $query->get();
    $categories = \App\Models\Category::all();
    $banners = \App\Models\Banner::where('is_active', true)->where('position', 'Hero Main')->latest()->get();

    return view('welcome', compact('products', 'categories', 'categorySlug', 'banners'));
});

Route::get('/product/{slug}', function ($slug) {
    $product = \App\Models\Product::where('slug', $slug)->firstOrFail();
    return view('product', compact('product'));
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        $users = \App\Models\User::all();
        $stores = \App\Models\Store::with('user')->get();
        
        $totalRevenue = \App\Models\Order::where('status', '!=', 'cancelled')->sum('total_price');
        $totalOrders = \App\Models\Order::count();
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        $conversionRate = $totalCustomers > 0 ? round(($totalOrders / $totalCustomers) * 100, 2) : 0;
        
        $topProducts = \App\Models\Product::withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->take(5)
            ->get();

        $revenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('m');
            $year = now()->subMonths($i)->format('Y');
            $sum = \App\Models\Order::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('status', '!=', 'cancelled')
                ->sum('total_price');
            $revenueData[] = (float) $sum;
        }

        return view('admin.dashboard', compact(
            'users', 'stores', 'totalRevenue', 'totalOrders', 'totalCustomers', 'conversionRate', 'topProducts', 'revenueData'
        ));
    })->name('admin.dashboard');

    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class)->except(['create', 'show', 'edit']);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['create', 'show', 'edit']);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::resource('stores', \App\Http\Controllers\Admin\StoreController::class)->only(['index', 'update']);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'update']);
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['create', 'show', 'edit']);
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::resource('claims', \App\Http\Controllers\Admin\ClaimController::class)->only(['index', 'update']);
});

Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/', function () {
        $store = \Illuminate\Support\Facades\Auth::user()->store;
        
        $totalRevenue = 0;
        $totalOrders = 0;
        $totalCustomers = 0;
        $topProducts = collect();
        $revenueData = [0,0,0,0,0,0,0];

        if ($store) {
            $totalOrders = \App\Models\OrderItem::whereHas('product', function($q) use ($store) {
                $q->where('store_id', $store->id);
            })->distinct('order_id')->count('order_id');

            $totalRevenue = \App\Models\OrderItem::whereHas('product', function($q) use ($store) {
                $q->where('store_id', $store->id);
            })->whereHas('order', function($q) {
                $q->where('status', '!=', 'cancelled');
            })->sum(\Illuminate\Support\Facades\DB::raw('price * quantity'));

            $totalCustomers = \App\Models\Order::whereHas('items.product', function($q) use ($store) {
                $q->where('store_id', $store->id);
            })->distinct('user_id')->count('user_id');

            $topProducts = \App\Models\Product::where('store_id', $store->id)
                ->withSum('orderItems', 'quantity')
                ->orderByDesc('order_items_sum_quantity')
                ->take(5)
                ->get();
                
            for ($i = 6; $i >= 0; $i--) {
                $month = now()->subMonths($i)->format('m');
                $year = now()->subMonths($i)->format('Y');
                $sum = \App\Models\OrderItem::whereHas('product', function($q) use ($store) {
                    $q->where('store_id', $store->id);
                })->whereHas('order', function($q) use ($month, $year) {
                    $q->whereMonth('created_at', $month)->whereYear('created_at', $year)->where('status', '!=', 'cancelled');
                })->sum(\Illuminate\Support\Facades\DB::raw('price * quantity'));
                $revenueData[6-$i] = (float) $sum;
            }
        }

        return view('manager.dashboard', compact('store', 'totalRevenue', 'totalOrders', 'totalCustomers', 'topProducts', 'revenueData'));
    })->name('dashboard');
    
    Route::resource('products', \App\Http\Controllers\Manager\ProductController::class)->except(['show']);
    
    // Placeholder routes for sidebar
    Route::get('/store', [\App\Http\Controllers\Manager\StoreController::class, 'index'])->name('store');
    Route::post('/store', [\App\Http\Controllers\Manager\StoreController::class, 'update'])->name('store.update');
    Route::get('/orders', [\App\Http\Controllers\Manager\OrderController::class, 'index'])->name('orders');
    Route::put('/orders/{order}', [\App\Http\Controllers\Manager\OrderController::class, 'update'])->name('orders.update');
    Route::get('/inventory', [\App\Http\Controllers\Manager\InventoryController::class, 'index'])->name('inventory');
    Route::put('/inventory/{product}', [\App\Http\Controllers\Manager\InventoryController::class, 'update'])->name('inventory.update');
    Route::get('/revenue', [\App\Http\Controllers\Manager\RevenueController::class, 'index'])->name('revenue');
    Route::get('/promotions', [\App\Http\Controllers\Manager\PromotionController::class, 'index'])->name('promotions');
    Route::post('/promotions', [\App\Http\Controllers\Manager\PromotionController::class, 'store'])->name('promotions.store');
    Route::delete('/promotions/{coupon}', [\App\Http\Controllers\Manager\PromotionController::class, 'destroy'])->name('promotions.destroy');
    Route::get('/reviews', [\App\Http\Controllers\Manager\ReviewController::class, 'index'])->name('reviews');
    Route::post('/reviews/{review}/reply', [\App\Http\Controllers\Manager\ReviewController::class, 'reply'])->name('reviews.reply');
    Route::get('/notifications', [\App\Http\Controllers\Manager\NotificationController::class, 'index'])->name('notifications');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\Manager\NotificationController::class, 'destroy'])->name('notifications.destroy');
});


Route::get('/test-router', function() {
    $request = \Illuminate\Http\Request::create('/admin/products/1', 'POST', ['_method' => 'DELETE']);
    $route = app('router')->getRoutes()->match($request);
    
    // Test if the current user can execute it
    $user = \App\Models\User::where('role', 'admin')->first();
    \Illuminate\Support\Facades\Auth::login($user);
    
    return response()->json([
        'uri' => $route->uri(),
        'action' => $route->getActionName(),
        'middleware' => $route->middleware(),
        'user_role' => auth()->user()->role,
    ]);
});
Route::get('/cart', function () {
    return view('cart');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Ghi đè route login mặc định của Laravel Breeze để sử dụng giao diện custom
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return redirect('/login#form-register');
})->name('register');
