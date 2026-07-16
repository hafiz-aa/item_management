<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_warehouse', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
        });

        Schema::table('item_details', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->renameColumn('id', 'warehouse_id');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('parent_id')->constrained('branches', 'branch_id')->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::table('user_warehouse', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->onDelete('cascade');
        });

        Schema::table('item_details', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->nullOnDelete();
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreign('parent_id')->references('warehouse_id')->on('warehouses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('user_warehouse', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
        });

        Schema::table('item_details', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->renameColumn('warehouse_id', 'id');
        });

        Schema::table('user_warehouse', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });

        Schema::table('item_details', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }
};
