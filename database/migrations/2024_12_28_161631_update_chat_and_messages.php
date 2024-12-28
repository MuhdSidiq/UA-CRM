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
        // Skip if chat_id already exists
        if (Schema::hasColumn('messages', 'chat_id')) {
            // If chat_id exists but index doesn't, add the index
            if (!Schema::hasIndex('messages', 'idx_messages_chat')) {
                Schema::table('messages', function (Blueprint $table) {
                    $table->index('chat_id', 'idx_messages_chat');
                });
            }

            // Migrate any remaining data
            DB::statement("
                UPDATE messages m
                INNER JOIN leads l ON m.lead_id = l.id
                INNER JOIN chats c ON l.telegram_chat_id = c.telegram_chat_id
                    AND l.telegram_account_id = c.telegram_account_id
                SET m.chat_id = c.id
                WHERE m.chat_id IS NULL
            ");

            // Remove lead_id column if it exists
            if (Schema::hasColumn('messages', 'lead_id')) {
                Schema::table('messages', function (Blueprint $table) {
                    $table->dropForeign(['lead_id']);
                    $table->dropColumn('lead_id');
                });
            }
            return;
        }

        // Add chat_id column if it doesn't exist
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('chat_id')->nullable()->after('telegram_account_id');
            $table->foreign('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade');
            $table->index('chat_id', 'idx_messages_chat');
        });

        // Migrate data
        DB::statement("
            UPDATE messages m
            INNER JOIN leads l ON m.lead_id = l.id
            INNER JOIN chats c ON l.telegram_chat_id = c.telegram_chat_id
                AND l.telegram_account_id = c.telegram_account_id
            SET m.chat_id = c.id
        ");

        // Remove lead_id column
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['lead_id']);
            $table->dropColumn('lead_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only proceed if chat_id exists
        if (!Schema::hasColumn('messages', 'chat_id')) {
            return;
        }

        // Add lead_id column back
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('lead_id')->nullable()->after('telegram_account_id');
            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->onDelete('cascade');
        });

        // Restore data
        DB::statement("
            UPDATE messages m
            INNER JOIN chats c ON m.chat_id = c.id
            INNER JOIN leads l ON c.telegram_chat_id = l.telegram_chat_id
                AND c.telegram_account_id = l.telegram_account_id
            SET m.lead_id = l.id
        ");

        // Remove chat_id column
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['chat_id']);
            $table->dropIndex('idx_messages_chat');
            $table->dropColumn('chat_id');
        });
    }

};
