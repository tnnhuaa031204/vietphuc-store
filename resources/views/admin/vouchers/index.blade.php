<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Voucher - Admin</title>
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
            <a href="{{ route('admin.dashboard') }}" class="mb-1"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <div class="menu-heading">Quản lý đơn hàng</div>
            <a href="{{ route('admin.orders.index') }}" class="mb-1"><i class="bi bi-cart-check me-2"></i> Đơn hàng</a>
            <div class="menu-heading">Quản lý sản phẩm</div>
            <a href="{{ route('admin.products.index') }}" class="mb-1"><i class="bi bi-box-seam me-2"></i> Sản phẩm</a>
            <div class="menu-heading">Quản lý Voucher</div>
            <a href="{{ route('admin.vouchers.index') }}" class="mb-1 active fw-bold"><i class="bi bi-ticket-perforated me-2"></i> Voucher</a>
            <hr class="text-warning">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-warning w-100"><i class="bi bi-box-arrow-right me-1"></i> Đăng xuất</button>
            </form>
        </div>

        {{-- Main --}}
        <div class="p-4 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0" style="color: #6b1110;">QUẢN LÝ VOUCHER</h2>
                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i> Thêm Voucher
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã</th>
                                    <th>Tên</th>
                                    <th>Giảm</th>
                                    <th>Đơn tối thiểu</th>
                                    <th>Đã dùng</th>
                                    <th>Hiệu lực</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vouchers as $voucher)
                                    <tr>
                                        <td><strong>{{ $voucher->code }}</strong></td>
                                        <td>{{ $voucher->name }}</td>
                                        <td>
                                            @if($voucher->type === 'percent')
                                                {{ $voucher->value }}%
                                                @if($voucher->max_discount)
                                                    <small class="text-muted">(tối đa {{ number_format($voucher->max_discount, 0, ',', '.') }}đ)</small>
                                                @endif
                                            @else
                                                {{ number_format($voucher->value, 0, ',', '.') }}đ
                                            @endif
                                        </td>
                                        <td>{{ number_format($voucher->min_order_amount, 0, ',', '.') }}đ</td>
                                        <td>{{ $voucher->used_count }}/{{ $voucher->quantity }}</td>
                                        <td>
                                            <small>
                                                {{ $voucher->start_date->format('d/m/Y') }}<br>
                                                → {{ $voucher->end_date->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($voucher->is_active)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Tắt</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa voucher này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">Chưa có voucher nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>