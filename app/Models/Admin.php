<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'department',
        'access_level',
        'last_login_at',
        'is_active',
    ];
    
    protected $casts = [
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * الحصول على المستخدم المرتبط بملف المشرف
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
    
    /**
     * الحصول على معلومات المهام المسندة للمشرف
     */
    public function logs()
    {
        // يمكن إضافة علاقات أخرى هنا إذا تم إنشاء النماذج المناسبة
        // مثل سجلات النشاط أو المهام
        return $this->hasMany(User::class, 'admin_id')->where('type', 'admin');
    }
}
