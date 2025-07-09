<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('Password@123'),

            ],
            [
                'name' => 'Manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('Password@123'),

            ],
            [
                'name' => 'Content Admin',
                'email' => 'contentadmin@example.com',
                'password' => Hash::make('Password@123'),

            ],
        ];

        foreach (['Super Admin', 'Manager', 'Content Admin'] as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'admin',
            ]);
        }

        foreach ($admins as $admin) {
            Admin::updateOrCreate([
                'email' => $admin['email']
            ], $admin);
        }
    }
}
