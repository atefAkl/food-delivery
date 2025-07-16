@extends('frontend.layouts.app')

@section('title', 'الرسائل')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">الرسائل</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- قائمة المحادثات -->
                        <div class="col-md-4 border-end">
                            <div class="p-3 border-bottom">
                                <h5 class="mb-0">المحادثات</h5>
                            </div>
                            <div class="conversations-list">
                                @if(count($conversations) > 0)
                                    @foreach($conversations as $conversation)
                                        @php
                                            $otherUser = $conversation->sender_id === Auth::id() ? $conversation->receiver : $conversation->sender;
                                            $lastMessage = $conversation->messages->last();
                                            $unreadCount = $conversation->messages->where('is_read', false)->where('receiver_id', Auth::id())->count();
                                        @endphp
                                        <a href="{{ route('frontend.messages.conversation', $conversation->id) }}" class="text-decoration-none text-dark">
                                            <div class="p-3 border-bottom conversation-item {{ $activeConversation && $activeConversation->id === $conversation->id ? 'active bg-light' : '' }}">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $otherUser->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle me-3" alt="{{ $otherUser->name }}" width="50" height="50" style="object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0">{{ $otherUser->name }}</h6>
                                                            <small class="text-muted">{{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}</small>
                                                        </div>
                                                        <p class="mb-0 small text-truncate">{{ $lastMessage ? $lastMessage->message : 'لا توجد رسائل' }}</p>
                                                    </div>
                                                    @if($unreadCount > 0)
                                                        <span class="badge bg-primary rounded-pill ms-2">{{ $unreadCount }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="p-4 text-center">
                                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                        <p>لا توجد محادثات حالياً</p>
                                        @if(Auth::user()->type === 'customer')
                                            <a href="{{ route('frontend.chefs') }}" class="btn btn-sm btn-primary">تصفح الشيفات</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- محتوى المحادثة -->
                        <div class="col-md-8">
                            @if($activeConversation)
                                @php
                                    $otherUser = $activeConversation->sender_id === Auth::id() ? $activeConversation->receiver : $activeConversation->sender;
                                @endphp
                                <div class="d-flex flex-column h-100">
                                    <!-- رأس المحادثة -->
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $otherUser->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle me-3" alt="{{ $otherUser->name }}" width="40" height="40" style="object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">{{ $otherUser->name }}</h6>
                                                <small class="text-muted">
                                                    @if($otherUser->type === 'chef')
                                                        شيف
                                                    @elseif($otherUser->type === 'customer')
                                                        عميل
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="ms-auto">
                                                @if($otherUser->type === 'chef')
                                                    <a href="{{ route('frontend.chef.show', $otherUser->chef->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-user me-1"></i> عرض الملف الشخصي
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- الرسائل -->
                                    <div class="p-3 flex-grow-1 messages-container" id="messagesContainer" style="height: 400px; overflow-y: auto;">
                                        @foreach($messages as $message)
                                            <div class="message mb-3 {{ $message->sender_id === Auth::id() ? 'text-end' : '' }}">
                                                <div class="d-inline-block p-3 rounded {{ $message->sender_id === Auth::id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 75%;">
                                                    <p class="mb-0">{{ $message->message }}</p>
                                                </div>
                                                <div class="mt-1">
                                                    <small class="text-muted">{{ $message->created_at->format('h:i A') }}</small>
                                                    @if($message->sender_id === Auth::id())
                                                        @if($message->is_read)
                                                            <i class="fas fa-check-double text-primary ms-1" title="تم القراءة"></i>
                                                        @else
                                                            <i class="fas fa-check text-muted ms-1" title="تم الإرسال"></i>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- نموذج إرسال رسالة -->
                                    <div class="p-3 border-top">
                                        <form action="{{ route('frontend.messages.send') }}" method="POST" id="messageForm">
                                            @csrf
                                            <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                                            <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="message" placeholder="اكتب رسالتك هنا..." required>
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4">
                                    <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                    <h5>اختر محادثة للبدء</h5>
                                    <p class="text-muted text-center">اختر محادثة من القائمة على اليمين أو ابدأ محادثة جديدة.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تمرير إلى آخر الرسائل
        const messagesContainer = document.getElementById('messagesContainer');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // تحديث حالة القراءة للرسائل
        @if($activeConversation)
            fetch('{{ route("frontend.messages.mark-read", $activeConversation->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        @endif
    });
</script>
@endsection

@section('styles')
<style>
    .conversation-item {
        transition: all 0.2s ease;
    }
    
    .conversation-item:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .conversation-item.active {
        border-right: 3px solid #0d6efd;
    }
    
    .messages-container {
        background-color: #f8f9fa;
    }
</style>
@endsection
