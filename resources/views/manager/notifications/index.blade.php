<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo - Kênh Người Bán</title>
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

        /* Notifications */
        .card { background: white; border-radius: 16px; border: 1px solid var(--gray-200); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        
        .notification-item { padding: 20px 24px; border-bottom: 1px solid var(--gray-100); display: flex; gap: 16px; transition: background 0.2s; }
        .notification-item:hover { background: var(--gray-50); }
        .notification-item:last-child { border-bottom: none; }
        .notification-item.unread { background: var(--primary-light); border-left: 4px solid var(--primary); padding-left: 20px; }
        
        .notif-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .notif-icon.order { background: var(--info-bg); color: var(--info); }
        .notif-icon.review { background: var(--warning-bg); color: var(--warning); }
        .notif-icon.stock { background: var(--danger-bg); color: var(--danger); }
        .notif-icon.promotion { background: var(--success-bg); color: var(--success); }
        .notif-icon.system { background: var(--gray-200); color: var(--gray-700); }

        .notif-content { flex: 1; }
        .notif-title { font-weight: 600; color: var(--gray-900); font-size: 15px; margin-bottom: 4px; }
        .notif-message { color: var(--gray-600); font-size: 14px; line-height: 1.5; margin-bottom: 6px; }
        .notif-time { color: var(--gray-400); font-size: 12px; }
        
        .btn-delete { color: var(--gray-400); background: transparent; border: none; cursor: pointer; font-size: 16px; transition: color 0.2s; padding: 4px; }
        .btn-delete:hover { color: var(--danger); }
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
            <a href="{{ route('manager.promotions') }}" class="nav-item">
                <i class="fa-solid fa-ticket"></i>
                Khuyến mãi
            </a>
            
            <div class="nav-label">Chăm sóc khách hàng</div>
            <a href="{{ route('manager.reviews') }}" class="nav-item">
                <i class="fa-solid fa-star"></i>
                Đánh giá
            </a>
            <a href="{{ route('manager.notifications') }}" class="nav-item active">
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
                    <h1 class="page-title">Thông báo</h1>
                    <p class="page-subtitle" style="color: var(--gray-500); margin-top: 4px;">Tất cả cập nhật về đơn hàng, đánh giá và hoạt động của gian hàng</p>
                </div>
            </div>

            <div class="card">
                @forelse($notifications as $notification)
                <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}">
                    <div class="notif-icon {{ $notification->type }}">
                        @if($notification->type == 'order') <i class="fa-solid fa-box"></i>
                        @elseif($notification->type == 'review') <i class="fa-solid fa-star"></i>
                        @elseif($notification->type == 'stock') <i class="fa-solid fa-triangle-exclamation"></i>
                        @elseif($notification->type == 'promotion') <i class="fa-solid fa-tag"></i>
                        @else <i class="fa-solid fa-bell"></i>
                        @endif
                    </div>
                    <div class="notif-content">
                        <div class="notif-title">{{ $notification->title }}</div>
                        <div class="notif-message">{{ $notification->message }}</div>
                        <div class="notif-time"><i class="fa-regular fa-clock" style="margin-right: 4px;"></i> {{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    <div>
                        <form action="{{ route('manager.notifications.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Xóa thông báo"><i class="fa-solid fa-xmark"></i></button>
                        </form>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 40px; color: var(--gray-500);">
                    <div style="font-size: 48px; color: var(--gray-300); margin-bottom: 16px;"><i class="fa-regular fa-bell-slash"></i></div>
                    <div>Bạn không có thông báo nào.</div>
                </div>
                @endforelse

                @if($notifications->hasPages())
                <div style="padding: 16px 24px; border-top: 1px solid var(--gray-200);">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>

        </div>
    </main>

</body>
</html>
