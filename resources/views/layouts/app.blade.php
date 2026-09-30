<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOA NGHIÊM VIỆT PHỤC</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
</head>
<body style="background-color: #fdfbf7;">

    {{-- Nhúng Navbar của dự án --}}
    @include('partials.navbar')

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="text-center py-4 mt-5 border-top" style="background-color: #6b1110; color: #f3e5ab; border-top: 2px solid #d4af37 !important;">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} HOA NGHIÊM VIỆT PHỤC - Tinh hoa Cổ phục Việt.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>