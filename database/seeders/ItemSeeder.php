<?php

namespace Database\Seeders;

use App\Models\ItemDetail;
use App\Models\ItemHeader;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = Warehouse::all();
        $adminUser = User::where('email', 'admin@example.com')->first();

        $items = [
            [
                'item_code' => 'TBG-001',
                'item_name' => 'Oksigen Medis Ukuran Besar',
                'capacity' => 40.00,
                'uom_id_1' => 'Kg',
                'cat_id' => 'Oksigen',
                'detail' => [
                    'itemd_code' => 'SN-OKS-001',
                    'qty' => 10,
                    'status' => 'Aktif',
                    'acquired_date' => Carbon::create(2023, 6, 15),
                ],
            ],
            [
                'item_code' => 'TBG-002',
                'item_name' => 'Nitrogen Cair Industri',
                'capacity' => 35.00,
                'uom_id_1' => 'Kg',
                'cat_id' => 'Nitrogen',
                'detail' => [
                    'itemd_code' => 'SN-NIT-001',
                    'qty' => 5,
                    'status' => 'Aktif',
                    'acquired_date' => Carbon::create(2023, 8, 20),
                ],
            ],
            [
                'item_code' => 'TBG-003',
                'item_name' => 'Asetilen Las',
                'capacity' => 25.00,
                'uom_id_1' => 'Kg',
                'cat_id' => 'Asetilen',
                'detail' => [
                    'itemd_code' => 'SN-ACE-001',
                    'qty' => 8,
                    'status' => 'Aktif',
                    'acquired_date' => Carbon::create(2022, 3, 10),
                ],
            ],
            [
                'item_code' => 'TBG-004',
                'item_name' => 'Karbon Dioksida Makanan',
                'capacity' => 30.00,
                'uom_id_1' => 'Kg',
                'cat_id' => 'CO2',
                'detail' => [
                    'itemd_code' => 'SN-CO2-001',
                    'qty' => 15,
                    'status' => 'Aktif',
                    'acquired_date' => Carbon::create(2024, 1, 5),
                ],
            ],
            [
                'item_code' => 'TBG-005',
                'item_name' => 'Oksigen Medis Ukuran Kecil',
                'capacity' => 15.00,
                'uom_id_1' => 'Kg',
                'cat_id' => 'Oksigen',
                'detail' => [
                    'itemd_code' => 'SN-OKS-002',
                    'qty' => 3,
                    'status' => 'Aktif',
                    'acquired_date' => Carbon::create(2021, 11, 20),
                    'is_broken' => true,
                ],
            ],
            [
                'item_code' => 'TBG-006',
                'item_name' => 'Hidrogen Industri',
                'capacity' => 35.00,
                'uom_id_1' => 'Kg',
                'cat_id' => 'Hidrogen',
                'detail' => [
                    'itemd_code' => 'SN-HID-001',
                    'qty' => 6,
                    'status' => 'Aktif',
                    'acquired_date' => Carbon::create(2024, 4, 15),
                    'is_dispossed' => true,
                ],
            ],
            [
                'item_code' => 'TBG-007',
                'item_name' => 'Oksigen Medis Ukuran Sedang',
                'capacity' => 25.00,
                'uom_id_1' => 'Kg',
                'cat_id' => 'Oksigen',
                'detail' => [
                    'itemd_code' => 'SN-OKS-003',
                    'qty' => 12,
                    'status' => 'Aktif',
                    'acquired_date' => Carbon::create(2024, 7, 1),
                ],
            ],
            [
                'item_code' => 'TBG-008',
                'item_name' => 'Gas Elpiji 50 Kg',
                'capacity' => 50.00,
                'uom_id_1' => 'Kg',
                'cat_id' => 'LPG',
                'detail' => [
                    'itemd_code' => 'SN-LPG-001',
                    'qty' => 20,
                    'status' => 'Aktif',
                    'acquired_date' => Carbon::create(2023, 5, 10),
                ],
            ],
        ];

        foreach ($items as $index => $data) {
            $warehouse = $warehouses->get($index % $warehouses->count());
            $detailData = $data['detail'];
            unset($data['detail']);

            $data['created_by'] = $adminUser?->id;
            $header = ItemHeader::create($data);

            $detailData['itemh_id'] = $header->itemh_id;
            $detailData['warehouse_id'] = $warehouse?->warehouse_id;
            $detailData['created_by'] = $adminUser?->id;
            ItemDetail::create($detailData);
        }
    }
}
