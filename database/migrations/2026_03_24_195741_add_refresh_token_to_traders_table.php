<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('traders', function (Blueprint $table) {
            $table->text('refresh_token')->nullable();
            $table->timestamp('refresh_token_expired_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('traders', function (Blueprint $table) {
            $table->dropColumn(['refresh_token', 'refresh_token_expired_at']);
        });
    }
};
