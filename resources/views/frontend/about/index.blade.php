@extends('frontend.layouts.app')

@section('title', 'من نحن | Food Delivery')

@section('content')
<div class="container py-5">
    <!-- بداية القسم الرئيسي -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold text-primary">من نحن</h1>
            <p class="lead text-muted">نقدم لكم أفضل تجربة توصيل طعام من الشيفات المحليين إلى منزلك</p>
            <hr class="my-4">
        </div>
    </div>

    <!-- نبذة عنا -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h2 class="h3 fw-bold mb-3">قصتنا</h2>
            <p>
                بدأت فكرة منصة توصيل الطعام من الشيفات المستقلين في عام 2023، عندما لاحظنا وجود العديد من الطهاة الموهوبين الذين يفتقرون إلى منصة تمكنهم من مشاركة إبداعاتهم الطهي مع جمهور أوسع.
            </p>
            <p>
                نحن نؤمن بأن الطعام ليس مجرد وجبة، بل هو تجربة ثقافية وفنية، وأن كل طبق يحكي قصة. لذلك، قمنا بإنشاء منصة تجمع بين الشيفات الموهوبين والعملاء الباحثين عن تجارب طعام فريدة ومميزة.
            </p>
            <p>
                اليوم، نفخر بأننا نضم أكثر من 500 شيف محترف من مختلف التخصصات والمطابخ العالمية، ونخدم آلاف العملاء الراضين في جميع أنحاء المملكة.
            </p>
        </div>
        <div class="col-lg-6">
            <img src="/images/about-us.jpg" alt="عن منصة توصيل الطعام" class="img-fluid rounded shadow">
        </div>
    </div>

    <!-- رؤيتنا ورسالتنا -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="icon-circle bg-primary text-white">
                            <i class="fas fa-eye fa-2x"></i>
                        </div>
                    </div>
                    <h3 class="h4 card-title">رؤيتنا</h3>
                    <p class="card-text">
                        أن نكون المنصة الرائدة عالمياً في ربط الشيفات المستقلين بعملائهم، ونشر ثقافة الطعام المنزلي عالي الجودة، وتمكين الطهاة الموهوبين من تحقيق دخل مستدام من شغفهم.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="icon-circle bg-primary text-white">
                            <i class="fas fa-bullseye fa-2x"></i>
                        </div>
                    </div>
                    <h3 class="h4 card-title">رسالتنا</h3>
                    <p class="card-text">
                        توفير منصة آمنة وموثوقة تجمع بين الشيفات المبدعين والعملاء، وتضمن تجربة طعام استثنائية من المطبخ إلى المائدة، مع التركيز على الجودة والتنوع والابتكار في عالم الطهي.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- كيف نعمل -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">كيف تعمل منصتنا؟</h2>
            <p class="text-muted">عملية بسيطة وفعالة لضمان وصول الطعام الطازج إلى منزلك</p>
        </div>

        <div class="col-md-3 mb-4">
            <div class="text-center">
                <div class="mb-3">
                    <div class="icon-circle bg-primary text-white mx-auto">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                </div>
                <h4 class="h5">تصفح واختيار</h4>
                <p class="small text-muted">
                    تصفح مجموعة متنوعة من الأطباق من شيفات محليين موهوبين، واختر ما يناسب ذوقك
                </p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="text-center">
                <div class="mb-3">
                    <div class="icon-circle bg-primary text-white mx-auto">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
                <h4 class="h5">طلب سهل</h4>
                <p class="small text-muted">
                    أضف الأطباق إلى سلتك واختر وقت التوصيل المناسب وطريقة الدفع
                </p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="text-center">
                <div class="mb-3">
                    <div class="icon-circle bg-primary text-white mx-auto">
                        <i class="fas fa-utensils fa-2x"></i>
                    </div>
                </div>
                <h4 class="h5">تحضير الطعام</h4>
                <p class="small text-muted">
                    يقوم الشيف بتحضير طلبك بعناية من مكونات طازجة وعالية الجودة
                </p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="text-center">
                <div class="mb-3">
                    <div class="icon-circle bg-primary text-white mx-auto">
                        <i class="fas fa-truck fa-2x"></i>
                    </div>
                </div>
                <h4 class="h5">توصيل سريع</h4>
                <p class="small text-muted">
                    يصلك الطعام في الوقت المحدد، طازجاً ولذيذاً، جاهزاً للاستمتاع
                </p>
            </div>
        </div>
    </div>

    <!-- لماذا تختارنا -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">لماذا تختارنا؟</h2>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                            <i class="fas fa-award text-primary"></i>
                        </div>
                        <h4 class="h5 mb-0">جودة عالية</h4>
                    </div>
                    <p class="card-text">
                        نختار بعناية شيفات محترفين وموهوبين لضمان تقديم تجربة طعام استثنائية لعملائنا.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                            <i class="fas fa-leaf text-primary"></i>
                        </div>
                        <h4 class="h5 mb-0">مكونات طازجة</h4>
                    </div>
                    <p class="card-text">
                        نلتزم باستخدام مكونات طازجة وعالية الجودة في جميع أطباقنا لتقديم طعام صحي ولذيذ.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                            <i class="fas fa-globe text-primary"></i>
                        </div>
                        <h4 class="h5 mb-0">تنوع المطابخ</h4>
                    </div>
                    <p class="card-text">
                        نقدم مجموعة متنوعة من الأطباق من مختلف المطابخ العالمية لإرضاء جميع الأذواق.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- انضم إلينا -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary text-white p-5 rounded-lg">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3>هل أنت شيف موهوب؟</h3>
                        <p class="lead mb-lg-0">انضم إلى منصتنا اليوم وابدأ في مشاركة إبداعاتك الطهي مع آلاف العملاء!</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('frontend.register') }}?type=chef" class="btn btn-light btn-lg px-4 py-2">انضم كشيف</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- قسم CSS المخصص -->
<style>
    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-square {
        width: 40px;
        height: 40px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection
