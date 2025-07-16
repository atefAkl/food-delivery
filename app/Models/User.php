<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_image',
        'bio',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the customer profile if user is a customer.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id');
    }

    /**
     * Get the chef profile if user is a chef.
     */
    public function chef()
    {
        return $this->hasOne(Chef::class, 'id');
    }

    /**
     * Get the admin profile if user is an admin.
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id');
    }


    public function cart()
    {
        return $this->hasOne(Cart::class, 'customer_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'customer_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'customer_id');
    }

    public function defaultAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }
    
    /**
     * تعيين الدور للمستخدم عند تحديد النوع
     * 
     * @param string $type
     * @return void
     */
    public function setTypeAttribute($type)
    {
        $this->attributes['type'] = $type;
        
        // إضافة الدور المناسب تلقائيًا عند تحديد النوع للمستخدم الموجود
        if ($this->id) {
            $this->syncRoles([$type]);
        }
    }
    
    /**
     * تسجيل الدالة التي تعمل عند بدء النموذج
     */
    protected static function booted()
    {
        // إضافة الدور تلقائياً عند إنشاء مستخدم جديد
        static::created(function ($user) {
            if ($user->type) {
                $user->assignRole($user->type);
            }
        });
    }
    
    /**
     * احصل على الدور الأساسي للمستخدم (متوافق مع حقل type)
     * 
     * @return string|null
     */
    public function getPrimaryRoleAttribute()
    {
        return $this->type;
    }
}
