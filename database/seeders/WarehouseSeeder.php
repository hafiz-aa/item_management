<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        $createdBy = $adminUser?->id;

        $pusat = Warehouse::updateOrCreate(
            ['kode_gudang' => 'WH-MKS-01'],
            [
                'nama_gudang' => 'Gudang Makassar Pusat',
                'tipe' => 'Kantor Pusat',
                'alamat' => 'Jl. Sultan Hasanuddin No. 123',
                'status' => 'Aktif',
                'created_by' => $createdBy,
            ]
        );

        Warehouse::updateOrCreate(
            ['kode_gudang' => 'WH-JKT-01'],
            [
                'nama_gudang' => 'Gudang Jakarta Pusat',
                'tipe' => 'Kantor Pusat',
                'alamat' => 'Jl. Sudirman No. 1',
                'status' => 'Aktif',
                'created_by' => $createdBy,
            ]
        );

        $cabang = [
            [
                'kode_gudang' => 'WH-MKS-02',
                'nama_gudang' => 'Gudang Makassar Timur',
                'alamat' => 'Jl. AP Pettarani No. 456',
                'status' => 'Aktif',
            ],
            [
                'kode_gudang' => 'WH-MKS-03',
                'nama_gudang' => 'Gudang Makassar Barat',
                'alamat' => 'Jl. Ujung Pandang No. 789',
                'status' => 'Aktif',
            ],
            [
                'kode_gudang' => 'WH-MKS-04',
                'nama_gudang' => 'Gudang Makassar Utara',
                'alamat' => 'Jl. Perintis Kemerdekaan Km. 15',
                'status' => 'Tidak Aktif',
            ],
        ];

        foreach ($cabang as $data) {
            Warehouse::updateOrCreate(
                ['kode_gudang' => $data['kode_gudang']],
                [
                    'parent_id' => $pusat->id,
                    'nama_gudang' => $data['nama_gudang'],
                    'tipe' => 'Kantor Cabang',
                    'alamat' => $data['alamat'],
                    'status' => $data['status'],
                    'created_by' => $createdBy,
                ]
            );
        }
    }
}
