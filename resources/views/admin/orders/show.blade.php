<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng #{{ $order->id }} - Hoa Nghiêm Việt Phục</title>
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
        <!-- Sidebar Menu -->
        <div class="sidebar p-3" style="width: 260px;">
            <h4 class="fw-bold mb-4 text-warning text-center">HOA NGHIÊM ADMIN</h4>
            
            <a href="{{ route('admin.dashboard') }}" class="mb-1">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            <!-- QUẢN LÝ SẢN PHẨM -->
            <div class="menu-heading">Quản lý Sản phẩm</div>
            <a href="{{ route('admin.products.index') }}" class="mb-1">
                <i class="bi bi-box-seam me-2"></i> Danh sách sản phẩm
            </a>
            <a href="{{ route('admin.products.create') }}" class="mb-1">
                <i class="bi bi-plus-circle me-2"></i> Thêm sản phẩm mới
            </a>

            <!-- QUẢN LÝ ĐƠN HÀNG -->
            <div class="menu-heading">Quản lý Đơn hàng</div>
            <a href="{{ route('admin.orders.index') }}" class="mb-1 active fw-bold">
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
                <h2 class="fw-bold mb-0" style="color: #6b1110;">CHI TIẾT ĐƠN HÀNG #{{ $order->id }}</h2>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Thông tin người nhận -->
                <div class="col-md-5 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white fw-bold" style="color: #6b1110;">
                            Thông Tin Khách Hàng
                        </div>
                        <div class="card-body">
                            <p><strong>Tên khách hàng:</strong> {{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $order->phone ?? 'N/A' }}</p>
                            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->address ?? 'N/A' }}</p>
                            <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có ghi chú' }}</p>
                            <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i:s') : '' }}</p>

                            <hr>

                            <!-- Cập nhật trạng thái đơn hàng -->
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label class="form-label fw-bold">Cập nhật trạng thái đơn hàng:</label>
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ ($order->status ?? '') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="processing" {{ ($order->status ?? '') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="completed" {{ ($order->status ?? '') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                        <option value="cancelled" {{ ($order->status ?? '') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Danh sách sản phẩm trong đơn -->
                <div class="col-md-7 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-bold" style="color: #6b1110;">
                            Sản Phẩm Đã Đặt
                        </div>
                        <div class="card-body p-0">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($order->items) && count($order->items) > 0)
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td><strong>{{ $item->product->name ?? $item->product_name ?? 'Sản phẩm' }}</strong></td>
                                                <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">Không có thông tin chi tiết sản phẩm.</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="3" class="fw-bold text-end">Tổng tiền:</td>
                                        <td class="text-end text-danger fw-bold fs-5">
                                            {{ number_format($order->total_price ?? $order->total ?? 0, 0, ',', '.') }} đ
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>