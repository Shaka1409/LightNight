<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'total',
        'status',
        'shipper_name',
        'shipper_phone',
        'payment_method',
        'shipping_area',
        'payment_proof',
        'shipping_fee',
        'is_paid',
    ];

    public static function parseTransferContent($content)
    {
        preg_match('/DH(\d+)/', $content, $orderMatch); // tìm mã đơn hàng: DH1234
        preg_match('/\d{9,11}/', $content, $phoneMatch); // tìm số điện thoại

        return [
            'order_id' => $orderMatch[1] ?? null,
            'phone'    => $phoneMatch[0] ?? null,
        ];
    }

    // Accessor cho payment_proof
    protected function paymentProof(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true)
        );
    }
    public function details()
    {
        return $this->hasMany(Order_details::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
