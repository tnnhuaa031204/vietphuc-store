<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoa Nghiêm Việt Phục - Di Sản & Cổ Phục Việt</title>
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
        /* Topbar & Header */
        .top-bar {
            background-color: #4a0e17;
            color: #d4af37;
            font-size: 0.85rem;
            padding: 6px 0;
        }
        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url("{{ asset('images/banner.jpg') }}") center/cover no-repeat;
            color: #f3e5ab;
            padding: 100px 0;
            border-bottom: 4px solid #8b0000;
        }
        /* Product Cards */
        .card-product {
            border: 1px solid #e0d8c3;
            background-color: #fff;
            transition: all 0.3s ease;
        }
        .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(107, 17, 16, 0.15) !important;
        }
        .card-img-top {
            height: 320px;
            object-fit: cover;
        }
        .badge-cat {
            background-color: #8b0000;
            color: #f3e5ab;
            font-weight: normal;
            border: 1px solid #d4af37;
        }
        .btn-gold {
            background-color: #6b1110;
            color: #f3e5ab;
            border: 1px solid #d4af37;
        }
        .btn-gold:hover {
            background-color: #8b0000;
            color: #fff;
        }
        .price-text {
            color: #8b0000;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <!-- Thông báo trên cùng -->
    <div class="top-bar text-center">
        <span>Tôn vinh di sản văn hóa Việt qua từng nét thêu hoa văn cổ truyền</span>
    </div>

    <!-- Nhúng Navbar dùng chung -->
    @include('partials.navbar')

    <!-- Hero Banner -->
    <div class="hero-banner text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">HOA NGHIÊM VIỆT PHỤC</h1>
            <p class="lead fs-4 text-light">Gợi lại hồn xưa qua những tà áo Nhật Bình, Ngũ Thân & Phụ kiện di sản</p>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="container my-5" id="products-list">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-uppercase" style="color: #6b1110;">Danh Mục Sắc Phục</h2>
            <div style="width: 80px; height: 3px; background-color: #d4af37; margin: 10px auto;"></div>
        </div>

        <div class="row g-4">
            @foreach($products as $product)
            <!-- Sửa ở đây: col-lg-3 sẽ chia lưới 12/3 = 4 sản phẩm 1 hàng trên màn hình lớn -->
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card card-product h-100 shadow-sm">
                    <img src="{{ asset($product->image ?? 'images/products/default.jpg') }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column text-center">
                        <span class="badge badge-cat align-self-center mb-2 px-3 py-1">{{ $product->category->name ?? 'Chưa phân loại' }}</span>
                        <h5 class="card-title fw-bold my-2">{{ $product->name }}</h5>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 90) }}</p>
                        <div class="mt-3">
                            <div class="price-text fs-5 mb-3">{{ number_format($product->price, 0, ',', '.') }} đ</div>
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-gold w-100">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Section Giới thiệu -->
    <section id="about-us" class="py-5 my-5" style="background-color: #f4efe4; border-top: 2px solid #d4af37; border-bottom: 2px solid #d4af37;">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="text-uppercase fw-bold text-muted tracking-wide" style="letter-spacing: 2px;">Về Thương Hiệu</span>
                    <h2 class="fw-bold my-3" style="color: #6b1110; font-family: 'Merriweather', serif; font-size: 2.2rem;">
                        HOA NGHIÊM VIỆT PHỤC
                    </h2>
                    <h5 class="fst-italic mb-4" style="color: #8b0000;">"Gìn giữ hồn cốt di sản – Thổi nét hiện đại vào thời trang Việt"</h5>
                    <p class="text-muted leading-relaxed" style="text-align: justify; line-height: 1.8;">
                        <strong>Hoa Nghiêm Việt Phục</strong> ra đời với sứ mệnh phục dựng và tôn vinh vẻ đẹp kiêu hãnh của trang phục truyền thống Việt Nam qua các thời kỳ. Từ những đường nét sang trọng của chiếc <em>Áo Nhật Bình</em> chốn hoàng cung, phom dáng chỉn chu của <em>Áo Ngũ Thân</em>, cho đến các dòng phụ kiện như khăn Bandana mang dấu ấn họa tiết <em>Trống đồng Đông Sơn</em> hay hoa văn <em>Mây Như Ý</em>.
                    </p>
                    <p class="text-muted leading-relaxed" style="text-align: justify; line-height: 1.8;">
                        Mỗi sản phẩm tại Hoa Nghiêm là sự kết hợp hài hòa giữa chất liệu lụa, gấm cao cấp cùng bàn tay khéo léo của người thợ thủ công, giúp người mặc không chỉ khoác lên mình một bộ trang phục mà còn mang theo niềm tự hào di sản văn hóa dân tộc.
                    </p>
                    <div class="d-flex gap-4 mt-4">
                        <div class="border-start border-3 border-warning ps-3">
                            <h4 class="fw-bold mb-0" style="color: #6b1110;">100%</h4>
                            <small class="text-muted">Lụa & Gấm Cao Cấp</small>
                        </div>
                        <div class="border-start border-3 border-warning ps-3">
                            <h4 class="fw-bold mb-0" style="color: #6b1110;">Cổ Truyền</h4>
                            <small class="text-muted">Chuẩn Phom Dáng Dân Tộc</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                    <div class="p-3 bg-white rounded shadow-sm border" style="border-color: #d4af37 !important;">
                        <img src="{{ asset('images/products/nhat-binh.png') }}" alt="Hoa Nghiêm Việt Phục" class="img-fluid rounded" style="max-height: 450px; width: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 border-top border-warning">
        <div class="container text-center">
            <p class="mb-1 fw-bold">HOA NGHIÊM VIỆT PHỤC - DI SẢN & THỜI TRANG</p>
            <p class="small text-muted mb-0">© 2026 Bảo lưu mọi quyền.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>