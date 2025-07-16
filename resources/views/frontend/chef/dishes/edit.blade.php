@extends('frontend.layouts.app')

@section('title', 'تعديل الطبق')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
@endsection

@section('content')
<div class="row">
    <!-- القائمة الجانبية -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <img src="{{ Auth::user()->profile_image ?? '/images/default-chef.jpg' }}" class="rounded-circle mb-3" alt="{{ Auth::user()->name }}" width="100" height="100" style="object-fit: cover;">
                <h4>{{ Auth::user()->name }}</h4>
                <p class="text-muted">
                    <span class="badge bg-success">شيف</span>
                </p>
                <p class="text-muted">{{ Auth::user()->email }}</p>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('frontend.chef.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> لوحة التحكم
                </a>
                <a href="{{ route('frontend.chef.dishes') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.dishes*') ? 'active' : '' }}">
                    <i class="fas fa-utensils me-2"></i> الأطباق
                </a>
                <a href="{{ route('frontend.chef.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag me-2"></i> الطلبات
                </a>
                <a href="{{ route('frontend.chef.earnings') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.earnings*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave me-2"></i> الأرباح
                </a>
                <a href="{{ route('frontend.chef.ratings') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.ratings') ? 'active' : '' }}">
                    <i class="fas fa-star me-2"></i> التقييمات
                </a>
                <a href="{{ route('frontend.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.profile') ? 'active' : '' }}">
                    <i class="fas fa-user me-2"></i> الملف الشخصي
                </a>
                
                <form action="{{ route('frontend.logout') }}" method="POST" class="mt-3 px-3 pb-3">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- محتوى تعديل الطبق -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>تعديل الطبق: {{ $dish->name }}</h2>
            <a href="{{ route('frontend.chef.dishes') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i> العودة للأطباق
            </a>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('frontend.chef.dishes.update', $dish->id) }}" method="POST" enctype="multipart/form-data" id="dish-form">
            @csrf
            @method('PUT')
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">المعلومات الأساسية</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">اسم الطبق <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $dish->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">التصنيف <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">اختر التصنيف</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $dish->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="price" class="form-label">السعر (ريال) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $dish->price) }}" required>
                                <span class="input-group-text">ريال</span>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="preparation_time" class="form-label">وقت التحضير (دقائق) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="1" class="form-control @error('preparation_time') is-invalid @enderror" id="preparation_time" name="preparation_time" value="{{ old('preparation_time', $dish->preparation_time) }}" required>
                                <span class="input-group-text">دقيقة</span>
                                @error('preparation_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="description" class="form-label">وصف الطبق <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $dish->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">صور الطبق</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">الصورة الرئيسية <span class="text-danger">*</span></label>
                        <div class="mb-3">
                            <div class="current-image-container">
                                <img src="{{ $dish->image_url }}" alt="{{ $dish->name }}" class="img-thumbnail mb-2" style="max-height: 200px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="replace_main_image" name="replace_main_image" value="1">
                                    <label class="form-check-label" for="replace_main_image">استبدال الصورة الرئيسية</label>
                                </div>
                            </div>
                        </div>
                        <div class="dropzone" id="main-image-dropzone" style="display: none;"></div>
                        <input type="hidden" name="main_image" id="main-image-input">
                        @error('main_image')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">صور إضافية</label>
                        @if(count($dish->images) > 0)
                            <div class="row mb-3">
                                @foreach($dish->images as $image)
                                    <div class="col-md-3 mb-3">
                                        <div class="card">
                                            <img src="{{ $image->url }}" class="card-img-top" alt="صورة إضافية" style="height: 150px; object-fit: cover;">
                                            <div class="card-body p-2">
                                                <div class="form-check">
                                                    <input class="form-check-input delete-image-check" type="checkbox" id="delete_image_{{ $image->id }}" name="delete_images[]" value="{{ $image->id }}">
                                                    <label class="form-check-label" for="delete_image_{{ $image->id }}">حذف</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="add_more_images" name="add_more_images" value="1">
                            <label class="form-check-label" for="add_more_images">إضافة المزيد من الصور</label>
                        </div>
                        
                        <div class="dropzone" id="additional-images-dropzone" style="display: none;"></div>
                        <div id="additional-images-container"></div>
                        @error('additional_images')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">يمكنك إضافة حتى 5 صور إضافية</small>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">المكونات والتفاصيل</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label">المكونات <span class="text-danger">*</span></label>
                        <div id="ingredients-container">
                            @if(count($dish->ingredients) > 0)
                                @foreach($dish->ingredients as $index => $ingredient)
                                    <div class="ingredient-item mb-2 row g-2">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="ingredients[{{ $index }}][name]" placeholder="اسم المكون" value="{{ $ingredient->name }}" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="ingredients[{{ $index }}][quantity]" placeholder="الكمية (مثال: 100 جرام)" value="{{ $ingredient->quantity }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger w-100 remove-ingredient" {{ count($dish->ingredients) <= 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="ingredient-item mb-2 row g-2">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="ingredients[0][name]" placeholder="اسم المكون" required>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="ingredients[0][quantity]" placeholder="الكمية (مثال: 100 جرام)" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-danger w-100 remove-ingredient" disabled>
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-primary mt-2" id="add-ingredient">
                            <i class="fas fa-plus me-2"></i> إضافة مكون
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">معلومات غذائية (اختياري)</label>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="calories" class="form-label">السعرات الحرارية</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" id="calories" name="nutrition[calories]" value="{{ old('nutrition.calories', $dish->nutrition->calories ?? '') }}">
                                    <span class="input-group-text">سعرة</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="protein" class="form-label">البروتين</label>
                                <div class="input-group">
                                    <input type="number" min="0" step="0.1" class="form-control" id="protein" name="nutrition[protein]" value="{{ old('nutrition.protein', $dish->nutrition->protein ?? '') }}">
                                    <span class="input-group-text">جرام</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="carbs" class="form-label">الكربوهيدرات</label>
                                <div class="input-group">
                                    <input type="number" min="0" step="0.1" class="form-control" id="carbs" name="nutrition[carbs]" value="{{ old('nutrition.carbs', $dish->nutrition->carbs ?? '') }}">
                                    <span class="input-group-text">جرام</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="fat" class="form-label">الدهون</label>
                                <div class="input-group">
                                    <input type="number" min="0" step="0.1" class="form-control" id="fat" name="nutrition[fat]" value="{{ old('nutrition.fat', $dish->nutrition->fat ?? '') }}">
                                    <span class="input-group-text">جرام</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-block">خيارات إضافية</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_vegetarian" name="options[is_vegetarian]" value="1" {{ old('options.is_vegetarian', $dish->is_vegetarian) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_vegetarian">نباتي</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_vegan" name="options[is_vegan]" value="1" {{ old('options.is_vegan', $dish->is_vegan) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_vegan">نباتي صرف</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_gluten_free" name="options[is_gluten_free]" value="1" {{ old('options.is_gluten_free', $dish->is_gluten_free) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_gluten_free">خالي من الغلوتين</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_spicy" name="options[is_spicy]" value="1" {{ old('options.is_spicy', $dish->is_spicy) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_spicy">حار</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">خيارات النشر</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $dish->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">نشر الطبق (متاح للطلب)</label>
                    </div>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $dish->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">تمييز الطبق (يظهر في قسم الأطباق المميزة)</label>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                <button type="button" class="btn btn-outline-secondary me-md-2" onclick="window.location='{{ route('frontend.chef.dishes') }}'">إلغاء</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
    // تعطيل الاستخدام التلقائي لـ Dropzone
    Dropzone.autoDiscover = false;
    
    document.addEventListener('DOMContentLoaded', function() {
        // إعداد Dropzone للصورة الرئيسية
        const mainImageDropzone = new Dropzone("#main-image-dropzone", {
            url: "{{ route('frontend.chef.upload-temp-image') }}",
            paramName: "image",
            maxFiles: 1,
            maxFilesize: 5, // 5MB
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            dictDefaultMessage: "اسحب الصورة الرئيسية هنا أو انقر للاختيار",
            dictRemoveFile: "حذف",
            dictFileTooBig: "حجم الملف كبير جدًا ({{filesize}}MB). الحد الأقصى هو {{maxFilesize}}MB.",
            dictInvalidFileType: "لا يمكنك رفع هذا النوع من الملفات.",
            dictMaxFilesExceeded: "لا يمكنك رفع المزيد من الملفات.",
        });
        
        mainImageDropzone.on("success", function(file, response) {
            document.getElementById('main-image-input').value = response.path;
        });
        
        mainImageDropzone.on("removedfile", function(file) {
            document.getElementById('main-image-input').value = '';
        });
        
        // إظهار/إخفاء منطقة رفع الصورة الرئيسية
        document.getElementById('replace_main_image').addEventListener('change', function() {
            document.getElementById('main-image-dropzone').style.display = this.checked ? 'block' : 'none';
        });
        
        // إعداد Dropzone للصور الإضافية
        const additionalImagesDropzone = new Dropzone("#additional-images-dropzone", {
            url: "{{ route('frontend.chef.upload-temp-image') }}",
            paramName: "image",
            maxFiles: 5,
            maxFilesize: 5, // 5MB
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            dictDefaultMessage: "اسحب الصور الإضافية هنا أو انقر للاختيار",
            dictRemoveFile: "حذف",
            dictFileTooBig: "حجم الملف كبير جدًا ({{filesize}}MB). الحد الأقصى هو {{maxFilesize}}MB.",
            dictInvalidFileType: "لا يمكنك رفع هذا النوع من الملفات.",
            dictMaxFilesExceeded: "لا يمكنك رفع المزيد من الملفات.",
        });
        
        additionalImagesDropzone.on("success", function(file, response) {
            const container = document.getElementById('additional-images-container');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'additional_images[]';
            input.value = response.path;
            input.dataset.uuid = file.upload.uuid;
            container.appendChild(input);
        });
        
        additionalImagesDropzone.on("removedfile", function(file) {
            const container = document.getElementById('additional-images-container');
            const input = container.querySelector(`input[data-uuid="${file.upload.uuid}"]`);
            if (input) {
                container.removeChild(input);
            }
        });
        
        // إظهار/إخفاء منطقة رفع الصور الإضافية
        document.getElementById('add_more_images').addEventListener('change', function() {
            document.getElementById('additional-images-dropzone').style.display = this.checked ? 'block' : 'none';
        });
        
        // إدارة المكونات
        let ingredientIndex = {{ count($dish->ingredients) > 0 ? count($dish->ingredients) - 1 : 0 }};
        
        document.getElementById('add-ingredient').addEventListener('click', function() {
            ingredientIndex++;
            const container = document.getElementById('ingredients-container');
            
            const ingredientItem = document.createElement('div');
            ingredientItem.className = 'ingredient-item mb-2 row g-2';
            ingredientItem.innerHTML = `
                <div class="col-md-5">
                    <input type="text" class="form-control" name="ingredients[${ingredientIndex}][name]" placeholder="اسم المكون" required>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="ingredients[${ingredientIndex}][quantity]" placeholder="الكمية (مثال: 100 جرام)" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-ingredient">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(ingredientItem);
            
            // تفعيل زر الحذف للعنصر الأول إذا كان هناك أكثر من عنصر
            if (container.querySelectorAll('.ingredient-item').length > 1) {
                container.querySelector('.remove-ingredient[disabled]')?.removeAttribute('disabled');
            }
        });
        
        // حذف المكونات
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-ingredient') || e.target.closest('.remove-ingredient')) {
                const button = e.target.classList.contains('remove-ingredient') ? e.target : e.target.closest('.remove-ingredient');
                const container = document.getElementById('ingredients-container');
                const ingredientItem = button.closest('.ingredient-item');
                
                container.removeChild(ingredientItem);
                
                // تعطيل زر الحذف للعنصر الأول إذا كان هو العنصر الوحيد المتبقي
                if (container.querySelectorAll('.ingredient-item').length === 1) {
                    container.querySelector('.remove-ingredient').setAttribute('disabled', 'disabled');
                }
            }
        });
    });
</script>
@endsection
