<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Hàng Thành Công - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdfbf7; font-family: 'Montserrat', sans-serif; color: #2c2c2c; min-height: 100vh; display: flex; flex-direction: column; }
        h1, h2, h3, h4 { font-family: 'Merriweather', serif; }
        .header-title { color: #6b1110; font-weight: bold; border-bottom: 2px solid #d4af37; display: inline-block; padding-bottom: 5px; }
        .btn-gold { background-color: #6b1110; color: #f3e5ab; font-weight: 600; border: none; }
        .btn-gold:hover { background-color: #4a0b0a; color: #fff; }
        .btn-outline-gold { border: 1px solid #6b1110; color: #6b1110; font-weight: 600; background: transparent; }
        .btn-outline-gold:hover { background-color: #6b1110; color: #f3e5ab; }
        .price-text { color: #8b0000; font-weight: 700; }
        .success-icon { font-size: 3rem; color: #198754; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container py-5 flex-grow-1">
        <div class="card mx-auto p-5 border-0 shadow-sm" style="max-width: 650px; border-radius: 8px;">

            {{-- Icon + tiêu đề --}}
            <div class="text-center mb-4">
                <div class="success-icon">✓</div>
                <h2 class="fw-bold text-success mt-2">ĐẶT HÀNG THÀNH CÔNG!</h2>
                <p class="text-muted mb-0">Cảm ơn bạn đã lựa chọn <strong>Hoa Nghiêm Việt Phục</strong>.</p>
            </div>

            <hr>

            {{-- Thông tin đơn hàng --}}
            <div class="mb-4">
                <h5 class="fw-bold mb-3" style="color: #6b1110;">Thông tin đơn hàng</h5>

                <div class="row mb-2">
                    <div class="col-5 text-muted">Mã đơn hàng:</div>
                    <div class="col-7 fw-bold">#{{ $order->id }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 text-muted">Người nhận:</div>
                    <div class="col-7">{{ $order->customer_name }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 text-muted">Số điện thoại:</div>
                    <div class="col-7">{{ $order->customer_phone }}</div>
                </div>

                @if($order->customer_email)
                <div class="row mb-2">
                    <div class="col-5 text-muted">Email:</div>
                    <div class="col-7">{{ $order->customer_email }}</div>
                </div>
                @endif

                <div class="row mb-2">
                    <div class="col-5 text-muted">Địa chỉ giao hàng:</div>
                    <div class="col-7">{{ $order->shipping_address }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 text-muted">Hình thức thanh toán:</div>
                    <div class="col-7">
                        <span class="badge bg-secondary">
                            {{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Thanh toán QR' }}
                        </span>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 text-muted">Trạng thái thanh toán:</div>
                    <div class="col-7">
                        @if($order->payment_status === 'paid')
                            <span class="badge bg-success">Đã thanh toán</span>
                        @elseif($order->payment_status === 'pending')
                            <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                        @else
                            <span class="badge bg-danger">Chưa thanh toán</span>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-5 fw-bold">Tổng tiền:</div>
                    <div class="col-7 price-text fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} đ</div>
                </div>
            </div>

            {{-- Nút hành động --}}
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="{{ route('orders.mine') }}" class="btn btn-gold px-4 py-2">
                    Xem đơn hàng của tôi
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-gold px-4 py-2">
                    Tiếp tục mua hàng
                </a>
            </div>

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