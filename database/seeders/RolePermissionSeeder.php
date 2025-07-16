<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إعادة تعيين ذاكرة التخزين المؤقت للأدوار والصلاحيات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // إنشاء الأدوار الأساسية
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $chefRole = Role::firstOrCreate(['name' => 'chef']);

        // إنشاء الصلاحيات للعملاء
        Permission::firstOrCreate(['name' => 'view_menu']);
        Permission::firstOrCreate(['name' => 'place_order']);
        Permission::firstOrCreate(['name' => 'manage_profile']);
        
        // إنشاء الصلاحيات للطهاة
        Permission::firstOrCreate(['name' => 'manage_dishes']);
        Permission::firstOrCreate(['name' => 'view_orders']);
        Permission::firstOrCreate(['name' => 'update_order_status']);
        
        // إنشاء الصلاحيات للمشرفين
        Permission::firstOrCreate(['name' => 'manage_users']);
        Permission::firstOrCreate(['name' => 'manage_settings']);
        Permission::firstOrCreate(['name' => 'view_reports']);
        
        // إسناد الصلاحيات للأدوار
        $customerRole->givePermissionTo([
            'view_menu',
            'place_order',
            'manage_profile',
        ]);
        
        $chefRole->givePermissionTo([
            'manage_dishes',
            'view_orders',
            'update_order_status',
            'manage_profile',
        ]);
        
        $adminRole->givePermissionTo([
            'manage_users',
            'manage_settings',
            'view_reports',
            'view_menu',
            'manage_dishes',
            'view_orders',
        ]);

        // تحديث الأدوار للمستخدمين الحاليين على أساس حقل type
        $users = User::all();
        foreach ($users as $user) {
            if ($user->type) {
                $user->assignRole($user->type);
            }
        }
    }
}
