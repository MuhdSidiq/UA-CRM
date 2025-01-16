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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->integer('telegram_account_id')->nullable();
            $table->bigInteger('telegram_chat_id')->nullable();
            $table->bigInteger('telegram_user_id')->nullable()->unique(); //userID
            $table->string('telegram_username')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('country')->nullable();
            $table->enum('status', ['cold','new', 'f1', 'f2', '50d', 'closed','recon','internal','existing'])->nullable();
            $table->string('platform_username')->nullable();
            $table->string('platform')->nullable();
            $table->timestamp('first_message_date')->nullable(); //First date message initialed
            $table->timestamp('last_message_date')->nullable(); // Last communicate date
            $table->timestamps();

            // Foreign key
            $table->foreign('telegram_account_id')
                ->references('id')
                ->on('telegram_accounts')
                ->nullOnDelete();

            // Indexes
            $table->index('telegram_account_id', 'idx_telegram_account');
            $table->index('status', 'idx_status');
            $table->index(['first_message_date', 'last_message_date'], 'idx_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
