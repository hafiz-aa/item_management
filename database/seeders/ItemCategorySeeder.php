<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_id' => 1, 'category_code' => 'TABUNG', 'category_name' => 'Tabung Gas', 'description' => 'Tabung gas untuk berbagai keperluan industri'],
            ['category_id' => 2, 'category_code' => 'PIPE', 'category_name' => 'Pipa', 'description' => 'Pipa untuk konektivitas dan distribusi'],
            ['category_id' => 3, 'category_code' => 'VALVE', 'category_name' => 'Katup / Valve', 'description' => 'Katup untuk pengaturan aliran fluida'],
        ];

        foreach ($categories as $category) {
            ItemCategory::updateOrCreate(
                ['category_code' => $category['category_code']],
                $category
            );
        }
    }
}
