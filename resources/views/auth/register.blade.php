<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Hoa Nghiêm Việt Phục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdfbf7; font-family: 'Montserrat', sans-serif; color: #2c2c2c; min-height: 100vh; display: flex; flex-direction: column; }
        h1, h2, h3, h4, .brand-title { font-family: 'Merriweather', serif; }
        .auth-card { background: #fff; border: 1px solid #e0d8c3; border-radius: 8px; }
        .btn-gold { background-color: #6b1110; color: #f3e5ab; font-weight: 600; border: none; }
        .btn-gold:hover { background-color: #4a0b0a; color: #fff; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container py-5 flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="card auth-card shadow-sm p-4">
                <h3 class="text-center mb-4" style="color: #6b1110;">ĐẮNG KÝ TÀI KHOẢN</h3>

                {{-- Khối thông báo lỗi tổng --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    {{-- Họ tên --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Họ và Tên</label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Họ và tên">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Địa chỉ Email</label>
                        <input type="email" name="email" id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="example@domain">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Số điện thoại --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold">Số Điện Thoại</label>
                        <input type="text" name="phone" id="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" placeholder="">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mật khẩu --}}
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Mật Khẩu</label>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Ít nhất 6 ký tự">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Xác nhận mật khẩu --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Xác Nhận Mật Khẩu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control" placeholder="Nhập lại mật khẩu">
                    </div>

                    <button type="submit" class="btn btn-gold w-100 py-2 mt-2">Đăng Ký Tài Khoản</button>
                </form>

                <div class="text-center mt-3">
                    <small>Đã có tài khoản? <a href="{{ route('login') }}" class="text-decoration-none" style="color: #6b1110; font-weight: 600;">Đăng nhập</a></small>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-light py-3 text-center border-top border-warning mt-auto">
        <small>© 2026 Hoa Nghiêm Việt Phục.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>