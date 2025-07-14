<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $guard = 'admin';
    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * الحصول على الملف الشخصي للمشرف
     */
    public function profile(): HasOne
    {
        return $this->hasOne(AdminProfile::class, 'admin_id', 'id');
    }

    /**
     * إنشاء ملف شخصي فارغ للمشرف عند إنشاء حساب جديد
     */
    protected static function booted()
    {
        static::created(function (Admin $admin) {
            $admin->profile()->create();
        });
    }
}
