<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Gian hàng - TechStore</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #F97316; /* Orange for seller */
            --primary-light: #FFEDD5;
            --success: #10B981;
            --success-bg: #D1FAE5;
            --danger: #EF4444;
            --danger-bg: #FEE2E2;
            --warning: #F59E0B;
            --warning-bg: #FEF3C7;
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
        .search-bar { display: flex; align-items: center; background: var(--gray-100); border-radius: 20px; padding: 10px 20px; width: 360px; transition: all 0.2s; border: 1px solid transparent; }
        .search-bar:focus-within { background: white; border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-light); }
        .search-bar i { color: var(--gray-400); margin-right: 12px; font-size: 14px; }
        .search-bar input { border: none; background: transparent; outline: none; width: 100%; color: var(--gray-700); font-family: 'Inter', sans-serif; font-size: 14px; }
        
        .topbar-right { display: flex; align-items: center; gap: 24px; }
        .icon-btn { position: relative; color: var(--gray-500); cursor: pointer; transition: color 0.2s; font-size: 20px; }
        .icon-btn:hover { color: var(--primary); }
        .icon-btn .badge-dot { position: absolute; top: -2px; right: -2px; background: var(--danger); width: 8px; height: 8px; border-radius: 50%; border: 2px solid white; }
        
        .user-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; padding-left: 24px; border-left: 1px solid var(--gray-200); }
        .avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .user-info { display: flex; flex-direction: column; }
        .user-name { font-size: 14px; font-weight: 600; color: var(--gray-800); }
        .user-role { font-size: 12px; color: var(--gray-500); }

        /* Dashboard Content */
        .content-scroll { flex: 1; overflow-y: auto; padding: 32px; }
        .page-header { margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; }
        .page-title { font-size: 28px; font-weight: 800; color: var(--gray-900); margin-bottom: 4px; letter-spacing: -0.5px; }
        .page-subtitle { color: var(--gray-500); font-size: 15px; }

        /* Form */
        .card { background: white; border-radius: 16px; border: 1px solid var(--gray-200); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 32px; max-width: 800px; }
        .form-group { margin-bottom: 24px; }
        .form-label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--gray-800); }
        .form-control { width: 100%; padding: 12px 16px; border: 1px solid var(--gray-300); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 15px; color: var(--gray-900); transition: border-color 0.2s, box-shadow 0.2s; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-light); }
        .btn-primary { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 15px; cursor: pointer; transition: background 0.2s; }
        .btn-primary:hover { background: #ea580c; }
        
        .alert { padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 15px; }
        .alert-success { background: var(--success-bg); color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: var(--danger-bg); color: #991b1b; border: 1px solid #fecaca; }

        .logo-preview { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gray-200); margin-top: 12px; display: block; }
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
            <a href="{{ route('manager.store') }}" class="nav-item active">
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
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Tìm kiếm đơn hàng, sản phẩm...">
            </div>
            <div class="topbar-right">
                <div class="icon-btn">
                    <i class="fa-regular fa-bell"></i>
                    <div class="badge-dot"></div>
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
                    <h1 class="page-title">Hồ sơ gian hàng</h1>
                    <p class="page-subtitle">Quản lý thông tin và hình ảnh nhận diện của gian hàng</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                @if($store)
                <form action="{{ route('manager.store.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label" for="name">Tên gian hàng</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $store->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Mô tả gian hàng</label>
                        <textarea id="description" name="description" class="form-control" rows="5" placeholder="Viết giới thiệu về gian hàng của bạn...">{{ old('description', $store->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="logo">Logo gian hàng</label>
                        <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo" class="logo-preview">
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Trạng thái</label>
                        <div style="padding: 10px 16px; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: 8px; display: inline-block;">
                            @if($store->status === 'approved')
                                <span style="color: var(--success); font-weight: 600;"><i class="fa-solid fa-circle-check"></i> Đang hoạt động</span>
                            @elseif($store->status === 'pending')
                                <span style="color: var(--warning); font-weight: 600;"><i class="fa-solid fa-clock"></i> Đang chờ duyệt</span>
                            @else
                                <span style="color: var(--danger); font-weight: 600;"><i class="fa-solid fa-circle-xmark"></i> Bị từ chối/Khóa</span>
                            @endif
                        </div>
                    </div>

                    <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--gray-200); text-align: right;">
                        <button type="submit" class="btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
                @else
                <div style="text-align: center; padding: 40px 0;">
                    <div style="font-size: 48px; color: var(--gray-300); margin-bottom: 16px;">
                        <i class="fa-solid fa-store-slash"></i>
                    </div>
                    <h3 style="margin-bottom: 8px;">Bạn chưa có gian hàng</h3>
                    <p style="color: var(--gray-500); margin-bottom: 24px;">Vui lòng đăng ký để bắt đầu bán hàng cùng TechStore.</p>
                    <button class="btn-primary">Đăng ký mở gian hàng</button>
                </div>
                @endif
            </div>

        </div>
    </main>
</body>
</html>
