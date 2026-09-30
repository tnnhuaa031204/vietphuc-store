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
        <!-- Sidebar -->
        <div class="sidebar p-3" style="width: 260px;">
            <h4 class="fw-bold mb-4 text-warning text-center">HOA NGHIÊM ADMIN</h4>
            
            <a href="{{ route('admin.dashboard') }}" class="mb-1 active">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            <!-- MỤC QUẢN LÝ SẢN PHẨM -->
            <div class="menu-heading">Quản lý Sản phẩm</div>
            <a href="{{ route('admin.products.index') }}" class="mb-1">
                <i class="bi bi-box-seam me-2"></i> Danh sách sản phẩm
            </a>
            <a href="{{ route('admin.products.create') }}" class="mb-1 text-warning fw-bold">
                <i class="bi bi-plus-circle me-2"></i> Thêm sản phẩm mới
            </a>

            <!-- MỤC QUẢN LÝ ĐƠN HÀNG -->
            <div class="menu-heading">Quản lý Đơn hàng</div>
            <a href="{{ route('admin.orders.index') }}" class="mb-1">
                <i class="bi bi-cart-check me-2"></i> Quản lý Đơn hàng
            </a>

            <!-- HỆ THỐNG -->
            <div class="menu-heading">Hệ thống</div>
            <a href="{{ route('products.index') }}" class="mb-1" target="_blank">
                <i class="bi bi-house me-2"></i> Xem Trang chủ
            </a>

            <hr class="border-secondary">
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-warning w-100 mt-2">
                    <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="p-4 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1" style="color: #6b1110;">ADMIN DASHBOARD</h2>
                    <p class="mb-0 text-muted">Xin chào, <strong>{{ auth()->user()->name }}</strong></p>
                </div>
                <!-- NÚT TẠO MỚI SẢN PHẨM NHAU -->
                <a href="{{ route('admin.products.create') }}" class="btn btn-success shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Thêm sản phẩm mới
                </a>
            </div>

            <!-- Thông báo thành công nếu có -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Thống kê chung -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card bg-danger text-white p-3 shadow-sm border-0">
                        <h5>Tổng Đơn Hàng</h5>
                        <h2 class="fw-bold mb-0">{{ $totalOrders ?? $orders->total() ?? count($orders) }}</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                        <div class="card bg-warning text-dark p-3 shadow-sm border-0">
                            <h5>Tổng Sản Phẩm</h5>
                            <h2 class="fw-bold mb-0">{{ $totalProducts ?? 0 }}</h2>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="card bg-dark text-white p-3 shadow-sm border-0">
                        <h5>Khách Hàng</h5>
                        <h2 class="fw-bold mb-0">{{ $totalCustomers ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <!-- Bảng Quản Lý Đơn Hàng Mới Nhất -->
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
                                    <th>Mã Đơn</th>
                                    <th>Khách Hàng</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Địa Chỉ</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái Hiện Tại</th>
                                    <th>Cập Nhật Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->fullname }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td><small>{{ Str::limit($order->shipping_address, 30) }}</small></td>
                                    <td class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                    <td>
                                        @switch($order->order_status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge bg-info text-dark">Đã xác nhận</span>
                                                @break
                                            @case('shipping')
                                                <span class="badge bg-primary">Đang giao</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Hoàn thành</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $order->order_status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="order_status" class="form-select form-select-sm" style="width: 140px;">
                                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                                <option value="shipping" {{ $order->order_status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                                <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Hủy đơn</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-dark">Lưu</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Chưa có đơn hàng nào trong hệ thống.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Phân trang nếu dùng Paginate -->
            @if(method_exists($orders, 'links'))
                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>