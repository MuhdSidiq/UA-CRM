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
        Schema::create('telegram_accounts', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name')->nullable();
            $table->string('phone_number', 20);
            $table->string('api_id', 20);
            $table->string('api_hash', 100);
            $table->boolean('is_active')->default(false);
            $table->timestamp('last_connected')->nullable();
            $table->string('temp_code_hash', 255)->nullable();
            $table->timestamps();

            $table->index('phone_number', 'idx_phone');
            $table->index('is_active', 'idx_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_accounts');
    }
};
