<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order_details extends Model
{
    use HasFactory;

    protected $table = 'order_details'; // Đảm bảo Laravel nhận diện đúng bảng

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Mối quan hệ với bảng Orders
     */
    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    /**
     * Mối quan hệ với bảng Products
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
