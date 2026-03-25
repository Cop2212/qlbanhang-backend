<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->unsignedBigInteger('trader_id')->nullable()->after('id');

            $table->foreign('trader_id')
                ->references('id')
                ->on('traders')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['trader_id']);
            $table->dropColumn('trader_id');
        });
    }
};
