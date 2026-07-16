<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('items');

        Schema::create('item_headers', function (Blueprint $table) {
            $table->bigIncrements('itemh_id');
            $table->foreignId('company_id')->nullable();
            $table->string('item_code', 100)->unique();
            $table->string('item_name')->nullable();
            $table->decimal('capacity', 10, 2)->default(0);
            $table->string('uom_id_1', 20)->default('Kg');
            $table->string('uom_id_2', 20)->nullable();
            $table->string('cat_id', 100)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('item_details', function (Blueprint $table) {
            $table->bigIncrements('itemd_id');
            $table->foreignId('itemh_id')->constrained('item_headers', 'itemh_id')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('branch_id')->nullable();
            $table->foreignId('whsl_id')->nullable();
            $table->date('acquired_date')->nullable();
            $table->string('itemd_code', 100)->unique()->nullable();
            $table->integer('qty')->default(1);
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->foreignId('position_id')->nullable();
            $table->boolean('is_broken')->default(false);
            $table->boolean('is_dispossed')->default(false);
            $table->boolean('is_writeoff')->default(false);
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->foreignId('original_branch_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_details');
        Schema::dropIfExists('item_headers');

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
};
