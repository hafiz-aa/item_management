<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->primary();
            $table->unsignedBigInteger('comp_id')->index();
            $table->string('branch_code')->unique();
            $table->string('branch_name');
            $table->boolean('branch_is_headquarter')->default(false);
            $table->text('branch_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
