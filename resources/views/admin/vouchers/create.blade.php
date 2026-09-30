<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Voucher - Admin</title>
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
            <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-outline-warning w-100"><i class="bi bi-box-arrow-right me-1"></i> Đăng xuất</button></form>
        </div>

        <div class="p-4 flex-grow-1">
            <h2 class="fw-bold mb-4" style="color: #6b1110;">THÊM VOUCHER</h2>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.vouchers.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mã voucher *</label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                       value="{{ old('code') }}" style="text-transform: uppercase;" required>
                                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tên voucher *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Mô tả</label>
                                <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loại giảm giá *</label>
                                <select name="type" class="form-select" required>
                                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá trị *</label>
                                <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                                       value="{{ old('value') }}" min="0" step="0.01" required>
                                @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Đơn tối thiểu *</label>
                                <input type="number" name="min_order_amount" class="form-control"
                                       value="{{ old('min_order_amount', 0) }}" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Giảm tối đa</label>
                                <input type="number" name="max_discount" class="form-control"
                                       value="{{ old('max_discount') }}" min="0">
                                <small class="text-muted">Chỉ áp dụng cho loại %</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Số lượng *</label>
                                <input type="number" name="quantity" class="form-control"
                                       value="{{ old('quantity', 0) }}" min="0" required>
                                <small class="text-muted">0 = không giới hạn</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày bắt đầu *</label>
                                <input type="datetime-local" name="start_date" class="form-control"
                                       value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày kết thúc *</label>
                                <input type="datetime-local" name="end_date" class="form-control"
                                       value="{{ old('end_date', now()->addMonth()->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Kích hoạt voucher</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-success">Tạo voucher</button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>