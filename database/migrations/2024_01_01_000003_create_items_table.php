<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tabung', 100)->unique();
            $table->text('deskripsi_isi_tabung')->nullable();
            $table->string('serial_no', 100)->unique()->nullable();
            $table->year('tahun_pembuatan')->nullable();
            $table->decimal('berat', 10, 2)->default(0);
            $table->decimal('kapasitas', 10, 2)->default(0);
            $table->string('uom', 20)->default('Kg');
            $table->integer('qty')->default(1);
            $table->date('tanggal_perolehan')->nullable();
            $table->string('kategori', 100)->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->boolean('rusak')->default(false);
            $table->boolean('dijual')->default(false);
            $table->foreignId('lokasi_gudang_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->string('vendor', 100)->nullable();
            $table->string('pemilik_tabung', 100)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
