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
        Schema::create('trader_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trader_id');

            $table->string('code')->unique();
            $table->string('campaign')->nullable();

            $table->timestamps();

            $table->foreign('trader_id')->references('id')->on('traders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trader_links');
    }
};
