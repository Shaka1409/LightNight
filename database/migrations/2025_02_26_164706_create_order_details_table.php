<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id(); // Khóa chính tự động tăng

            // Khóa ngoại liên kết với bảng orders
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Khóa ngoại liên kết với bảng products
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // Đảm bảo mỗi sản phẩm chỉ xuất hiện 1 lần trong mỗi đơn hàng
            $table->unique(['order_id', 'product_id']);

            // Các cột khác
            $table->integer('quantity')->default(1); // Số lượng, mặc định là 1
            $table->decimal('price', 10, 2); // Giá tiền, với 10 chữ số tổng cộng, trong đó 2 chữ số thập phân

            // Cột thời gian tạo và cập nhật
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};