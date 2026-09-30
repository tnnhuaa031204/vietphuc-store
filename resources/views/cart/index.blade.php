<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #fdfbf7; color: #2c2c2c; }
        h1, h2, h3, .brand-title { font-family: 'Merriweather', serif; }
        .btn-gold { background-color: #6b1110; color: #f3e5ab; border: 1px solid #d4af37; font-weight: 600; }
        .btn-gold:hover { background-color: #8b0000; color: #fff; }
        .table-cart { background: #fff; border: 1px solid #e0d8c3; }
        .table-cart th { background-color: #6b1110; color: #f3e5ab; border: none; }
        .price-text { color: #8b0000; font-weight: 700; }
        
        /* Custom nút và ô số lượng */
        .btn-qty { 
            width: 32px; 
            height: 32px; 
            padding: 0; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            border: 1px solid #d4af37; 
            background: #6b1110; 
            color: #f3e5ab; 
            font-weight: bold; 
            border-radius: 4px; 
            cursor: pointer;
        }
        .btn-qty:hover { background: #8b0000; color: #fff; }
        
        .input-qty { 
            width: 50px; 
            height: 32px;
            text-align: center; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            font-weight: 600; 
            background-color: #fff; 
        }

        /* Ẩn mũi tên mặc định của input type=number */
        .input-qty::-webkit-outer-spin-button,
        .input-qty::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .input-qty[type=number] {
            -moz-appearance: textfield;
        }

        .product-link { color: #6b1110; text-decoration: none; transition: color 0.2s; }
        .product-link:hover { color: #8b0000; text-decoration: underline; }
    </style>
</head>
<body>

    <!-- Nhúng Navbar dùng chung -->
    @include('partials.navbar')

    <!-- Cart Content -->
    <div class="container my-5">
        <h2 class="fw-bold text-center mb-4" style="color: #6b1110;">GIỎ HÀNG CỦA BẠN</h2>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="table-responsive">
                <table class="table table-cart align-middle shadow-sm">
                    <thead>
                        <tr>
                            <th class="py-3 ps-3">Sản phẩm</th>
                            <th class="py-3">Đơn giá</th>
                            <th class="py-3 text-center" style="width: 170px;">Số lượng</th>
                            <th class="py-3">Thành tiền</th>
                            <th class="py-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr id="cart-item-{{ $id }}">
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('products.show', $item['slug'] ?? $id) }}">
                                            <img src="{{ asset($item['image'] ?? 'images/products/default.jpg') }}" width="60" height="60" style="object-fit: cover;" class="rounded border">
                                        </a>
                                        <a href="{{ route('products.show', $item['slug'] ?? $id) }}" class="product-link fw-bold">
                                            {{ $item['name'] }}
                                        </a>
                                    </div>
                                </td>
                                <td class="price-text">{{ number_format($item['price'], 0, ',', '.') }} đ</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <button type="button" class="btn-qty" onclick="changeQty({{ $id }}, -1)">-</button>
                                        
                                        <!-- Ô nhập số lượng trực tiếp -->
                                        <input type="number" 
                                               id="qty-{{ $id }}" 
                                               class="input-qty" 
                                               value="{{ $item['quantity'] }}" 
                                               min="1"
                                               oninput="handleInputQty({{ $id }}, this)">

                                        <button type="button" class="btn-qty" onclick="changeQty({{ $id }}, 1)">+</button>
                                    </div>
                                </td>
                                <td class="price-text" id="subtotal-{{ $id }}">{{ number_format($subtotal, 0, ',', '.') }} đ</td>
                                <td class="text-center">
                                    <!-- Cập nhật theo Cách 1: Truyền trực tiếp $id vào route và dùng @method('DELETE') -->
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total & Checkout -->
            <div class="row align-items-center mt-4">
                <div class="col-md-6">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">← Tiếp tục xem sản phẩm</a>
                </div>
                <div class="col-md-6 text-end">
                    <h4 class="fw-bold mb-3">Tổng tiền: <span class="price-text fs-3" id="cart-total">{{ number_format($total, 0, ',', '.') }} đ</span></h4>
                    <a href="{{ route('checkout.index') }}" class="btn btn-gold btn-lg px-4 shadow-sm">TIẾN HÀNH THANH TOÁN</a>
                </div>
            </div>
        @else
            <div class="alert alert-warning text-center py-4">
                Giỏ hàng của bạn đang trống. <a href="{{ route('products.index') }}" class="fw-bold text-dark">Trở về cửa hàng</a>
            </div>
        @endif
    </div>

    <!-- JS Tự động cập nhật số lượng và tính lại tiền -->
    <script>
        let timeoutId = null;

        // Xử lý khi nhấn nút + hoặc -
        function changeQty(productId, change) {
            let qtyInput = document.getElementById('qty-' + productId);
            let currentQty = parseInt(qtyInput.value) || 1;
            let newQty = currentQty + change;

            if (newQty >= 1) {
                qtyInput.value = newQty;
                sendUpdateCart(productId, newQty);
            }
        }

        // Xử lý khi tự gõ phím vào ô nhập số
        function handleInputQty(productId, input) {
            // Lọc bỏ ký tự không phải số
            input.value = input.value.replace(/[^0-9]/g, '');

            let val = parseInt(input.value);

            // Nếu người dùng xóa sạch hoặc gõ số < 1
            if (isNaN(val) || val < 1) {
                val = 1;
            }

            // Đợi 400ms sau khi người dùng ngừng gõ mới gửi request cập nhật (chống spam server)
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                input.value = val;
                sendUpdateCart(productId, val);
            }, 400);
        }

        // Hàm AJAX gửi request cập nhật session và giao diện
        function sendUpdateCart(productId, quantity) {
            fetch("{{ route('cart.update') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật thành tiền sản phẩm đó
                    document.getElementById('subtotal-' + productId).innerText = data.subtotal + ' đ';
                    // Cập nhật tổng tiền toàn bộ giỏ hàng
                    document.getElementById('cart-total').innerText = data.total + ' đ';
                }
            })
            .catch(error => console.error('Lỗi cập nhật giỏ hàng:', error));
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>