<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('feedbacks', function (Blueprint $table) {
        // Xóa khóa ngoại cũ và cột product_id
        $table->dropForeign(['product_id']);
        $table->dropColumn('product_id');

        // Thêm cột variation_id
        $table->unsignedBigInteger('variation_id');

        // Tạo khóa ngoại mới
        $table->foreign('variation_id')->references('id')->on('product_variants')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('feedbacks', function (Blueprint $table) {
        $table->dropForeign(['variation_id']);
        $table->dropColumn('variation_id');

        $table->unsignedBigInteger('product_id');
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    });
}

};
