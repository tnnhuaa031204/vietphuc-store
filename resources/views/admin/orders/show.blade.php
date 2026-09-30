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
        {{-- Sidebar --}}
        <div class="sidebar p-3" style="width: 260px;">
            <h4 class="fw-bold mb-4 text-warning text-center">HOA NGHIÊM ADMIN</h4>
            <a href="{{ route('admin.dashboard') }}" class="mb-1">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <div class="menu-heading">Quản lý đơn hàng</div>
            <a href="{{ route('admin.orders.index') }}" class="mb-1 active fw-bold">
                <i class="bi bi-cart-check me-2"></i> Quản lý đơn hàng
            </a>
            <div class="menu-heading">Quản lý sản phẩm</div>
            <a href="{{ route('admin.products.index') }}" class="mb-1">
                <i class="bi bi-box-seam me-2"></i> Sản phẩm
            </a>
            {{-- ✅ THÊM: Quản lý Voucher --}}
            <div class="menu-heading">Quản lý Voucher</div>
            <a href="{{ route('admin.vouchers.index') }}" class="mb-1">
                <i class="bi bi-ticket-perforated me-2"></i> Voucher
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
                <h2 class="fw-bold mb-0" style="color: #6b1110;">CHI TIẾT ĐƠN HÀNG #{{ $order->id }}</h2>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                {{-- Thông tin khách hàng --}}
                <div class="col-md-5 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-bold" style="color: #6b1110;">
                            Thông Tin Khách Hàng
                        </div>
                        <div class="card-body">
                            <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                            @if($order->customer_email)
                                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                            @endif
                            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                            <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at?->format('d/m/Y H:i:s') }}</p>
                            <p><strong>Phương thức TT:</strong>
                                <span class="badge bg-info text-dark">{{ strtoupper($order->payment_method) }}</span>
                            </p>
                            <p><strong>Trạng thái TT:</strong>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                                @else
                                    <span class="badge bg-danger">Chưa thanh toán</span>
                                @endif
                            </p>

                            {{-- ✅ HIỂN THỊ VOUCHER NẾU CÓ --}}
                            @if($order->voucher_code)
                                <p><strong>Voucher:</strong>
                                    <span class="badge bg-success">{{ $order->voucher_code }}</span>
                                    <span class="text-success fw-bold">-{{ number_format($order->discount_amount, 0, ',', '.') }} đ</span>
                                </p>
                            @endif

                            <hr>

                            {{-- Cập nhật trạng thái đơn hàng --}}
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label class="form-label fw-bold">Cập nhật trạng thái đơn hàng:</label>
                                <div class="input-group mb-3">
                                    <select name="order_status" class="form-select">
                                        <option value="pending"    {{ $order->order_status == 'pending'    ? 'selected' : '' }}>Chờ xác nhận</option>
                                        <option value="confirmed"  {{ $order->order_status == 'confirmed'  ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="shipping"   {{ $order->order_status == 'shipping'   ? 'selected' : '' }}>Đang giao</option>
                                        <option value="completed"  {{ $order->order_status == 'completed'  ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="cancelled"  {{ $order->order_status == 'cancelled'  ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </form>

                            {{-- ✅ CẬP NHẬT TRẠNG THÁI THANH TOÁN (CHO CẢ COD VÀ QR) --}}
                            <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label class="form-label fw-bold">Cập nhật trạng thái thanh toán:</label>
                                <div class="input-group">
                                    <select name="payment_status" class="form-select">
                                        <option value="unpaid"  {{ $order->payment_status == 'unpaid'  ? 'selected' : '' }}>Chưa thanh toán</option>
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                        <option value="paid"    {{ $order->payment_status == 'paid'    ? 'selected' : '' }}>Đã thanh toán</option>
                                    </select>
                                    <button type="submit" class="btn btn-success">Lưu</button>
                                </div>
                                <small class="text-muted">
                                    @if($order->payment_method === 'cod')
                                        Áp dụng cho đơn hàng COD — xác nhận sau khi giao hàng thành công.
                                    @else
                                        Áp dụng cho đơn hàng QR — xác nhận sau khi kiểm tra tài khoản ngân hàng.
                                    @endif
                                </small>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Danh sách sản phẩm --}}
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
                                    @forelse($order->details as $item)
                                        <tr>
                                            <td><strong>{{ $item->product_name }}</strong></td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($item->subtotal, 0, ',', '.') }} đ
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">
                                                Không có thông tin chi tiết sản phẩm.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    @if($order->discount_amount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end text-muted">Tạm tính:</td>
                                            <td class="text-end">
                                                {{ number_format($order->total_amount + $order->discount_amount, 0, ',', '.') }} đ
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end text-success">
                                                Giảm giá ({{ $order->voucher_code }}):
                                            </td>
                                            <td class="text-end text-success fw-bold">
                                                -{{ number_format($order->discount_amount, 0, ',', '.') }} đ
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="table-light">
                                        <td colspan="3" class="fw-bold text-end">Tổng tiền:</td>
                                        <td class="text-end text-danger fw-bold fs-5">
                                            {{ number_format($order->total_amount, 0, ',', '.') }} đ
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