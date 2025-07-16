<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
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
                'type' => 'admin',
            ],
        ];

        foreach (['Super Admin', 'Manager', 'Content Admin'] as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'admin',
            ]);
        }

        foreach ($admins as $admin) {
            User::updateOrCreate([
                'email' => $admin['email'],
                'password' => $admin['password'],
                'type' => 'admin',
            ], $admin);
        }
    }
}
