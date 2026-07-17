<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
            'branch.view',
            'branch.create',
            'branch.edit',
            'branch.delete',
            'item-category.view',
            'item-category.create',
            'item-category.edit',
            'item-category.delete',
            'item-description.view',
            'item-description.create',
            'item-description.edit',
            'item-description.delete',
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
            'item.export', 'item.import', 'warehouse.manage', 'branch.view',
            'item-category.view', 'item-category.create', 'item-category.edit', 'item-category.delete',
            'item-description.view', 'item-description.create', 'item-description.edit', 'item-description.delete',
            'activity-log.view',
        ]);

        $staffGudang = Role::firstOrCreate(['name' => 'Staff Gudang', 'guard_name' => 'web']);
        $staffGudang->syncPermissions([
            'item.view', 'item.create', 'item.edit', 'item.export', 'item.import',
            'branch.view',
            'item-category.view', 'item-category.create',
            'item-description.view', 'item-description.create',
        ]);

        $viewer = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions(['item.view', 'item.export']);

        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'users_name' => 'Super Admin',
                'users_names' => 'Super Admin',
                'users_code' => 'ADM001',
                'users_password' => '',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $superAdminUser->assignRole('Super Admin');

        $adminGudangUser = User::firstOrCreate(
            ['email' => 'admin@gudang.com'],
            [
                'users_name' => 'Admin Gudang',
                'users_names' => 'Admin Gudang',
                'users_code' => 'ADM002',
                'users_password' => '',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $adminGudangUser->assignRole('Admin Gudang');

        $staffUser = User::firstOrCreate(
            ['email' => 'staff@gudang.com'],
            [
                'users_name' => 'Staff Gudang',
                'users_names' => 'Staff Gudang',
                'users_code' => 'STF001',
                'users_password' => '',
                'password' => Hash::make('staff123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $staffUser->assignRole('Staff Gudang');
    }
}
