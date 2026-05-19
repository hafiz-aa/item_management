<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Warehouse;
use App\Models\User;
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
                'kode_tabung' => 'TBG-001',
                'deskripsi_isi_tabung' => 'Oksigen Medis Ukuran Besar',
                'serial_no' => 'SN-OKS-001',
                'tahun_pembuatan' => 2023,
                'berat' => 50.00,
                'kapasitas' => 40.00,
                'uom' => 'Kg',
                'qty' => 10,
                'tanggal_perolehan' => Carbon::create(2023, 6, 15),
                'kategori' => 'Oksigen',
                'status' => 'Aktif',
                'rusak' => false,
                'dijual' => false,
                'vendor' => 'PT Gas Industri',
                'pemilik_tabung' => 'PT Rumah Sakit Sejahtera',
            ],
            [
                'kode_tabung' => 'TBG-002',
                'deskripsi_isi_tabung' => 'Nitrogen Cair Industri',
                'serial_no' => 'SN-NIT-001',
                'tahun_pembuatan' => 2023,
                'berat' => 45.00,
                'kapasitas' => 35.00,
                'uom' => 'Kg',
                'qty' => 5,
                'tanggal_perolehan' => Carbon::create(2023, 8, 20),
                'kategori' => 'Nitrogen',
                'status' => 'Aktif',
                'rusak' => false,
                'dijual' => false,
                'vendor' => 'PT Gas Industri',
                'pemilik_tabung' => 'PT Industri Kimia',
            ],
            [
                'kode_tabung' => 'TBG-003',
                'deskripsi_isi_tabung' => 'Asetilen Las',
                'serial_no' => 'SN-ACE-001',
                'tahun_pembuatan' => 2022,
                'berat' => 30.00,
                'kapasitas' => 25.00,
                'uom' => 'Kg',
                'qty' => 8,
                'tanggal_perolehan' => Carbon::create(2022, 3, 10),
                'kategori' => 'Asetilen',
                'status' => 'Aktif',
                'rusak' => false,
                'dijual' => false,
                'vendor' => 'PT Bahan Las',
                'pemilik_tabung' => 'PT Konstruksi Nasional',
            ],
            [
                'kode_tabung' => 'TBG-004',
                'deskripsi_isi_tabung' => 'Karbon Dioksida Makanan',
                'serial_no' => 'SN-CO2-001',
                'tahun_pembuatan' => 2024,
                'berat' => 35.00,
                'kapasitas' => 30.00,
                'uom' => 'Kg',
                'qty' => 15,
                'tanggal_perolehan' => Carbon::create(2024, 1, 5),
                'kategori' => 'CO2',
                'status' => 'Aktif',
                'rusak' => false,
                'dijual' => false,
                'vendor' => 'PT Gas Murni',
                'pemilik_tabung' => 'PT Minuman Segar',
            ],
            [
                'kode_tabung' => 'TBG-005',
                'deskripsi_isi_tabung' => 'Oksigen Medis Ukuran Kecil',
                'serial_no' => 'SN-OKS-002',
                'tahun_pembuatan' => 2021,
                'berat' => 20.00,
                'kapasitas' => 15.00,
                'uom' => 'Kg',
                'qty' => 3,
                'tanggal_perolehan' => Carbon::create(2021, 11, 20),
                'kategori' => 'Oksigen',
                'status' => 'Aktif',
                'rusak' => true,
                'dijual' => false,
                'vendor' => 'PT Gas Industri',
                'pemilik_tabung' => 'PT Klinik Sehat',
            ],
            [
                'kode_tabung' => 'TBG-006',
                'deskripsi_isi_tabung' => 'Hidrogen Industri',
                'serial_no' => 'SN-HID-001',
                'tahun_pembuatan' => 2024,
                'berat' => 40.00,
                'kapasitas' => 35.00,
                'uom' => 'Kg',
                'qty' => 6,
                'tanggal_perolehan' => Carbon::create(2024, 4, 15),
                'kategori' => 'Hidrogen',
                'status' => 'Aktif',
                'rusak' => false,
                'dijual' => true,
                'vendor' => 'PT Energi Baru',
                'pemilik_tabung' => 'PT Laboratorium',
            ],
            [
                'kode_tabung' => 'TBG-007',
                'deskripsi_isi_tabung' => 'Oksigen Medis Ukuran Sedang',
                'serial_no' => 'SN-OKS-003',
                'tahun_pembuatan' => 2024,
                'berat' => 30.00,
                'kapasitas' => 25.00,
                'uom' => 'Kg',
                'qty' => 12,
                'tanggal_perolehan' => Carbon::create(2024, 7, 1),
                'kategori' => 'Oksigen',
                'status' => 'Aktif',
                'rusak' => false,
                'dijual' => false,
                'vendor' => 'PT Gas Industri',
                'pemilik_tabung' => 'RS Umum Daerah',
            ],
            [
                'kode_tabung' => 'TBG-008',
                'deskripsi_isi_tabung' => 'Gas Elpiji 50 Kg',
                'serial_no' => 'SN-LPG-001',
                'tahun_pembuatan' => 2023,
                'berat' => 55.00,
                'kapasitas' => 50.00,
                'uom' => 'Kg',
                'qty' => 20,
                'tanggal_perolehan' => Carbon::create(2023, 5, 10),
                'kategori' => 'LPG',
                'status' => 'Aktif',
                'rusak' => false,
                'dijual' => false,
                'vendor' => 'PT Pertamina',
                'pemilik_tabung' => 'PT Hotel Berkah',
            ],
        ];

        foreach ($items as $index => $data) {
            $warehouse = $warehouses->get($index % $warehouses->count());
            $data['lokasi_gudang_id'] = $warehouse?->id;
            $data['created_by'] = $adminUser?->id;
            Item::create($data);
        }
    }
}
