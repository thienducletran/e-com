<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đánh giá - Kênh Người Bán</title>
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

        /* Reviews Card */
        .card { background: white; border-radius: 16px; border: 1px solid var(--gray-200); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .review-item { padding: 24px; border-bottom: 1px solid var(--gray-100); display: flex; gap: 20px; }
        .review-item:last-child { border-bottom: none; }
        
        .user-avatar { width: 48px; height: 48px; border-radius: 50%; background: var(--gray-200); color: var(--gray-600); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; flex-shrink: 0;}
        .review-content { flex: 1; }
        .review-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .review-author { font-weight: 600; color: var(--gray-900); font-size: 15px; }
        .review-date { color: var(--gray-500); font-size: 13px; }
        
        .stars { color: var(--warning); font-size: 14px; margin-bottom: 12px; }
        .product-ref { display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--gray-50); border-radius: 8px; border: 1px solid var(--gray-200); margin-bottom: 12px; font-size: 13px; color: var(--gray-700); text-decoration: none;}
        .product-ref:hover { border-color: var(--primary); }
        .product-ref img { width: 24px; height: 24px; object-fit: cover; border-radius: 4px; }
        
        .review-text { color: var(--gray-800); line-height: 1.5; margin-bottom: 16px; font-size: 14px; }
        
        .manager-reply { background: var(--primary-light); padding: 16px; border-radius: 8px; border-left: 4px solid var(--primary); margin-top: 12px; }
        .manager-reply-header { font-weight: 600; color: #9A3412; font-size: 13px; margin-bottom: 4px; }
        .manager-reply-text { color: #7C2D12; font-size: 14px; line-height: 1.5; }

        .reply-form { margin-top: 16px; display: none; }
        .form-control { width: 100%; padding: 10px; border: 1px solid var(--gray-300); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 14px; margin-bottom: 12px; }
        .btn { padding: 8px 16px; border-radius: 6px; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; border: none; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-outline { background: white; color: var(--gray-600); border: 1px solid var(--gray-300); }

        .alert-success { background: var(--success-bg); color: #065f46; border: 1px solid #a7f3d0; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 15px;}
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
            <a href="{{ route('manager.reviews') }}" class="nav-item active">
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
                    <h1 class="page-title">Quản lý Đánh giá</h1>
                    <p class="page-subtitle" style="color: var(--gray-500); margin-top: 4px;">Xem và phản hồi đánh giá từ khách hàng</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-success">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="card">
                @forelse($reviews as $review)
                <div class="review-item">
                    <div class="user-avatar">{{ substr($review->user->name, 0, 1) }}</div>
                    <div class="review-content">
                        <div class="review-header">
                            <span class="review-author">{{ $review->user->name }}</span>
                            <span class="review-date">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <div class="stars">
                            @for($i=1; $i<=5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fa-solid fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </div>

                        <a href="/product/{{ $review->product->slug }}" target="_blank" class="product-ref">
                            <img src="{{ $review->product->image }}" alt="">
                            <span>{{ $review->product->name }}</span>
                        </a>

                        <div class="review-text">
                            {{ $review->comment ?: 'Khách hàng không để lại bình luận.' }}
                        </div>

                        @if($review->reply)
                            <div class="manager-reply">
                                <div class="manager-reply-header">Phản hồi của bạn:</div>
                                <div class="manager-reply-text">{{ $review->reply }}</div>
                            </div>
                        @else
                            <button class="btn btn-outline" onclick="toggleReply('reply-form-{{ $review->id }}')">
                                <i class="fa-solid fa-reply"></i> Phản hồi
                            </button>
                            
                            <form action="{{ route('manager.reviews.reply', $review->id) }}" method="POST" class="reply-form" id="reply-form-{{ $review->id }}">
                                @csrf
                                <textarea name="reply" class="form-control" rows="3" placeholder="Viết phản hồi cho khách hàng..." required></textarea>
                                <div style="display: flex; gap: 8px;">
                                    <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                                    <button type="button" class="btn btn-outline" onclick="toggleReply('reply-form-{{ $review->id }}')">Hủy</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 40px; color: var(--gray-500);">
                    <div style="font-size: 48px; color: var(--gray-300); margin-bottom: 16px;"><i class="fa-regular fa-comments"></i></div>
                    <div>Chưa có đánh giá nào cho các sản phẩm của bạn.</div>
                </div>
                @endforelse
                
                @if($reviews->hasPages())
                <div style="padding: 16px 24px; border-top: 1px solid var(--gray-200);">
                    {{ $reviews->links() }}
                </div>
                @endif
            </div>

        </div>
    </main>

    <script>
        function toggleReply(id) {
            const form = document.getElementById(id);
            if (form.style.display === 'block') {
                form.style.display = 'none';
            } else {
                form.style.display = 'block';
            }
        }
    </script>
</body>
</html>
