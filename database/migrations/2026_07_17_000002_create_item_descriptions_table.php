<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_descriptions', function (Blueprint $table) {
            $table->bigIncrements('item_desc_id');
            $table->string('item_code', 100)->unique();
            $table->string('item_description')->nullable();
            $table->decimal('capacity', 10, 2)->default(0);
            $table->string('uom', 20)->default('Kg');
            $table->foreignId('category_id')->nullable()->constrained('item_categories', 'category_id')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_descriptions');
    }
};
