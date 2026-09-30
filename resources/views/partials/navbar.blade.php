<nav class="navbar navbar-expand-lg sticky-top navbar-dark" style="background-color: #6b1110; border-bottom: 2px solid #d4af37;">
    <div class="container">
        <a class="navbar-brand fw-bold brand-title" href="{{ route('products.index') }}" style="color: #f3e5ab; font-family: 'Merriweather', serif; font-size: 1.5rem; letter-spacing: 2px;">HOA NGHIÊM VIỆT PHỤC</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto align-items-center ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link text-light me-3" href="{{ route('products.index') }}">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light me-3" href="{{ route('products.index') }}#products-list">Bộ Sưu Tập</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light me-3" href="{{ route('products.index') }}#about-us">Giới thiệu</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <!-- Giỏ hàng -->
                <a class="btn btn-outline-warning text-warning px-3 py-1 fw-bold me-2" href="{{ route('cart.index') }}">
                    Giỏ hàng ({{ session('cart') ? count(session('cart')) : 0 }})
                </a>

                <!-- Kiểm tra đăng nhập -->
                @auth
                    <span class="fw-bold me-1" style="color: #f3e5ab; font-size: 0.9rem;">
                        Xin chào, <span class="text-warning">{{ auth()->user()->name }}</span>
                    </span>

                    {{-- Nút Trang Quản Trị dành riêng cho Admin --}}
                    @if(auth()->user()->role === 'admin' || auth()->user()->is_admin == 1)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm fw-bold text-dark me-1">Quản trị</a>
                    @endif

                    {{-- Nút xem Đơn hàng cá nhân --}}
                    <a href="{{ route('orders.mine') }}" class="btn btn-outline-warning btn-sm me-1">Đơn hàng</a>

                    {{-- Nút Đăng xuất --}}
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger px-2">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login.form') }}" class="btn btn-outline-warning btn-sm">Đăng nhập</a>
                    <a href="{{ route('register.form') }}" class="btn btn-warning btn-sm text-dark fw-bold">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>
</nav>