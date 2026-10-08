<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Chỉnh Sửa Sản Phẩm - Hoa Nghiêm Việt Phục</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #6b1110;
            color: #fff;
        }

        .sidebar a {
            color: #f3e5ab;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 4px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #8b0000;
            color: #fff;
        }

        .sidebar .menu-heading {
            color: #d1a153;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            padding-left: 15px;
        }
    </style>
</head>

<body>

<div class="d-flex">

    <!-- ================= SIDEBAR ================= -->
    <div class="sidebar p-3" style="width: 260px;">

        <h4 class="fw-bold mb-4 text-warning text-center">
            HOA NGHIÊM ADMIN
        </h4>

        <!-- DASHBOARD -->
        <a href="{{ route('admin.dashboard') }}" class="mb-1">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard
        </a>


        <!-- QUẢN LÝ ĐƠN HÀNG -->
        <div class="menu-heading">
            Quản lý đơn hàng
        </div>

        <a href="{{ route('admin.orders.index') }}" class="mb-1">
            <i class="bi bi-cart-check me-2"></i>
            Quản lý đơn hàng
        </a>


        <!-- QUẢN LÝ SẢN PHẨM -->
        <div class="menu-heading">
            Quản lý sản phẩm
        </div>

        <a href="{{ route('admin.products.index') }}" class="mb-1 active">
            <i class="bi bi-box-seam me-2"></i>
            Sản phẩm
        </a>


        <!-- QUẢN LÝ VOUCHER -->
        <div class="menu-heading">
            Quản lý Voucher
        </div>

        <a href="{{ route('admin.vouchers.index') }}" class="mb-1">
            <i class="bi bi-ticket-perforated me-2"></i>
            Voucher
        </a>


        <!-- HỆ THỐNG -->
        <div class="menu-heading">
            Hệ thống
        </div>

        <a href="{{ route('products.index') }}"
           class="mb-1"
           target="_blank">

            <i class="bi bi-house me-2"></i>
            Xem Trang chủ

        </a>


        <hr class="border-secondary">


        <!-- ĐĂNG XUẤT -->
        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button type="submit"
                    class="btn btn-outline-warning w-100">

                <i class="bi bi-box-arrow-right me-1"></i>
                Đăng xuất

            </button>

        </form>

    </div>


    <!-- ================= MAIN CONTENT ================= -->
    <div class="p-4 flex-grow-1">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="fw-bold mb-0" style="color: #6b1110;">
                Chỉnh Sửa Sản Phẩm
            </h2>

            <a href="{{ route('admin.products.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Quay lại

            </a>

        </div>


        <!-- HIỂN THỊ LỖI -->
        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>

            </div>

        @endif


        <!-- FORM -->
        <div class="card shadow-sm border-0">

            <div class="card-body p-4">

                <form action="{{ route('admin.products.update', $product->id) }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    @method('PUT')


                    <!-- TÊN SẢN PHẨM -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Tên sản phẩm
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $product->name) }}"
                               required>

                    </div>


                    <!-- DANH MỤC -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Danh mục
                        </label>

                        <select name="category_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Chọn danh mục --
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- GIÁ + TỒN KHO -->
                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-bold">
                                Giá sản phẩm (VNĐ)
                            </label>

                            <input type="number"
                                   name="price"
                                   class="form-control"
                                   value="{{ old('price', $product->price) }}"
                                   required>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-bold">
                                Số lượng tồn kho
                            </label>

                            <input type="number"
                                   name="stock"
                                   class="form-control"
                                   value="{{ old('stock', $product->stock) }}"
                                   required>

                        </div>

                    </div>


                    <!-- HÌNH ẢNH -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Hình ảnh hiện tại
                        </label>

                        <br>

                        @if($product->image)

                            <img src="{{ asset($product->image) }}"
                                 alt="{{ $product->name }}"
                                 width="100"
                                 class="img-thumbnail mb-2">

                        @else

                            <p class="text-muted small">
                                Chưa có ảnh
                            </p>

                        @endif


                        <input type="file"
                               name="image"
                               class="form-control"
                               accept="image/*">

                    </div>


                    <!-- MÔ TẢ -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Mô tả sản phẩm
                        </label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="5">{{ old('description', $product->description) }}</textarea>

                    </div>


                    <!-- NÚT CẬP NHẬT -->
                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Cập Nhật Sản Phẩm

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>