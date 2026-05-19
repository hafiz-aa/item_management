<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            [
                'kode_gudang' => 'WH-MKS-01',
                'nama_gudang' => 'Gudang Makassar Pusat',
                'alamat' => 'Jl. Sultan Hasanuddin No. 123',
                'kota' => 'Makassar',
                'provinsi' => 'Sulawesi Selatan',
                'telepon' => '0411-123456',
                'penanggung_jawab' => 'Ahmad Fathoni',
                'keterangan' => 'Gudang utama penyimpanan tabung',
                'status' => 'Aktif',
            ],
            [
                'kode_gudang' => 'WH-MKS-02',
                'nama_gudang' => 'Gudang Makassar Timur',
                'alamat' => 'Jl. AP Pettarani No. 456',
                'kota' => 'Makassar',
                'provinsi' => 'Sulawesi Selatan',
                'telepon' => '0411-789012',
                'penanggung_jawab' => 'Siti Rahma',
                'keterangan' => 'Gudang cabang wilayah timur',
                'status' => 'Aktif',
            ],
            [
                'kode_gudang' => 'WH-MKS-03',
                'nama_gudang' => 'Gudang Makassar Barat',
                'alamat' => 'Jl. Ujung Pandang No. 789',
                'kota' => 'Makassar',
                'provinsi' => 'Sulawesi Selatan',
                'telepon' => '0411-345678',
                'penanggung_jawab' => 'Budi Santoso',
                'keterangan' => 'Gudang cabang wilayah barat',
                'status' => 'Aktif',
            ],
            [
                'kode_gudang' => 'WH-MKS-04',
                'nama_gudang' => 'Gudang Makassar Utara',
                'alamat' => 'Jl. Perintis Kemerdekaan Km. 15',
                'kota' => 'Makassar',
                'provinsi' => 'Sulawesi Selatan',
                'telepon' => '0411-901234',
                'penanggung_jawab' => 'Dewi Lestari',
                'keterangan' => 'Gudang cabang wilayah utara',
                'status' => 'Tidak Aktif',
            ],
        ];

        $adminUser = User::where('email', 'admin@example.com')->first();

        foreach ($warehouses as $data) {
            $data['created_by'] = $adminUser?->id;
            Warehouse::firstOrCreate(
                ['kode_gudang' => $data['kode_gudang']],
                $data
            );
        }
    }
}
