<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestAdminSeeder extends Seeder
{
    public function run(): void
    {
        // التأكد من وجود دور المشرف
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // إنشاء مستخدم إداري واحد فقط للاختبار
        $adminData = [
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'type' => 'admin',
        ];

        $user = User::updateOrCreate(
            ['email' => $adminData['email']],
            $adminData
        );
        
        // تعيين دور المشرف للمستخدم
        $user->assignRole('admin');
        
        // إنشاء سجل في جدول admins مرتبط بالمستخدم
        Admin::updateOrCreate(
            ['id' => $user->id],
            [
                'department' => 'Management',
                'access_level' => 'super_admin',
                'is_active' => true,
                'last_login_at' => now(),
            ]
        );

        $this->command->info('تم إنشاء مستخدم إداري للاختبار بنجاح.');
        $this->command->info('البريد الإلكتروني: admin@example.com');
        $this->command->info('كلمة المرور: 123456');
        $this->command->info('تم إنشاء ملف المشرف المرتبط بنجاح.');
    }
}
