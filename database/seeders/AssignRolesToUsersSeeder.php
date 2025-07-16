<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssignRolesToUsersSeeder extends Seeder
{
    public function run(): void
    {
        // التأكد من وجود الأدوار الأساسية
        $roles = ['admin', 'chef', 'customer'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // الحصول على جميع المستخدمين وتعيين الأدوار بناءً على حقل type
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            if ($user->type && in_array($user->type, $roles)) {
                $user->syncRoles([$user->type]);
                $count++;
            }
        }

        $this->command->info("تم تحديث أدوار {$count} مستخدم بنجاح");
    }
}
