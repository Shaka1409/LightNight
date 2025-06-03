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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại liên kết với bảng users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Tên người nhận
            $table->string('address'); // Địa chỉ giao hàng
            $table->string('phone'); // Số điện thoại
            $table->decimal('total', 15, 2); // Tổng tiền
            $table->string('status')->default('pending'); // Trạng thái: pending, processing, shipped, delivered, cancelled
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
