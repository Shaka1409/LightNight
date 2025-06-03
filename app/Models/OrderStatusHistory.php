<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'order_status_histories';

    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_at',
        'changed_by',
    ];

    protected $dates = [
        'changed_at',
    ];

    // Quan hệ: 1 lịch sử thuộc về 1 đơn hàng
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    // Quan hệ: người thay đổi (admin hoặc user)
    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
