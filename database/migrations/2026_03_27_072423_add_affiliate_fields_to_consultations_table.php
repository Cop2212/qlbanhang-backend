<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {

            $table->string('ref_code')->nullable();

            // optional (khuyên dùng)
            $table->enum('source', ['product', 'contact'])
                ->default('contact');
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {

            $table->dropColumn([
                'ref_code',
                'source'
            ]);
        });
    }
};
