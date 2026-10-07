<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Doanh thu - Kênh Người Bán</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .stat-card { background: white; border-radius: 16px; padding: 24px; border: 1px solid var(--gray-200); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 24px; display: flex; align-items: center; gap: 24px; }
        .stat-icon { width: 64px; height: 64px; border-radius: 16px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 32px; }
        .stat-value { font-size: 36px; font-weight: 800; color: var(--gray-900); letter-spacing: -1px; }
        .stat-label { color: var(--gray-500); font-size: 15px; font-weight: 500; }

        /* Charts */
        .card { background: white; border-radius: 16px; border: 1px solid var(--gray-200); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 24px; margin-bottom: 24px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .card-title { font-size: 18px; font-weight: 700; color: var(--gray-900); }
        .chart-container { position: relative; height: 350px; width: 100%; }

        .filter-btn { border: 1px solid var(--gray-200); background: white; padding: 6px 12px; border-radius: 6px; font-weight: 600; color: var(--gray-600); cursor: pointer; font-family: 'Inter', sans-serif; text-decoration: none; font-size: 13px;}
        .filter-btn.active { background: var(--primary-light); color: var(--primary); border-color: var(--primary-light); }

        /* Recent Orders Table */
        table { width: 100%; border-collapse: collapse; white-space: nowrap; margin-top: 16px;}
        th { padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--gray-500); text-transform: uppercase; border-bottom: 1px solid var(--gray-200); }
        td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid var(--gray-100); font-size: 14px; color: var(--gray-700); font-weight: 500;}
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
            <a href="{{ route('manager.revenue') }}" class="nav-item active">
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
                    <h1 class="page-title">Quản lý Doanh thu</h1>
                    <p class="page-subtitle" style="color: var(--gray-500); margin-top: 4px;">Theo dõi tình hình kinh doanh của gian hàng</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                <div>
                    <div class="stat-label">Tổng doanh thu ({{ $filter == 'day' ? '7 ngày' : ($filter == 'year' ? '5 năm' : '7 tháng') }} gần nhất)</div>
                    <div class="stat-value" style="color: var(--primary);">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Biểu đồ thống kê</h2>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('manager.revenue', ['filter' => 'day']) }}" class="filter-btn {{ $filter == 'day' ? 'active' : '' }}">Theo Ngày</a>
                        <a href="{{ route('manager.revenue', ['filter' => 'month']) }}" class="filter-btn {{ $filter == 'month' ? 'active' : '' }}">Theo Tháng</a>
                        <a href="{{ route('manager.revenue', ['filter' => 'year']) }}" class="filter-btn {{ $filter == 'year' ? 'active' : '' }}">Theo Năm</a>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Lịch sử doanh thu gần đây (5 đơn mới nhất)</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $item)
                        <tr>
                            <td>#{{ $item->order_id }}</td>
                            <td>{{ $item->order->user->name }}</td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ $item->product->name }}</td>
                            <td>x{{ $item->quantity }}</td>
                            <td style="color: var(--primary); font-weight: 700;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                            <td style="color: var(--gray-500);">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--gray-500); padding: 20px;">Không có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, '#F97316');
            gradient.addColorStop(1, '#FDBA74');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: {!! json_encode($data) !!},
                        backgroundColor: gradient,
                        borderRadius: 6,
                        barThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1F2937',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('vi-VN').format(context.raw) + ' VNĐ';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F3F4F6' },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value);
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', weight: '500' }, color: '#6B7280' }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
