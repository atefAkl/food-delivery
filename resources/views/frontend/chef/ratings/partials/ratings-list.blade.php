@if(count($ratings) > 0)
    <div class="ratings-list">
        @foreach($ratings as $rating)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex">
                            <img src="{{ $rating->user->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle me-3" width="50" height="50" alt="{{ $rating->user->name }}" style="object-fit: cover;">
                            <div>
                                <h6 class="mb-1">{{ $rating->user->name }}</h6>
                                <div class="text-muted small">{{ $rating->created_at->format('Y/m/d - h:i A') }}</div>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end">
                            <div class="mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            <div>
                                <span class="badge {{ $rating->rating >= 4 ? 'bg-success' : ($rating->rating == 3 ? 'bg-info' : 'bg-danger') }}">
                                    {{ $rating->rating }}/5
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ $rating->dish->image_url }}" class="img-thumbnail me-3" width="60" height="60" alt="{{ $rating->dish->name }}" style="object-fit: cover;">
                            <div>
                                <h6 class="mb-1">{{ $rating->dish->name }}</h6>
                                <div class="text-muted small">طلب #{{ $rating->order->order_number }}</div>
                            </div>
                        </div>
                        <p class="mb-0">{{ $rating->comment }}</p>
                    </div>
                    
                    @if($rating->images && count($rating->images) > 0)
                        <div class="rating-images mb-3">
                            <div class="row g-2">
                                @foreach($rating->images as $image)
                                    <div class="col-auto">
                                        <a href="{{ $image }}" target="_blank">
                                            <img src="{{ $image }}" class="img-thumbnail" width="80" height="80" alt="صورة التقييم" style="object-fit: cover;">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($rating->reply)
                        <div class="chef-reply mt-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ Auth::user()->profile_image ?? '/images/default-chef.jpg' }}" class="rounded-circle me-2" width="30" height="30" alt="{{ Auth::user()->name }}" style="object-fit: cover;">
                                <div>
                                    <h6 class="mb-0">ردك</h6>
                                    <div class="text-muted small">{{ $rating->reply_at->format('Y/m/d - h:i A') }}</div>
                                </div>
                            </div>
                            <p class="mb-0">{{ $rating->reply }}</p>
                        </div>
                    @endif
                    
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-sm {{ $rating->reply ? 'btn-outline-secondary' : 'btn-outline-primary' }} reply-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#replyModal" 
                                data-rating-id="{{ $rating->id }}"
                                data-existing-reply="{{ $rating->reply ?? '' }}">
                            <i class="fas {{ $rating->reply ? 'fa-edit' : 'fa-reply' }} me-1"></i> 
                            {{ $rating->reply ? 'تعديل الرد' : 'الرد على التقييم' }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- الترقيم -->
        <div class="d-flex justify-content-center">
            {{ $ratings->appends(request()->query())->links() }}
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> لا توجد تقييمات متاحة.
    </div>
@endif
