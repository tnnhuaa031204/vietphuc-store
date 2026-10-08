<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản Lý Sản Phẩm - Hoa Nghiêm Việt Phục</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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

        {{-- ================= SIDEBAR ================= --}}
        <div class="sidebar p-3" style="width: 260px;">

            <h4 class="fw-bold mb-4 text-warning text-center">
                HOA NGHIÊM ADMIN
            </h4>

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="mb-1">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>


            {{-- Quản lý đơn hàng --}}
            <div class="menu-heading">
                Quản lý đơn hàng
            </div>

            <a href="{{ route('admin.orders.index') }}" class="mb-1">
                <i class="bi bi-cart-check me-2"></i>
                Quản lý đơn hàng
            </a>


            {{-- Quản lý sản phẩm --}}
            <div class="menu-heading">
                Quản lý sản phẩm
            </div>

            <a href="{{ route('admin.products.index') }}" class="mb-1 active fw-bold">
                <i class="bi bi-box-seam me-2"></i>
                Sản phẩm
            </a>


            {{-- Quản lý Voucher --}}
            <div class="menu-heading">
                Quản lý Voucher
            </div>

            <a href="{{ route('admin.vouchers.index') }}" class="mb-1">
                <i class="bi bi-ticket-perforated me-2"></i>
                Voucher
            </a>


            {{-- Hệ thống --}}
            <div class="menu-heading">
                Hệ thống
            </div>

            <a href="{{ route('products.index') }}" class="mb-1" target="_blank">
                <i class="bi bi-house me-2"></i>
                Xem Trang chủ
            </a>


            <hr class="text-warning">


            {{-- Đăng xuất --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="btn btn-outline-warning w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Đăng xuất
                </button>
            </form>

        </div>


        {{-- ================= MAIN CONTENT ================= --}}
        <div class="p-4 flex-grow-1">

            {{-- Tiêu đề + Thêm sản phẩm --}}
            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="fw-bold mb-0" style="color: #6b1110;">
                    QUẢN LÝ SẢN PHẨM
                </h2>

                <a href="{{ route('admin.products.create') }}"
                   class="btn btn-primary">

                    <i class="bi bi-plus-lg me-1"></i>
                    Thêm Sản Phẩm Mới

                </a>

            </div>


            {{-- Thông báo --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show"
                     role="alert">

                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            {{-- ================= DANH SÁCH SẢN PHẨM ================= --}}
            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Tồn kho</th>
                                    <th>Thao tác</th>
                                </tr>

                            </thead>


                            <tbody>

                                @forelse($products as $product)

                                    <tr>

                                        {{-- Hình ảnh --}}
                                        <td>

                                            @if($product->image)

                                                <img src="{{ asset($product->image) }}"
                                                     alt="{{ $product->name }}"
                                                     width="60"
                                                     height="60"
                                                     style="object-fit: cover; border-radius: 6px;">

                                            @else

                                                <span class="text-muted">
                                                    Không có ảnh
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Tên --}}
                                        <td>
                                            <strong>
                                                {{ $product->name }}
                                            </strong>
                                        </td>


                                        {{-- Giá --}}
                                        <td>
                                            {{ number_format($product->price) }} đ
                                        </td>


                                        {{-- Tồn kho --}}
                                        <td>
                                            {{ $product->stock }}
                                        </td>


                                        {{-- Thao tác --}}
                                        <td>

                                            {{-- Sửa --}}
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                               class="btn btn-sm btn-warning">

                                                <i class="bi bi-pencil"></i>
                                                Sửa

                                            </a>


                                            {{-- Xóa --}}
                                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger">

                                                    <i class="bi bi-trash"></i>
                                                    Xóa

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5"
                                            class="text-center py-4 text-muted">

                                            Chưa có sản phẩm nào.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Phân trang --}}
                    <div class="mt-3">

                        {{ $products->links() }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>