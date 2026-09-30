<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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

        {{-- ✅ FORM VOUCHER (ĐẶT NGOÀI FORM CHECKOUT) --}}
        <form action="{{ route('checkout.applyVoucher') }}" method="POST" id="voucher-form" class="d-none">
            @csrf
            <input type="hidden" name="voucher_code" id="voucher-code-hidden">
        </form>

        {{-- ✅ FORM REMOVE VOUCHER (ĐẶT NGOÀI) --}}
        <form action="{{ route('checkout.removeVoucher') }}" method="POST" id="remove-voucher-form" class="d-none">
            @csrf
        </form>

        {{-- FORM CHECKOUT CHÍNH --}}
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row g-4">
                {{-- Cột trái: Thông tin giao hàng --}}
                <div class="col-md-7">
                    <div class="card p-4 border-0 shadow-sm" style="border-radius: 8px;">
                        <h5 class="fw-bold mb-3" style="color: #6b1110;">1. Người Nhận Hàng</h5>

                        <div class="mb-3">
                            <label for="customer_name" class="form-label fw-bold">Họ và tên người nhận</label>
                            <input type="text" name="customer_name" id="customer_name"
                                   class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name', auth()->user()->name ?? '') }}">
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_phone" class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                   class="form-control @error('customer_phone') is-invalid @enderror"
                                   value="{{ old('customer_phone', auth()->user()->phone ?? '') }}">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_email" class="form-label fw-bold">Email (Không bắt buộc)</label>
                            <input type="email" name="customer_email" id="customer_email"
                                   class="form-control @error('customer_email') is-invalid @enderror"
                                   value="{{ old('customer_email', auth()->user()->email ?? '') }}">
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label fw-bold">Địa chỉ giao hàng chi tiết</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3"
                                      class="form-control @error('shipping_address') is-invalid @enderror"
                                      placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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
                        </ul>

                        {{-- ✅ KHỐI VOUCHER --}}
                        <div class="border rounded p-3 mb-3 bg-light">
                            <h6 class="fw-bold mb-2" style="color: #6b1110;">
                                <i class="bi bi-ticket-perforated"></i> Mã giảm giá
                            </h6>

                            @if(session('voucher_success'))
                                <div class="alert alert-success small py-2 mb-2">
                                    {{ session('voucher_success') }}
                                </div>
                            @endif
                            @if(session('voucher_error'))
                                <div class="alert alert-danger small py-2 mb-2">
                                    {{ session('voucher_error') }}
                                </div>
                            @endif

                            @if(session('voucher'))
                                {{-- Đã áp dụng voucher --}}
                                <div class="alert alert-success small mb-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ session('voucher')['code'] }}</strong>
                                        <div class="text-muted" style="font-size: 0.8rem;">
                                            Giảm {{ number_format(session('voucher')['discount'], 0, ',', '.') }} đ
                                        </div>
                                    </div>
                                    {{-- ✅ Nút Hủy dùng form="remove-voucher-form" --}}
                                    <button type="submit" form="remove-voucher-form" class="btn btn-sm btn-outline-danger">Hủy</button>
                                </div>
                            @else
                                {{-- Chưa áp dụng voucher --}}
                                <div class="input-group">
                                    <input type="text" id="voucher-code-input" class="form-control"
                                           placeholder="Nhập mã voucher..." style="text-transform: uppercase;">
                                    {{-- ✅ Nút Áp dụng dùng form="voucher-form" + JS copy value --}}
                                    <button type="submit" form="voucher-form" class="btn btn-gold"
                                            onclick="document.getElementById('voucher-code-hidden').value = document.getElementById('voucher-code-input').value;">
                                        Áp dụng
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Tóm tắt tiền --}}
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($total, 0, ',', '.') }} đ</span>
                            </li>

                            @if(session('voucher'))
                                <li class="list-group-item d-flex justify-content-between px-0 text-success">
                                    <span>Giảm giá ({{ session('voucher')['code'] }}):</span>
                                    <span>-{{ number_format(session('voucher')['discount'], 0, ',', '.') }} đ</span>
                                </li>
                                @php $total = $total - session('voucher')['discount']; @endphp
                            @endif

                            <li class="list-group-item d-flex justify-content-between px-0 pt-3 fw-bold border-top">
                                <span>Tổng cộng:</span>
                                <span class="price-text fs-5">{{ number_format($total, 0, ',', '.') }} đ</span>
                            </li>
                        </ul>

                        <button type="submit" class="btn btn-gold w-100 py-3 text-uppercase mt-3">Xác Nhận Đặt Hàng</button>
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