<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt Hàng Thành Công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #fdfbf7;">
    <div class="container text-center my-5">
        <div class="card mx-auto p-5 shadow-sm" style="max-width: 600px;">
            <h2 class="text-success fw-bold mb-3">✓ ĐẶT HÀNG THÀNH CÔNG!</h2>
            <p>Cảm ơn bạn đã lựa chọn <strong>Hoa Nghiêm Việt Phục</strong>.</p>
            <hr>
            <div class="text-start mb-4">
                <p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
                <p><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} đ</p>
                <p><strong>Hình thức thanh toán:</strong> {{ strtoupper($order->payment_method) }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark fw-bold">TIẾP TỤC MUA HÀNG</a>
        </div>
    </div>
</body>
</html>