<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * Các trường có thể gán hàng loạt.
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'sale_price',
        'color',
        'size',
        'material',
        'image',
        'stock_quantity',
        'sold_count',
        'status',
        'description',
    ];

    protected static function booted()
    {
        static::saving(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    /**
     * Định nghĩa mối quan hệ với model Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getEffectivePriceAttribute()
    {
        return ($this->sale_price && $this->sale_price < $this->price) ? $this->sale_price : $this->price;
    }

    public function orderDetails()
    {
        return $this->hasMany(Order_details::class, 'product_id');
    }
}
