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
        h1, h2, h3, h4, .brand-title, .header-title { font-family: 'Merriweather', serif; }
        .header-title { color: #6b1110; font-weight: bold; border-bottom: 2px solid #d4af37; display: inline-block; padding-bottom: 5px; }
        .btn-gold { background-color: #6b1110; color: #f3e5ab; font-weight: 600; border: none; }
        .btn-gold:hover { background-color: #4a0b0a; color: #fff; }
        .price-text { color: #8b0000; font-weight: 700; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container py-5 flex-grow-1">
        <h3 class="header-title mb-4">THÔNG TIN THANH TOÁN</h3>

        {{-- Khối hiển thị thông báo lỗi tổng hợp --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <h6 class="fw-bold mb-2">Vui lòng kiểm tra lại các thông tin bên dưới:</h6>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row g-4">
                {{-- Cột trái: Thông tin giao hàng --}}
                <div class="col-md-7">
                    <div class="card p-4 border-0 shadow-sm" style="border-radius: 8px;">
                        <h5 class="fw-bold mb-3" style="color: #6b1110;">1. Người Nhận Hàng</h5>

                        {{-- ✅ ĐỔI: fullname → customer_name --}}
                        <div class="mb-3">
                            <label for="customer_name" class="form-label fw-bold">Họ và tên người nhận</label>
                            <input type="text" name="customer_name" id="customer_name"
                                   class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name', auth()->user()->name ?? '') }}">
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ✅ ĐỔI: phone → customer_phone --}}
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                   class="form-control @error('customer_phone') is-invalid @enderror"
                                   value="{{ old('customer_phone', auth()->user()->phone ?? '') }}">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ✅ THÊM: customer_email (khớp migration) --}}
                        <div class="mb-3">
                            <label for="customer_email" class="form-label fw-bold">Email (Không bắt buộc)</label>
                            <input type="email" name="customer_email" id="customer_email"
                                   class="form-control @error('customer_email') is-invalid @enderror"
                                   value="{{ old('customer_email', auth()->user()->email ?? '') }}">
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ✅ ĐỔI: shipping_address giữ nguyên --}}
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label fw-bold">Địa chỉ giao hàng chi tiết</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3"
                                      class="form-control @error('shipping_address') is-invalid @enderror"
                                      placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ❌ XÓA: note (không có trong migration) --}}
                        {{-- Nếu bạn muốn giữ note, phải thêm cột note vào migration orders --}}

                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle"></i> Phương thức thanh toán sẽ được chọn ở bước tiếp theo.
                        </div>
                    </div>
                </div>

                {{-- Cột phải: Tóm tắt đơn hàng --}}
                <div class="col-md-5">
                    <div class="card p-4 border-0 shadow-sm" style="border-radius: 8px;">
                        <h5 class="fw-bold mb-3" style="color: #6b1110;">Đơn Hàng Của Bạn</h5>

                        @php $total = 0; @endphp
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($cart as $item)
                                @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="my-0 font-weight-bold">{{ $item['name'] }}</h6>
                                        <small class="text-muted">Số lượng: {{ $item['quantity'] }}</small>
                                    </div>
                                    <span class="text-muted">{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between px-0 pt-3 fw-bold border-top">
                                <span>Tổng cộng:</span>
                                <span class="price-text fs-5">{{ number_format($total, 0, ',', '.') }} đ</span>
                            </li>
                        </ul>

                        <button type="submit" class="btn btn-gold w-100 py-3 text-uppercase">Xác Nhận Đặt Hàng</button>
                    </div>
                </div>
            </div>
        </form>
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