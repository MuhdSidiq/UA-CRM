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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->integer('telegram_account_id'); // Foreign key to accounts
            $table->unsignedBigInteger('lead_id'); // Match type with leads.id
            $table->bigInteger('telegram_message_id');
            $table->text('message_text')->nullable();
            $table->timestamp('message_timestamp');
            $table->enum('sender_type', ['SALES', 'LEAD']);
            $table->timestamps();

            // Foreign keys
            $table->foreign('telegram_account_id')
                ->references('id')
                ->on('telegram_accounts')
                ->onDelete('cascade');

            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->onDelete('cascade'); // Cascade delete when lead is deleted
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
