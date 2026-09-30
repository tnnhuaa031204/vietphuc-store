<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng #{{ $order->id }} - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdfbf7; font-family: 'Montserrat', sans-serif; color: #2c2c2c; min-height: 100vh; display: flex; flex-direction: column; }
        h1, h2, h3, h4, h5 { font-family: 'Merriweather', serif; }
        .header-title { color: #6b1110; font-weight: bold; border-bottom: 2px solid #d4af37; display: inline-block; padding-bottom: 5px; }
        .price-text { color: #8b0000; font-weight: 700; }
        .btn-gold-outline { color: #6b1110; border: 1px solid #6b1110; font-weight: 600; }
        .btn-gold-outline:hover { background-color: #6b1110; color: #f3e5ab; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container py-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="header-title m-0">CHI TIẾT ĐƠN HÀNG #{{ $order->id }}</h3>
            <a href="{{ route('orders.mine') }}" class="btn btn-gold-outline">
                ← Quay lại danh sách
            </a>
        </div>

        <div class="row g-4">
            {{-- Cột trái: Thông tin giao hàng --}}
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold" style="color: #6b1110;">
                        Thông Tin Giao Hàng
                    </div>
                    <div class="card-body">
                        <p><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                        @if($order->customer_email)
                            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                        @endif
                        <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $order->created_at?->format('d/m/Y H:i:s') }}</p>

                        <hr>

                        <p><strong>Phương thức thanh toán:</strong>
                            <span class="badge bg-secondary">
                                {{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Thanh toán QR' }}
                            </span>
                        </p>
                        <p><strong>Trạng thái thanh toán:</strong>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                            @else
                                <span class="badge bg-danger">Chưa thanh toán</span>
                            @endif
                        </p>
                        <p><strong>Trạng thái đơn hàng:</strong>
                            @switch($order->order_status)
                                @case('pending')   <span class="badge bg-warning text-dark">Chờ xác nhận</span> @break
                                @case('confirmed') <span class="badge bg-info text-dark">Đã xác nhận</span> @break
                                @case('shipping')  <span class="badge bg-primary">Đang giao</span> @break
                                @case('completed') <span class="badge bg-success">Hoàn thành</span> @break
                                @case('cancelled') <span class="badge bg-danger">Đã hủy</span> @break
                                @default           <span class="badge bg-secondary">{{ $order->order_status }}</span>
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>

            {{-- Cột phải: Danh sách sản phẩm --}}
            <div class="col-md-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold" style="color: #6b1110;">
                        Sản Phẩm Đã Đặt
                    </div>
                    <div class="card-body p-0">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>SL</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->details as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product_name }}</strong>
                                        </td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($item->subtotal, 0, ',', '.') }} đ
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">
                                            Không có sản phẩm trong đơn hàng.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="3" class="fw-bold text-end">Tổng cộng:</td>
                                    <td class="text-end price-text fs-5">
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

    <footer class="bg-dark text-light py-4 border-top border-warning mt-auto">
        <div class="container text-center">
            <p class="mb-1 fw-bold">HOA NGHIÊM VIỆT PHỤC - DI SẢN & THỜI TRANG</p>
            <p class="small text-muted mb-0">© 2026 Bảo lưu mọi quyền.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>