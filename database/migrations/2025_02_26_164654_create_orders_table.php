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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->decimal('total', 15, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->string('shipping_area')->default('hanoi');
            $table->string('payment_method')->default('cod');
            $table->json('payment_proof')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('status')->default('pending');
            $table->string('shipper_name')->nullable();
            $table->string('shipper_phone', 20)->nullable();
            $table->timestamps();
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
