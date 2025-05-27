<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePriceColumnsFromProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price', 'price_sale']);
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('price');
            $table->integer('price_sale');
        });
    }
}

