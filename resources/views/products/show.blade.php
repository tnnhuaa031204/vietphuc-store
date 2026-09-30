<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fdfbf7;
            color: #2c2c2c;
        }
        h1, h2, h3, .brand-title {
            font-family: 'Merriweather', serif;
        }
        .product-img {
            max-height: 550px;
            object-fit: cover;
            border: 1px solid #e0d8c3;
            border-radius: 4px;
        }
        .badge-cat {
            background-color: #8b0000;
            color: #f3e5ab;
            border: 1px solid #d4af37;
        }
        .price-text {
            color: #8b0000;
            font-size: 2rem;
            font-weight: 700;
        }
        .btn-gold {
            background-color: #6b1110;
            color: #f3e5ab;
            border: 1px solid #d4af37;
            padding: 12px 24px;
            font-weight: 600;
        }
        .btn-gold:hover {
            background-color: #8b0000;
            color: #fff;
        }
        /* Custom nút tăng giảm số lượng */
        .btn-qty-detail {
            width: 42px;
            height: 42px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d4af37;
            background-color: #6b1110;
            color: #f3e5ab;
            font-weight: bold;
            font-size: 1.2rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-qty-detail:hover {
            background-color: #8b0000;
            color: #fff;
        }
        .input-qty-detail {
            width: 60px;
            height: 42px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-weight: 600;
            font-size: 1.1rem;
            background-color: #fff;
        }
        /* Ẩn mũi tên mặc định của input type=number */
        .input-qty-detail::-webkit-outer-spin-button,
        .input-qty-detail::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .input-qty-detail[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>

    <!-- Nhúng Navbar dùng chung -->
    @include('partials.navbar')

    <!-- Product Detail Content -->
    <div class="container my-5">
        <div class="row g-5 align-items-center">
            <!-- Ảnh sản phẩm -->
            <div class="col-md-6 text-center">
                <img src="{{ asset($product->image ?? 'images/products/default.jpg') }}" 
                     class="img-fluid w-100 product-img shadow-sm" 
                     alt="{{ $product->name }}">
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                <span class="badge badge-cat px-3 py-2 mb-3 fs-6">{{ $product->category->name }}</span>
                <h1 class="fw-bold mb-3" style="color: #6b1110;">{{ $product->name }}</h1>
                <div class="price-text mb-4">{{ number_format($product->price, 0, ',', '.') }} đ</div>

                <div class="border-top border-bottom py-3 mb-4">
                    <p class="mb-2"><strong>Chất liệu:</strong> {{ $product->material ?? 'Đang cập nhật' }}</p>
                    <p class="mb-2"><strong>Tình trạng:</strong> 
                        @if($product->stock > 0)
                            <span class="text-success fw-bold">Còn hàng ({{ $product->stock }} sản phẩm)</span>
                        @else
                            <span class="text-danger fw-bold">Hết hàng</span>
                        @endif
                    </p>
                </div>

                <p class="text-muted leading-relaxed mb-4">{{ $product->description }}</p>

                <!-- Form Thêm vào giỏ hàng -->
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-flex align-items-center gap-3">
                        @csrf
                        <!-- Cụm nút + - và ô nhập số lượng -->
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn-qty-detail" onclick="changeQuantity(-1)">-</button>
                            
                            <input type="number" 
                                   id="product-qty" 
                                   name="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="{{ $product->stock }}" 
                                   class="input-qty-detail"
                                   oninput="validateQuantityInput(this)">
                            
                            <button type="button" class="btn-qty-detail" onclick="changeQuantity(1)">+</button>
                        </div>

                        <button type="submit" class="btn btn-gold flex-grow-1 shadow-sm">THÊM VÀO GIỎ HÀNG</button>
                    </form>
                @else
                    <button class="btn btn-secondary w-100 py-3 fw-bold" disabled>SẢN PHẨM ĐÃ HẾT HÀNG</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 border-top border-warning mt-5">
        <div class="container text-center">
            <p class="mb-1 fw-bold">HOA NGHIÊM VIỆT PHỤC - DI SẢN & THỜI TRANG</p>
            <p class="small text-muted mb-0">© 2026 Bảo lưu mọi quyền.</p>
        </div>
    </footer>

    <!-- JS Xử lý số lượng -->
    <script>
        const maxStock = {{ $product->stock ?? 999 }};

        // Hàm xử lý nút bấm + -
        function changeQuantity(change) {
            let qtyInput = document.getElementById('product-qty');
            let currentQty = parseInt(qtyInput.value) || 1;
            let newQty = currentQty + change;

            if (newQty >= 1 && newQty <= maxStock) {
                qtyInput.value = newQty;
            }
        }

        // Hàm lọc và validate khi người dùng tự gõ phím
        function validateQuantityInput(input) {
            // Loại bỏ các ký tự không phải số
            input.value = input.value.replace(/[^0-9]/g, '');

            let val = parseInt(input.value);

            // Tự đưa về 1 nếu nhập nhỏ hơn 1 hoặc để trống
            if (isNaN(val) || val < 1) {
                input.value = 1;
            } 
            // Giới hạn không vượt quá tồn kho
            else if (val > maxStock) {
                input.value = maxStock;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>