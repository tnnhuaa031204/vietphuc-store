<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Hoa Nghiêm Việt Phục</title>
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
        <div class="col-md-5">
            <div class="card auth-card shadow-sm p-4">
                <h3 class="text-center mb-4" style="color: #6b1110;">ĐĂNG NHẬP</h3>

                {{-- Thông báo lỗi tổng hợp từ Controller (nếu có) --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label font-weight-bold">Địa chỉ Email</label>
                        <input type="email" name="email" id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required placeholder="example@domain">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mật khẩu --}}
                    <div class="mb-3">
                        <label for="password" class="form-label font-weight-bold">Mật Khẩu</label>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               required placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ghi nhớ đăng nhập --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                    </div>

                    <button type="submit" class="btn btn-gold w-100 py-2">Đăng Nhập</button>
                </form>

                <div class="text-center mt-3">
                    <small>Chưa có tài khoản? <a href="{{ route('register') }}" class="text-decoration-none" style="color: #6b1110; font-weight: 600;">Đăng ký ngay</a></small>
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