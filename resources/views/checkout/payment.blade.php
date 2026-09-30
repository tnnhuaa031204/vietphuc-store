<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán QR - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #fdfbf7;">
    <div class="container text-center my-5">
        <div class="card mx-auto p-4 shadow-sm" style="max-width: 500px;">
            <h3 class="fw-bold text-danger">MÃ QR THANH TOÁN</h3>
            <p>Mã đơn hàng: <strong>#{{ $order->id }}</strong></p>
            <p>Số tiền: <strong class="text-danger fs-4">{{ number_format($order->total_amount, 0, ',', '.') }} đ</strong></p>
            
            <div class="my-3">
                <img src="{{ asset('images/qr-payment.png') }}" class="img-fluid border p-2" style="max-width: 250px;" alt="QR Code">
            </div>

            <p class="text-muted small">Vui lòng mở ứng dụng ngân hàng và quét mã để thanh toán.</p>
            <a href="{{ route('checkout.success', $order) }}" class="btn btn-success w-100 fw-bold">TÔI ĐÃ THANH TOÁN</a>
        </div>
    </div>
</body>
</html>