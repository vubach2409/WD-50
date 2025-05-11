<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // Các trường cần được phép điền khi tạo/ cập nhật order_detail
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',   // Thêm thông tin tên sản phẩm
        'variant_id',
        'variant_name',   // Thêm thông tin tên biến thể
        'variant_sku',    // SKU của biến thể nếu cần
        'color_name',     // Thêm thông tin tên màu
        'size_name',      // Thêm thông tin tên kích thước
        'price',
        'quantity',
        'variant_image',  // Hình ảnh của biến thể (nếu có)
    ];

    // Quan hệ với bảng orders
    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    // Quan hệ với bảng product_variants
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // Quan hệ với bảng products
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
