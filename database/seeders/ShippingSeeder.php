<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipping;

class ShippingSeeder extends Seeder {
    public function run() {
        Shipping::insert([
            [
                'method' => 'Vận chuyển tiêu chuẩn', 
                'fee' => 30000, 
                'description' => 'Giao hàng trong 3-5 ngày làm việc.'
            ],
            [
                'method' => 'Vận chuyển nhanh', 
                'fee' => 50000, 
                'description' => 'Giao hàng trong 1-2 ngày làm việc.'
            ],
            [
                'method' => 'Nhận tại cửa hàng', 
                'fee' => 0, 
                'description' => 'Đến cửa hàng để nhận hàng.'
            ],
        ]);
    }
    
}

