<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use App\Models\ItemDescription;
use Illuminate\Database\Seeder;

class ItemDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $tabung = ItemCategory::where('category_code', 'TABUNG')->first();
        $pipe = ItemCategory::where('category_code', 'PIPE')->first();

        $descriptions = [
            [
                'item_code' => 'TAB-001',
                'item_description' => 'Tabung Gas LPG 3 Kg',
                'capacity' => 3.00,
                'uom' => 'Kg',
                'category_id' => $tabung?->category_id,
            ],
            [
                'item_code' => 'TAB-002',
                'item_description' => 'Tabung Gas LPG 12 Kg',
                'capacity' => 12.00,
                'uom' => 'Kg',
                'category_id' => $tabung?->category_id,
            ],
            [
                'item_code' => 'PIPE-001',
                'item_description' => 'Pipa Carbon Steel 2 inch',
                'capacity' => 2.00,
                'uom' => 'inch',
                'category_id' => $pipe?->category_id,
            ],
        ];

        foreach ($descriptions as $desc) {
            ItemDescription::updateOrCreate(
                ['item_code' => $desc['item_code']],
                $desc
            );
        }
    }
}
