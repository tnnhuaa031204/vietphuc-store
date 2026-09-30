<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fdfbf7;
            color: #2c2c2c;
        }
        h1, h2, h3, h4, h5, .brand-title {
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
            transition: all 0.3s ease;
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
            transition: background-color 0.2s ease;
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
        /* Style khối đánh giá */
        .review-box {
            background-color: #fff;
            border: 1px solid #e0d8c3;
            border-radius: 4px;
        }
        .review-item {
            background-color: #fff;
            border-bottom: 1px solid #e0d8c3;
            transition: background-color 0.2s ease;
        }
        .review-item:last-child {
            border-bottom: none;
        }
        .review-item:hover {
            background-color: #faf6f0;
        }
        .star-rating {
            color: #d4af37;
        }
    </style>
</head>
<body>

    <!-- Nhúng Navbar dùng chung -->
    @include('partials.navbar')

    <!-- Product Detail Content -->
    <div class="container my-5">
        <!-- Thông báo trạng thái -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-5 align-items-center">
            <!-- Ảnh sản phẩm -->
            <div class="col-md-6 text-center">
                <img src="{{ asset($product->image ?? 'images/products/default.jpg') }}" 
                     class="img-fluid w-100 product-img shadow-sm" 
                     alt="{{ $product->name }}">
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                @if(isset($product->category))
                    <span class="badge badge-cat px-3 py-2 mb-3 fs-6">{{ $product->category->name }}</span>
                @endif
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

                <p class="text-muted leading-relaxed mb-4">{!! nl2br(e($product->description)) !!}</p>

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

        <!-- Phần Đánh giá Sản phẩm -->
        <div class="row mt-5">
            <div class="col-12 border-top pt-5" style="border-color: #e0d8c3 !important;">
                <h3 class="fw-bold mb-4" style="color: #6b1110;">Đánh giá từ khách hàng</h3>

                <!-- Form Gửi Đánh Giá -->
                @auth
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="mb-5 p-4 review-box shadow-sm">
                        @csrf
                        <h5 class="fs-6 fw-bold mb-3" style="color: #6b1110;">Viết nhận xét của bạn</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn đánh giá:</label>
                            <select name="rating" class="form-select w-auto" required>
                                <option value="5">⭐⭐⭐⭐⭐ (5/5 - Tuyệt vời)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5 - Tốt)</option>
                                <option value="3">⭐⭐⭐ (3/5 - Bình thường)</option>
                                <option value="2">⭐⭐ (2/5 - Tạm được)</option>
                                <option value="1">⭐ (1/5 - Kém)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung đánh giá:</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Chia sẻ cảm nhận về đường kim mũi chỉ, chất liệu vải..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-gold">Gửi đánh giá</button>
                    </form>
                @else
                    <div class="alert alert-warning mb-4" role="alert" style="background-color: #fff8e1; border-color: #d4af37; color: #6b1110;">
                        Vui lòng <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: #8b0000;">đăng nhập</a> để gửi nhận xét cho sản phẩm này.
                    </div>
                @endauth

                <!-- Danh sách Đánh giá -->
                <div class="reviews-list review-box shadow-sm rounded">
                    @forelse($product->reviews ?? [] as $review)
                        <div class="review-item p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold" style="color: #6b1110;">{{ $review->user->name ?? 'Khách hàng' }}</span>
                                <span class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </span>
                            </div>
                            <p class="text-secondary mb-1">{{ $review->comment }}</p>
                            <small class="text-muted" style="font-size: 0.8rem;">
                                {{ $review->created_at ? $review->created_at->format('d/m/Y H:i') : '' }}
                            </small>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted italic">
                            Chưa có nhận xét nào cho sản phẩm này. Hãy là người đầu tiên trải nghiệm và đánh giá!
                        </div>
                    @endforelse
                </div>
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