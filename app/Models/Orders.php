<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
'status',
'total',
'consignee_name',
'consignee_phone',
'consignee_address',
'payment_method',
'transaction_id',
'shipping_fee',
'shipping_id',
'city',
'email',
'subdistrict',
'voucher_code',
'discount_amount',
    ];
    public function items()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }


    public function payment()
{
    return $this->hasOne(Payment::class, 'order_id');
}
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function ship()
{
    return $this->belongsTo(Shipping::class, 'shipping_id');
}
public function voucher()
{
    return $this->belongsTo(Voucher::class, 'voucher_code', 'code');
}
}
