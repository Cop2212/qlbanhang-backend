<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trader_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trader_id')
                ->unique() // mỗi trader chỉ có 1 profile
                ->constrained('traders')
                ->onDelete('cascade');

            $table->string('bank_name')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('bank_owner')->nullable();
            $table->string('phone')->nullable();

            $table->text('note')->nullable(); // admin ghi chú nếu cần

            $table->enum('status', ['incomplete', 'pending', 'approved', 'rejected'])
                ->default('incomplete');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trader_profiles');
    }
};
