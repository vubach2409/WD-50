<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('method'); // Tên phương thức vận chuyển (VD: Giao hàng nhanh, tiêu chuẩn)
            $table->integer('fee'); // Phí vận chuyển
            $table->text('description')->nullable(); // Mô tả thêm về phương thức vận chuyển
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
