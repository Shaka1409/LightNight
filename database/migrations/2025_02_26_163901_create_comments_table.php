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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại liên kết đến bảng users (user_id)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Khóa ngoại liên kết đến bảng products (product_id)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // Nội dung bình luận
            $table->text('comment');
            $table->integer('status')->default(1);  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
