<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'item.view',
            'item.create',
            'item.edit',
            'item.delete',
            'item.export',
            'item.import',
            'warehouse.manage',
            'user.manage',
            'role.manage',
            'permission.manage',
            'activity-log.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $adminGudang = Role::firstOrCreate(['name' => 'Admin Gudang', 'guard_name' => 'web']);
        $adminGudang->syncPermissions([
            'item.view', 'item.create', 'item.edit', 'item.delete',
            'item.export', 'item.import', 'warehouse.manage', 'activity-log.view',
        ]);

        $staffGudang = Role::firstOrCreate(['name' => 'Staff Gudang', 'guard_name' => 'web']);
        $staffGudang->syncPermissions([
            'item.view', 'item.create', 'item.edit', 'item.export', 'item.import',
        ]);

        $viewer = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions(['item.view', 'item.export']);

        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $superAdminUser->assignRole('Super Admin');

        $adminGudangUser = User::firstOrCreate(
            ['email' => 'admin@gudang.com'],
            [
                'name' => 'Admin Gudang',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $adminGudangUser->assignRole('Admin Gudang');

        $staffUser = User::firstOrCreate(
            ['email' => 'staff@gudang.com'],
            [
                'name' => 'Staff Gudang',
                'password' => Hash::make('staff123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $staffUser->assignRole('Staff Gudang');
    }
}
