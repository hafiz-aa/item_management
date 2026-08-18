<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfer_detail', function (Blueprint $table) {
            $table->string('itemd_name', 255)->nullable()->after('itemd_id');
        });
    }

    public function down(): void
    {
        Schema::table('transfer_detail', function (Blueprint $table) {
            $table->dropColumn('itemd_name');
        });
    }
};
