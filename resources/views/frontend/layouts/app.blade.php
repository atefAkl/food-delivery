<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'الصفحة الرئيسية')</title>

    <!-- الخطوط -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- نمط مخصص -->
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand img {
            height: 40px;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .dish-card img {
            height: 200px;
            object-fit: cover;
        }

        .chef-card img {
            height: 250px;
            object-fit: cover;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 50px 0 20px;
            margin-top: 100px;
        }

        .btn-primary {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
        }

        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }

        .rating {
            color: #ffc107;
        }

        .badge-chef {
            background-color: #28a745;
            color: white;
        }

        .badge-customer {
            background-color: #007bff;
            color: white;
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- القائمة العلوية -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('frontend.home') }}">
                <img src="/images/logo.png" alt="{{ config('app.name') }}" class="img-fluid">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home') }}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.dishes') ? 'active' : '' }}" href="{{ route('frontend.dishes') }}">الأطباق</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.chefs') ? 'active' : '' }}" href="{{ route('frontend.chefs') }}">الشيفات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.about') ? 'active' : '' }}" href="{{ route('frontend.about') }}">من نحن</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.contact') ? 'active' : '' }}" href="/#contact">اتصل بنا</a>
                    </li>
                </ul>

                <div class="d-flex">
                    <form class="d-flex me-2" action="{{ route('frontend.search') }}" method="GET">
                        <input class="form-control me-2" type="search" name="query" placeholder="ابحث عن طبق..." aria-label="Search">
                        <button class="btn btn-outline-primary" type="submit">بحث</button>
                    </form>

                    @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                            @if(Auth::user()->type === 'chef')
                            <span class="badge badge-chef">شيف</span>
                            @elseif(Auth::user()->type === 'customer')
                            <span class="badge badge-customer">عميل</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            @if(Auth::user()->type === 'chef')
                            <li><a class="dropdown-item" href="chef/dashboard">لوحة التحكم</a></li>
                            <li><a class="dropdown-item" href="chef/dishes">أطباقي</a></li>
                            <li><a class="dropdown-item" href="chef/orders">الطلبات</a></li>
                            <li><a class="dropdown-item" href="chef/earnings">الأرباح</a></li>
                            @elseif(Auth::user()->type === 'customer')
                            <li><a class="dropdown-item" href="customer/dashboard">لوحة التحكم</a></li>
                            <li><a class="dropdown-item" href="customer/cart">سلة التسوق</a></li>
                            <li><a class="dropdown-item" href="customer/orders">طلباتي</a></li>
                            <li><a class="dropdown-item" href="customer/favorites">المفضلة</a></li>
                            @endif
                            <li><a class="dropdown-item" href="chef/profile">الملف الشخصي</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('frontend.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">تسجيل الخروج</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    @if(Auth::user()->type === 'customer')
                    <a href="{{ route('frontend.cart') }}" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="badge bg-danger">{{ Auth::user()->customer->cart ? Auth::user()->customer->cart->cartItems->count() : 0 }}</span>
                    </a>
                    @endif
                    @else
                    <a href="{{ route('frontend.login') }}" class="btn btn-outline-primary me-2">تسجيل الدخول</a>
                    <a href="{{ route('frontend.register') }}" class="btn btn-primary">إنشاء حساب</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- محتوى الصفحة -->
    <main>
        @yield('hero')

        <div class="container py-4">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- تذييل الصفحة -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>عن {{ config('app.name') }}</h5>
                    <p>منصة توصيل طعام تربط بين الشيفات المحترفين والعملاء، لتقديم تجربة طعام فريدة ومميزة.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>روابط سريعة</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('frontend.home') }}" class="text-white">الرئيسية</a></li>
                        <li><a href="{{ route('frontend.dishes') }}" class="text-white">الأطباق</a></li>
                        <li><a href="{{ route('frontend.chefs') }}" class="text-white">الشيفات</a></li>
                        <li><a href="{{ route('frontend.about') }}" class="text-white">من نحن</a></li>
                        <li><a href="#contact" class="text-white">اتصل بنا</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>تواصل معنا</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> العنوان: شارع الرياض، المملكة العربية السعودية</li>
                        <li><i class="fas fa-phone me-2"></i> الهاتف: +966 123 456 789</li>
                        <li><i class="fas fa-envelope me-2"></i> البريد الإلكتروني: info@fooddelivery.com</li>
                    </ul>
                    <div class="mt-3">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-3">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <a href="#" class="text-white me-3">سياسة الخصوصية</a>
                        <a href="#" class="text-white">الشروط والأحكام</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @yield('scripts')
</body>

</html>