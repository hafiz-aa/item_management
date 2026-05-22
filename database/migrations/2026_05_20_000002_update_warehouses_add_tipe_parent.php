<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('warehouses')->onDelete('cascade');
            $table->string('tipe', 20)->default('Kantor Pusat')->after('nama_gudang');
        });

        Schema::dropIfExists('warehouse_branches');
    }

    public function down(): void
    {
        Schema::create('warehouse_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->string('kode_cabang', 50)->unique();
            $table->string('nama_cabang', 100);
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('penanggung_jawab', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'tipe']);
        });
    }
};
