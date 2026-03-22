<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // ❗ phải xóa foreign key trước
            $table->dropForeign(['category_id']);

            // sau đó xóa cột
            $table->dropColumn('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // rollback lại nếu cần
            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });
    }
};
