<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_specifications', function (Blueprint $table) {
            // Xoá cột cũ
            $table->dropColumn('name');

            // Thêm template_id để liên kết
            $table->foreignId('template_id')
                ->after('product_id')
                ->constrained('specification_templates')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('product_specifications', function (Blueprint $table) {
            $table->string('name');
            $table->dropConstrainedForeignId('template_id');
        });
    }
};
