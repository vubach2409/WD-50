<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Nếu khách không đăng nhập
            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->decimal('total_price', 10, 2);
            $table->string('payment_method')->default('COD');
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};