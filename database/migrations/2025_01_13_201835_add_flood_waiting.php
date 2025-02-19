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
        Schema::table('telegram_accounts', function (Blueprint $table) {
            $table->timestamp('flood_wait_until')->nullable();
            $table->timestamp('last_flood_error')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('telegram_account', function (Blueprint $table) {
            //
        });
    }
};
