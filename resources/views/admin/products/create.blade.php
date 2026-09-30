<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm Mới - Hoa Nghiêm Việt Phục</title>
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
            <a href="{{ route('admin.products.create') }}" class="mb-1 active text-warning fw-bold">
                <i class="bi bi-plus-circle me-2"></i> Thêm sản phẩm mới
            </a>

            <!-- QUẢN LÝ ĐƠN HÀNG -->
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

        <!-- Main Content (Form Thêm Sản Phẩm Mới) -->
        <div class="p-4 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0" style="color: #6b1110;">Thêm Sản Phẩm Mới</h2>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá sản phẩm (VNĐ)</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng tồn kho</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', 10) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Hình ảnh sản phẩm</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i> Lưu Sản Phẩm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>