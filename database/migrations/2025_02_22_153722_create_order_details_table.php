<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('variant_image')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('variant_name')->nullable();
            $table->string('variant_sku')->nullable();
            $table->string('color_name')->nullable(); 
            $table->string('size_name')->nullable();   
            $table->integer('price');
            $table->integer('quantity');
            $table->timestamps();
        
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};