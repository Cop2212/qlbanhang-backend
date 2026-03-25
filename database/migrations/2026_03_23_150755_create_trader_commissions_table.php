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
        Schema::create('trader_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trader_id');
            $table->unsignedBigInteger('consultation_id');

            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');

            $table->timestamps();

            $table->foreign('trader_id')->references('id')->on('traders')->onDelete('cascade');
            $table->foreign('consultation_id')->references('id')->on('consultations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trader_commissions');
    }
};
