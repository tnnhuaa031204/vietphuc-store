<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Đơn Hàng - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            background-color: #fdfbf7; 
            font-family: 'Montserrat', sans-serif; 
            color: #2c2c2c;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        h1, h2, h3, h4, h5, .brand-title, .header-title { font-family: 'Merriweather', serif; }
        .header-title { color: #6b1110; font-weight: bold; border-bottom: 2px solid #d4af37; display: inline-block; padding-bottom: 5px; }
        .table-orders { background: #fff; border: 1px solid #e0d8c3; }
        .table-orders th { background-color: #6b1110; color: #f3e5ab; border: none; }
        .price-text { color: #8b0000; font-weight: 700; }
        .btn-gold-outline { color: #6b1110; border: 1px solid #6b1110; font-weight: 600; }
        .btn-gold-outline:hover { background-color: #6b1110; color: #f3e5ab; }
    </style>
</head>
<body>

    <!-- Nhúng Navbar dùng chung -->
    @include('partials.navbar')

    <!-- Order History Content -->
    <div class="container py-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="header-title m-0">ĐƠN HÀNG CỦA TÔI</h3>
            <a href="{{ route('products.index') }}" class="btn btn-gold-outline">← Tiếp Tục Mua Sắm</a>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 8px;">
            <div class="table-responsive">
                <table class="table table-hover table-orders align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 ps-3">Mã Đơn</th>
                            <th class="py-3">Ngày Đặt</th>
                            <th class="py-3">Địa Chỉ Nhận</th>
                            <th class="py-3">Tổng Tiền</th>
                            <th class="py-3 text-center">Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-3"><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->shipping_address }}</td>
                            <td class="price-text">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                            <td class="text-center">
                                @switch($order->order_status)
                                    @case('pending') <span class="badge bg-warning text-dark px-3 py-2">Chờ xử lý</span> @break
                                    @case('confirmed') <span class="badge bg-info text-dark px-3 py-2">Đã xác nhận</span> @break
                                    @case('shipping') <span class="badge bg-primary px-3 py-2">Đang giao</span> @break
                                    @case('completed') <span class="badge bg-success px-3 py-2">Hoàn thành</span> @break
                                    @case('cancelled') <span class="badge bg-danger px-3 py-2">Đã hủy</span> @break
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Bạn chưa có đơn hàng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 border-top border-warning mt-auto">
        <div class="container text-center">
            <p class="mb-1 fw-bold">HOA NGHIÊM VIỆT PHỤC - DI SẢN & THỜI TRANG</p>
            <p class="small text-muted mb-0">© 2026 Bảo lưu mọi quyền.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>