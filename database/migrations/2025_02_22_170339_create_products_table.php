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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại liên kết với bảng categories, tự động xóa sản phẩm khi danh mục bị xóa (onDelete cascade)
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name')->unique();
             $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('color');
            $table->string('size');
            $table->string('material');
            $table->string('image');
            $table->integer('stock_quantity')->default(0);
            $table->integer('sold_count')->default(0);
            $table->integer('status')->default(1);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
