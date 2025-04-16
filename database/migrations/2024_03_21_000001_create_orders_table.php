<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('subdistrict');
            $table->string('zip_code');
            $table->string('country');
            $table->decimal('total', 10, 2);
            $table->string('status')->default('pending');
            $table->string('shipping_method');
            $table->decimal('shipping_fee', 10, 2);
            $table->string('payment_method');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
