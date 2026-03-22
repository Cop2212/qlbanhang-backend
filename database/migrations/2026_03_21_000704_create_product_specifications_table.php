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
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();

            // Liên kết đến sản phẩm
            $table->unsignedBigInteger('product_id');

            // Tên thông số: Material, Color, App Name, etc.
            $table->string('name');

            // Giá trị thông số: Aluminum Alloy, Tuya App, etc.
            $table->string('value')->nullable();

            // Quan hệ khóa ngoại
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};
