<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // tên công ty
            $table->string('tax_code')->nullable(); // mã số thuế
            $table->string('representative')->nullable(); // người đại diện
            $table->year('established_year')->nullable(); // năm thành lập
            $table->string('title')->nullable(); // tiêu đề giới thiệu
            $table->text('content')->nullable(); // nội dung giới thiệu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
