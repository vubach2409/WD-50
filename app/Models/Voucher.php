<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'usage_limit',
        'used',
        'starts_at',
        'expires_at',
        'is_active',
    ];
    
protected $casts = [
    'starts_at' => 'datetime',
    'expires_at' => 'datetime',
];
public function orders()
    {
        return $this->hasMany(Orders::class);
    }
}
