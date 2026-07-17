<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            BranchSeeder::class,
            WarehouseSeeder::class,
            ItemCategorySeeder::class,
            ItemDescriptionSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
