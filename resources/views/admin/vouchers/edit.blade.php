<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Voucher - Hoa Nghiêm Việt Phục</title>

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

            <a href="{{ route('admin.products.index') }}" class="mb-1">
                <i class="bi bi-box-seam me-2"></i>
                Sản phẩm
            </a>

            {{-- Quản lý Voucher --}}
            <div class="menu-heading">
                Quản lý Voucher
            </div>

            <a href="{{ route('admin.vouchers.index') }}" class="mb-1 active">
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

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h2 class="fw-bold mb-1" style="color: #6b1110;">
                        SỬA VOUCHER
                    </h2>

                    <p class="mb-0 text-muted">
                        Cập nhật thông tin voucher
                        <strong>{{ $voucher->code }}</strong>
                    </p>
                </div>

                <a href="{{ route('admin.vouchers.index') }}"
                   class="btn btn-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i>
                    Quay lại
                </a>

            </div>


            {{-- Thông báo lỗi --}}
            @if($errors->any())
                <div class="alert alert-danger">

                    <strong>Có lỗi xảy ra:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif


            {{-- ================= FORM VOUCHER ================= --}}
            <div class="card shadow-sm border-0">

                <div class="card-header bg-dark text-white fw-bold">
                    Thông tin Voucher
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('admin.vouchers.update', $voucher->id) }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Mã voucher --}}
                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Mã voucher *
                                </label>

                                <input
                                    type="text"
                                    name="code"
                                    class="form-control @error('code') is-invalid @enderror"
                                    value="{{ old('code', $voucher->code) }}"
                                    required
                                >

                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Tên voucher --}}
                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Tên voucher *
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $voucher->name) }}"
                                    required
                                >

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Mô tả --}}
                            <div class="col-12">

                                <label class="form-label fw-bold">
                                    Mô tả
                                </label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="3"
                                >{{ old('description', $voucher->description) }}</textarea>

                            </div>


                            {{-- Loại giảm giá --}}
                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Loại giảm giá *
                                </label>

                                <select name="type" class="form-select" required>

                                    <option value="percent"
                                        {{ old('type', $voucher->type) == 'percent' ? 'selected' : '' }}>
                                        Phần trăm (%)
                                    </option>

                                    <option value="fixed"
                                        {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>
                                        Số tiền cố định (VNĐ)
                                    </option>

                                </select>

                            </div>


                            {{-- Giá trị --}}
                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Giá trị *
                                </label>

                                <input
                                    type="number"
                                    name="value"
                                    class="form-control @error('value') is-invalid @enderror"
                                    value="{{ old('value', $voucher->value) }}"
                                    min="0"
                                    step="0.01"
                                    required
                                >

                                @error('value')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Đơn hàng tối thiểu --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">
                                    Đơn hàng tối thiểu *
                                </label>

                                <input
                                    type="number"
                                    name="min_order_amount"
                                    class="form-control"
                                    value="{{ old('min_order_amount', $voucher->min_order_amount) }}"
                                    min="0"
                                    required
                                >

                            </div>


                            {{-- Giảm tối đa --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">
                                    Giảm tối đa
                                </label>

                                <input
                                    type="number"
                                    name="max_discount"
                                    class="form-control"
                                    value="{{ old('max_discount', $voucher->max_discount) }}"
                                    min="0"
                                >

                            </div>


                            {{-- Số lượng --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">
                                    Số lượng *
                                </label>

                                <input
                                    type="number"
                                    name="quantity"
                                    class="form-control"
                                    value="{{ old('quantity', $voucher->quantity) }}"
                                    min="0"
                                    required
                                >

                            </div>


                            {{-- Đã sử dụng --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">
                                    Đã sử dụng
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    value="{{ $voucher->used_count }}"
                                    disabled
                                >

                            </div>


                            {{-- Ngày bắt đầu --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">
                                    Ngày bắt đầu *
                                </label>

                                <input
                                    type="datetime-local"
                                    name="start_date"
                                    class="form-control"
                                    value="{{ old('start_date', optional($voucher->start_date)->format('Y-m-d\TH:i')) }}"
                                    required
                                >

                            </div>


                            {{-- Ngày kết thúc --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">
                                    Ngày kết thúc *
                                </label>

                                <input
                                    type="datetime-local"
                                    name="end_date"
                                    class="form-control"
                                    value="{{ old('end_date', optional($voucher->end_date)->format('Y-m-d\TH:i')) }}"
                                    required
                                >

                            </div>


                            {{-- Trạng thái --}}
                            <div class="col-12">

                                <div class="form-check">

                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        class="form-check-input"
                                        id="is_active"
                                        {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}
                                    >

                                    <label class="form-check-label" for="is_active">
                                        Kích hoạt voucher
                                    </label>

                                </div>

                            </div>

                        </div>


                        {{-- Buttons --}}
                        <div class="mt-4">

                            <button type="submit"
                                    class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i>
                                Cập nhật
                            </button>

                            <a href="{{ route('admin.vouchers.index') }}"
                               class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i>
                                Hủy
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>