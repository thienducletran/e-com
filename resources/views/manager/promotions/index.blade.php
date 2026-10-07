<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Khuyến mãi - Kênh Người Bán</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #F97316;
            --primary-light: #FFEDD5;
            --success: #10B981;
            --success-bg: #D1FAE5;
            --danger: #EF4444;
            --danger-bg: #FEE2E2;
            --warning: #F59E0B;
            --warning-bg: #FEF3C7;
            --info: #3B82F6;
            --info-bg: #DBEAFE;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --sidebar-w: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--gray-50); color: var(--gray-800); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar */
        .sidebar { width: var(--sidebar-w); background: white; border-right: 1px solid var(--gray-200); display: flex; flex-direction: column; z-index: 10; }
        .logo { padding: 20px 24px; display: flex; align-items: center; gap: 12px; font-size: 20px; font-weight: 800; color: var(--primary); border-bottom: 1px solid var(--gray-100); letter-spacing: -0.5px;}
        .logo-icon { width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        
        .nav-menu { padding: 20px 12px; flex: 1; overflow-y: auto; }
        .nav-label { font-size: 11px; text-transform: uppercase; color: var(--gray-400); font-weight: 600; letter-spacing: 0.5px; margin: 16px 0 8px 12px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: var(--gray-500); text-decoration: none; border-radius: 8px; margin-bottom: 4px; font-weight: 500; transition: all 0.2s; }
        .nav-item:hover { background: var(--gray-50); color: var(--primary); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-item i { width: 20px; text-align: center; font-size: 18px; }

        /* Main Content */
        .main-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        
        /* Topbar */
        .topbar { height: 72px; background: white; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; z-index: 5; }
        
        .topbar-right { display: flex; align-items: center; gap: 24px; margin-left: auto;}
        .icon-btn { position: relative; color: var(--gray-500); cursor: pointer; transition: color 0.2s; font-size: 20px; }
        .icon-btn:hover { color: var(--primary); }
        .user-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; padding-left: 24px; border-left: 1px solid var(--gray-200); }
        .avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .user-info { display: flex; flex-direction: column; }
        .user-name { font-size: 14px; font-weight: 600; color: var(--gray-800); }
        .user-role { font-size: 12px; color: var(--gray-500); }

        /* Dashboard Content */
        .content-scroll { flex: 1; overflow-y: auto; padding: 32px; }
        .page-header { margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; }
        .page-title { font-size: 28px; font-weight: 800; color: var(--gray-900); margin-bottom: 4px; letter-spacing: -0.5px; }

        /* Tables & Cards */
        .card { background: white; border-radius: 16px; border: 1px solid var(--gray-200); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 24px; }
        .card-header { padding: 24px; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 18px; font-weight: 700; color: var(--gray-900); }
        
        table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        th { padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--gray-200); background: var(--gray-50); }
        td { padding: 16px 24px; vertical-align: middle; border-bottom: 1px solid var(--gray-100); font-size: 14px; color: var(--gray-700); font-weight: 500;}
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--gray-50); }
        
        .badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge.active { background: var(--success-bg); color: var(--success); }
        .badge.inactive { background: var(--danger-bg); color: var(--danger); }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--gray-800); }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--gray-300); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 14px; color: var(--gray-900); }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
        
        .btn { padding: 10px 16px; border-radius: 8px; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; transition: all 0.2s; border: none; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #EA580C; }
        .btn-danger { background: var(--danger-bg); color: var(--danger); }
        .btn-danger:hover { background: var(--danger); color: white; }

        .alert-success { background: var(--success-bg); color: #065f46; border: 1px solid #a7f3d0; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 15px;}
        .alert-error { background: var(--danger-bg); color: #991b1b; border: 1px solid #fecaca; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 15px;}
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon">S</div>
            Kênh Người Bán
        </div>
        <nav class="nav-menu">
            <div class="nav-label">Quản lý hoạt động</div>
            <a href="{{ route('manager.dashboard') }}" class="nav-item">
                <i class="fa-solid fa-chart-pie"></i>
                Tổng quan
            </a>
            <a href="{{ route('manager.store') }}" class="nav-item">
                <i class="fa-solid fa-store"></i>
                Gian hàng
            </a>
            <a href="{{ route('manager.products.index') }}" class="nav-item">
                <i class="fa-solid fa-boxes-stacked"></i>
                Sản phẩm
            </a>
            <a href="{{ route('manager.orders') }}" class="nav-item">
                <i class="fa-solid fa-clipboard-list"></i>
                Đơn hàng
            </a>
            <a href="{{ route('manager.inventory') }}" class="nav-item">
                <i class="fa-solid fa-warehouse"></i>
                Kho hàng
            </a>
            
            <div class="nav-label">Tài chính & Phát triển</div>
            <a href="{{ route('manager.revenue') }}" class="nav-item">
                <i class="fa-solid fa-wallet"></i>
                Doanh thu
            </a>
            <a href="{{ route('manager.promotions') }}" class="nav-item active">
                <i class="fa-solid fa-ticket"></i>
                Khuyến mãi
            </a>
            
            <div class="nav-label">Chăm sóc khách hàng</div>
            <a href="{{ route('manager.reviews') }}" class="nav-item">
                <i class="fa-solid fa-star"></i>
                Đánh giá
            </a>
            <a href="{{ route('manager.notifications') }}" class="nav-item">
                <i class="fa-regular fa-bell"></i>
                Thông báo
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-right">
                <div class="icon-btn">
                    <i class="fa-regular fa-bell"></i>
                </div>
                <div class="user-profile">
                    <div class="user-info" style="text-align: right;">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">{{ $store ? $store->name : 'Chưa có gian hàng' }}</span>
                    </div>
                    <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="content-scroll">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Quản lý Khuyến mãi</h1>
                    <p class="page-subtitle" style="color: var(--gray-500); margin-top: 4px;">Tạo và quản lý các mã giảm giá cho khách hàng</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-success">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert-error">
                    <ul style="padding-left: 20px; margin: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Tạo Mã Mới</h2>
                </div>
                <form action="{{ route('manager.promotions.store') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Mã code</label>
                            <input type="text" name="code" class="form-control" placeholder="Ví dụ: Giam10k" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Loại giảm giá</label>
                            <select name="type" class="form-control">
                                <option value="fixed">Giảm số tiền cố định (VNĐ)</option>
                                <option value="percent">Giảm theo phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mức giảm</label>
                            <input type="number" name="value" class="form-control" placeholder="Nhập số tiền hoặc %" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Đơn tối thiểu (VNĐ)</label>
                            <input type="number" name="min_order_value" class="form-control" placeholder="Để trống nếu không yêu cầu">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Giới hạn số lượt dùng</label>
                            <input type="number" name="usage_limit" class="form-control" placeholder="Để trống nếu không giới hạn">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ngày hết hạn</label>
                            <input type="date" name="expires_at" class="form-control">
                        </div>
                    </div>
                    <div style="padding: 0 24px 24px;">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Mã Khuyến Mãi</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Danh sách Mã Khuyến mãi</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Mã Code</th>
                            <th>Mức giảm</th>
                            <th>Đơn tối thiểu</th>
                            <th>Đã dùng / Giới hạn</th>
                            <th>Hạn dùng</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td style="font-weight: 700; color: var(--primary);">{{ $coupon->code }}</td>
                            <td>
                                {{ $coupon->type == 'fixed' ? number_format($coupon->value, 0, ',', '.') . 'đ' : $coupon->value . '%' }}
                            </td>
                            <td>{{ $coupon->min_order_value ? number_format($coupon->min_order_value, 0, ',', '.') . 'đ' : 'Không có' }}</td>
                            <td>{{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                            <td>{{ $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') : 'Không có' }}</td>
                            <td>
                                @if($coupon->is_active && (!$coupon->expires_at || \Carbon\Carbon::parse($coupon->expires_at)->isFuture()))
                                    <span class="badge active">Đang hiệu lực</span>
                                @else
                                    <span class="badge inactive">Hết hạn/Đã tắt</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('manager.promotions.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa mã này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 12px;"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--gray-500); padding: 40px;">Chưa có mã khuyến mãi nào được tạo</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($coupons->hasPages())
                <div style="padding: 16px 24px; border-top: 1px solid var(--gray-200);">
                    {{ $coupons->links() }}
                </div>
                @endif
            </div>

        </div>
    </main>

</body>
</html>
