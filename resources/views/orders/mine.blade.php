<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn Hàng Của Tôi - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdfbf7; font-family: 'Montserrat', sans-serif; color: #2c2c2c; min-height: 100vh; display: flex; flex-direction: column; }
        h1, h2, h3, h4, h5 { font-family: 'Merriweather', serif; }
        .header-title { color: #6b1110; font-weight: bold; border-bottom: 2px solid #d4af37; display: inline-block; padding-bottom: 5px; }
        .table-orders { background: #fff; border: 1px solid #e0d8c3; }
        .table-orders th { background-color: #6b1110; color: #f3e5ab; border: none; }
        .price-text { color: #8b0000; font-weight: 700; }
        .btn-gold-outline { color: #6b1110; border: 1px solid #6b1110; font-weight: 600; }
        .btn-gold-outline:hover { background-color: #6b1110; color: #f3e5ab; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container py-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="header-title m-0">ĐƠN HÀNG CỦA TÔI</h3>
            <a href="{{ route('products.index') }}" class="btn btn-gold-outline">Tiếp tục mua hàng</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-orders align-middle">
                <thead>
                    <tr>
                        <th class="py-3 ps-3">Mã đơn</th>
                        <th class="py-3">Ngày đặt</th>
                        <th class="py-3">Địa chỉ nhận</th>
                        <th class="py-3">Tổng tiền</th>
                        <th class="py-3 text-center">Trạng thái</th>
                        <th class="py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-3"><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->shipping_address }}</td>
                            <td class="price-text">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                            <td class="text-center">
                                @switch($order->order_status)
                                    @case('pending')   <span class="badge bg-warning text-dark px-3 py-2">Chờ xác nhận</span> @break
                                    @case('confirmed') <span class="badge bg-info text-dark px-3 py-2">Đã xác nhận</span> @break
                                    @case('shipping')  <span class="badge bg-primary px-3 py-2">Đang giao</span> @break
                                    @case('completed') <span class="badge bg-success px-3 py-2">Hoàn thành</span> @break
                                    @case('cancelled') <span class="badge bg-danger px-3 py-2">Đã hủy</span> @break
                                    @default           <span class="badge bg-secondary px-3 py-2">{{ $order->order_status }}</span>
                                @endswitch
                            </td>
                            <td class="text-center">
                             {{-- ✅ NÚT XEM CHI TIẾT --}}
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-gold-outline">
                                 Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Bạn chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>

    <footer class="bg-dark text-light py-4 border-top border-warning mt-auto">
        <div class="container text-center">
            <p class="mb-1 fw-bold">HOA NGHIÊM VIỆT PHỤC - DI SẢN & THỜI TRANG</p>
            <p class="small text-muted mb-0">© 2026 Bảo lưu mọi quyền.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>