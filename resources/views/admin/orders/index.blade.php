<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Đơn Hàng - Hoa Nghiêm Việt Phục</title>

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

            <a href="{{ route('admin.orders.index') }}" class="mb-1 active fw-bold">
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

            <h2 class="fw-bold mb-4" style="color: #6b1110;">
                QUẢN LÝ ĐƠN HÀNG
            </h2>


            {{-- Thông báo thành công --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>

                </div>
            @endif


            {{-- ================= DANH SÁCH ĐƠN HÀNG ================= --}}
            <div class="card shadow-sm border-0">

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
                                    <th class="text-center">Thao tác</th>
                                </tr>

                            </thead>


                            <tbody>

                                @forelse($orders as $order)

                                    <tr>

                                        {{-- Mã đơn --}}
                                        <td>
                                            <strong>
                                                #{{ $order->id }}
                                            </strong>
                                        </td>

                                        {{-- Khách hàng --}}
                                        <td>
                                            {{ $order->customer_name }}
                                        </td>

                                        {{-- Số điện thoại --}}
                                        <td>
                                            {{ $order->customer_phone }}
                                        </td>

                                        {{-- Tổng tiền --}}
                                        <td class="text-danger fw-bold">
                                            {{ number_format($order->total_amount, 0, ',', '.') }} đ
                                        </td>

                                        {{-- Trạng thái --}}
                                        <td>

                                            @switch($order->order_status)

                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">
                                                        Chờ xác nhận
                                                    </span>
                                                    @break

                                                @case('confirmed')
                                                    <span class="badge bg-info text-dark">
                                                        Đã xác nhận
                                                    </span>
                                                    @break

                                                @case('shipping')
                                                    <span class="badge bg-primary">
                                                        Đang giao
                                                    </span>
                                                    @break

                                                @case('completed')
                                                    <span class="badge bg-success">
                                                        Hoàn thành
                                                    </span>
                                                    @break

                                                @case('cancelled')
                                                    <span class="badge bg-danger">
                                                        Đã hủy
                                                    </span>
                                                    @break

                                                @default
                                                    <span class="badge bg-secondary">
                                                        {{ $order->order_status }}
                                                    </span>

                                            @endswitch

                                        </td>

                                        {{-- Ngày đặt --}}
                                        <td>
                                            {{ $order->created_at?->format('d/m/Y H:i') }}
                                        </td>

                                        {{-- Thao tác --}}
                                        <td class="text-center">

                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                               class="btn btn-sm btn-primary">

                                                <i class="bi bi-eye"></i>
                                                Xem

                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="7"
                                            class="text-center py-4 text-muted">

                                            Chưa có đơn hàng nào.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- ================= PHÂN TRANG ================= --}}
            <div class="mt-4 d-flex justify-content-center">

                {{ $orders->links() }}

            </div>

        </div>

    </div>


    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>