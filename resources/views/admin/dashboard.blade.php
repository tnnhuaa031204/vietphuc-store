<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #6b1110; color: #fff; }
        .sidebar a { color: #f3e5ab; text-decoration: none; padding: 10px 15px; display: block; border-radius: 4px; }
        .sidebar a:hover, .sidebar a.active { background-color: #8b0000; color: #fff; }
        .sidebar .menu-heading { color: #d1a153; font-size: 0.75rem; text-transform: uppercase; font-weight: bold; margin-top: 15px; margin-bottom: 5px; padding-left: 15px; }
    </style>
</head>
<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="sidebar p-3" style="width: 260px;">
            <h4 class="fw-bold mb-4 text-warning text-center">HOA NGHIÊM ADMIN</h4>
            <a href="{{ route('admin.dashboard') }}" class="mb-1 active">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <div class="menu-heading">Quản lý đơn hàng</div>
            <a href="{{ route('admin.orders.index') }}" class="mb-1">
                <i class="bi bi-cart-check me-2"></i> Quản lý đơn hàng
            </a>
            <div class="menu-heading">Quản lý sản phẩm</div>
            <a href="{{ route('admin.products.index') }}" class="mb-1">
                <i class="bi bi-box-seam me-2"></i> Sản phẩm
            </a>
            <hr class="text-warning">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-warning w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                </button>
            </form>
        </div>

        {{-- Main Content --}}
        <div class="p-4 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1" style="color: #6b1110;">ADMIN DASHBOARD</h2>
                    <p class="mb-0 text-muted">Xin chào, <strong>{{ auth()->user()->name }}</strong></p>
                </div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-success shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Thêm sản phẩm mới
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Thống kê --}}
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card bg-danger text-white p-3 shadow-sm border-0">
                        <h5>Tổng Đơn Hàng</h5>
                        <h2 class="fw-bold mb-0">{{ $totalOrders }}</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                        <div class="card bg-warning text-dark p-3 shadow-sm border-0">
                            <h5>Tổng Sản Phẩm</h5>
                            <h2 class="fw-bold mb-0">{{ $totalProducts }}</h2>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="card bg-dark text-white p-3 shadow-sm border-0">
                        <h5>Khách Hàng</h5>
                        <h2 class="fw-bold mb-0">{{ $totalCustomers }}</h2>
                    </div>
                </div>
            </div>

            {{-- Bảng đơn hàng mới nhất --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Danh Sách Đơn Hàng Mới Nhất</span>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-light">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->customer_phone }}</td>
                                        <td class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                        <td>
                                            @switch($order->order_status)
                                                @case('pending')   <span class="badge bg-warning text-dark">Chờ xác nhận</span> @break
                                                @case('confirmed') <span class="badge bg-info text-dark">Đã xác nhận</span> @break
                                                @case('shipping')  <span class="badge bg-primary">Đang giao</span> @break
                                                @case('completed') <span class="badge bg-success">Hoàn thành</span> @break
                                                @case('cancelled') <span class="badge bg-danger">Đã hủy</span> @break
                                                @default           <span class="badge bg-secondary">{{ $order->order_status }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Chưa có đơn hàng nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>