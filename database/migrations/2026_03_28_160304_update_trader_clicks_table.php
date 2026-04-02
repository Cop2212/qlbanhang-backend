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
        Schema::table('trader_clicks', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('trader_link_id')->nullable();

            $table->string('session_id')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_campaign')->nullable();

            // 🔥 index (rất quan trọng)
            $table->index('product_id');
            $table->index('trader_link_id');

            // 🔥 foreign keys
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('set null');

            $table->foreign('trader_link_id')
                ->references('id')
                ->on('trader_links')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trader_clicks', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['trader_link_id']);

            $table->dropIndex('trader_clicks_product_id_index');
            $table->dropIndex('trader_clicks_trader_link_id_index');

            $table->dropColumn([
                'product_id',
                'trader_link_id',
                'session_id',
                'utm_source',
                'utm_campaign'
            ]);
        });
    }
};
