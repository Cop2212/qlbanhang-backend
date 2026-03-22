<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
    <meta charset="UTF-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1f2937;
            color: white;
            position: fixed;
        }

        .sidebar a {
            color: #cbd5e1;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #374151;
            color: white;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .topbar {
            background: white;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
        }

        .card-dashboard {
            border-radius: 12px;
        }

        .table img {
            border-radius: 8px;
        }

        .badge {
            padding: 6px 10px;
            font-size: 13px;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4 class="text-center py-3 border-bottom">Admin</h4>

        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('admin.products.index') }}">📦 Sản phẩm</a>
        <a href="{{ route('admin.specifications.index') }}">📦 Thông số sản phẩm</a>
        <a href="{{ route('admin.categories.index') }}">📂 Loại sản phẩm</a>
        <a href="{{ route('admin.brands.index') }}">🏷 Hãng</a>
        <a href="#">🛒 Đơn hàng</a>
        <a href="#">👥 Khách hàng</a>
        <a href="{{ route('admin.consultations.index') }}">📞 Tư vấn khách hàng</a>

        <hr>

        <a href="{{ route('admin.sliders.index') }}">🖼 Slider</a>
        <a href="{{ route('admin.reviews.index') }}">⭐ Đánh giá</a>
        <a href="{{ route('admin.settings.index') }}">⚙ Cài đặt website</a>
    </div>

    <div class="content">

        <div class="topbar d-flex justify-content-between align-items-center">
            <div>
                Xin chào, {{ auth()->guard('admin')->user()->name }}
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>

        <div class="mt-4">
            @yield('content')
        </div>

    </div>

</body>

</html>