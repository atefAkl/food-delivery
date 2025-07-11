<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminProfile extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'phone_number',
        'profile_picture',
        'address',
        'position',
        'bio',
        'birth_date',
        'gender',
        'social_links',
    ];

    /**
     * الحقول التي يجب تحويلها
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'social_links' => 'json',
    ];

    /**
     * الحصول على المشرف المرتبط بهذا الملف الشخصي
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
