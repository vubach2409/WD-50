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
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'shipping'])->default('pending');
            $table->integer('total');
            $table->string('consignee_name');
            $table->string('consignee_phone');
            $table->string('transaction_id')->unique();
            $table->text('consignee_address');
            $table->enum('payment_method', ['cod', 'vnpay'])->default('cod');
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->foreignId('shipping_id')->constrained('shippings')->onDelete('cascade');
            $table->string('city');
            $table->string('email');
            $table->string('subdistrict');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};