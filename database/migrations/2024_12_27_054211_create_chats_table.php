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
        Schema::create('chats', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->integer('telegram_account_id'); // Match type with telegram_accounts.id
            $table->bigInteger('telegram_chat_id');
            $table->string('title')->nullable(); // Chat title
            $table->enum('type', ['private', 'group', 'channel']); // Chat type
            $table->string('username', 100)->nullable(); // Telegram username
            $table->string('first_name', 100)->nullable(); // User's first name
            $table->string('last_name', 100)->nullable(); // User's last name
            $table->timestamps(); // created_at and updated_at

            $table->unique(['telegram_account_id', 'telegram_chat_id'], 'unique_chat');
            $table->index('telegram_chat_id', 'idx_telegram_chat');
            $table->foreign('telegram_account_id')
                ->references('id')
                ->on('telegram_accounts')
                ->onDelete('cascade'); // Cascade delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
