<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('size_id');
            $table->string('variation_name');
            $table->string('sku')->unique();
            $table->decimal('price', 15, 2);
            $table->decimal('weight', 10, 2)->nullable();
            $table->text('image')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
            

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};

