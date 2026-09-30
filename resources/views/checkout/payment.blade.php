<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdfbf7; font-family: 'Montserrat', sans-serif; color: #2c2c2c; min-height: 100vh; display: flex; flex-direction: column; }
        h1, h2, h3, h4 { font-family: 'Merriweather', serif; }
        .header-title { color: #6b1110; font-weight: bold; border-bottom: 2px solid #d4af37; display: inline-block; padding-bottom: 5px; }
        .btn-gold { background-color: #6b1110; color: #f3e5ab; font-weight: 600; border: none; }
        .btn-gold:hover { background-color: #4a0b0a; color: #fff; }
        .price-text { color: #8b0000; font-weight: 700; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container py-5 flex-grow-1">
        <h3 class="header-title mb-4">PHƯƠNG THỨC THANH TOÁN</h3>

        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            {{-- Cột trái: Form chọn phương thức thanh toán --}}
            <div class="col-md-7">
                <div class="card p-4 border-0 shadow-sm" style="border-radius: 8px;">
                    <h5 class="fw-bold mb-3" style="color: #6b1110;">Chọn phương thức thanh toán</h5>

                    <form action="{{ route('checkout.payment.process', $order->id) }}" method="POST" id="payment-form">
                        @csrf

                        {{-- COD --}}
                        <div class="form-check mb-3 p-3 border rounded" style="border-color: #d4af37 !important;">
                            <input class="form-check-input" type="radio" name="payment_method"
                                   id="method-cod" value="cod"
                                   {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="method-cod">
                                Thanh toán khi nhận hàng (COD)
                            </label>
                            <p class="small text-muted mb-0 ms-4">Bạn sẽ thanh toán bằng tiền mặt khi nhận hàng.</p>
                        </div>

                        {{-- QR --}}
                        <div class="form-check mb-3 p-3 border rounded" style="border-color: #d4af37 !important;">
                            <input class="form-check-input" type="radio" name="payment_method"
                                   id="method-qr" value="qr"
                                   {{ old('payment_method') === 'qr' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="method-qr">
                                Thanh toán bằng QR
                            </label>
                            <p class="small text-muted mb-0 ms-4">Quét mã QR bằng ứng dụng ngân hàng để thanh toán.</p>
                        </div>

                        {{-- Khối QR (ẩn/hiện theo lựa chọn) --}}
                        <div id="qr-box" class="text-center my-3 p-3 bg-white border rounded"
                             style="display: {{ old('payment_method') === 'qr' ? 'block' : 'none' }};">
                            <p class="fw-bold mb-2">Mã QR thanh toán</p>
                            <img src="{{ asset('images/qr-payment.png') }}"
                                 class="img-fluid border p-2" style="max-width: 250px;" alt="QR Code">
                            <p class="text-muted small mb-0 mt-2">
                                Nội dung chuyển khoản: <strong>DH{{ $order->id }}</strong>
                            </p>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 py-3 text-uppercase mt-3">
                            Xác Nhận Đặt Hàng
                        </button>
                    </form>
                </div>
            </div>

            {{-- Cột phải: Tóm tắt đơn hàng --}}
            <div class="col-md-5">
                <div class="card p-4 border-0 shadow-sm" style="border-radius: 8px;">
                    <h5 class="fw-bold mb-3" style="color: #6b1110;">Đơn Hàng #{{ $order->id }}</h5>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Người nhận:</span>
                            <strong>{{ $order->customer_name }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Số điện thoại:</span>
                            <strong>{{ $order->customer_phone }}</strong>
                        </li>
                        <li class="list-group-item px-0">
                            <span>Địa chỉ:</span>
                            <div class="text-muted small">{{ $order->shipping_address }}</div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0 pt-3 fw-bold border-top">
                            <span>Tổng cộng:</span>
                            <span class="price-text fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} đ</span>
                        </li>
                    </ul>
                </div>
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
    <script>
        // Hiện/ẩn khối QR theo lựa chọn phương thức
        document.querySelectorAll('input[name="payment_method"]').forEach(function (el) {
            el.addEventListener('change', function () {
                document.getElementById('qr-box').style.display =
                    this.value === 'qr' ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>