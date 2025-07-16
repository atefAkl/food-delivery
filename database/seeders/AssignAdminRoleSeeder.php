<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssignAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التأكد من وجود دور المشرف
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // البحث عن المستخدم عن طريق البريد الإلكتروني
        $user = User::where('email', 'superadmin@example.com')->first();
        
        // التحقق من وجود المستخدم قبل تعيين الدور
        if ($user) {
            // التأكد من أن المستخدم له نوع "admin"
            $user->type = 'admin';
            $user->save();
            
            // إضافة دور المشرف للمستخدم
            $user->assignRole($adminRole);
            
            $this->command->info('تم إضافة دور المشرف للمستخدم superadmin@example.com بنجاح!');
        } else {
            $this->command->error('لم يتم العثور على المستخدم superadmin@example.com!');
        }
    }
}
