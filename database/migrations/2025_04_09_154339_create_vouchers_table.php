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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();             // Mã voucher
            $table->string('type')->default('fixed');     // Kiểu: fixed hoặc percent
            $table->integer('value');
            $table->integer('min_order_amount');
            $table->integer('usage_limit')->nullable();   // Giới hạn số lần dùng
            $table->integer('used')->default(0);          // Đã dùng bao nhiêu lần
            $table->dateTime('starts_at')->nullable();    // Bắt đầu
            $table->dateTime('expires_at')->nullable();   // Hết hạn
            $table->boolean('is_active')->default(true);  // Có đang hoạt động không
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
