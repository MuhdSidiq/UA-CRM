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
        Schema::create('telegram_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('telegram_account_id')->constrained('telegram_accounts')
                ->onDelete('cascade');;
            $table->text('session_string');
            $table->timestamps();

            $table->foreign('telegram_account_id')
                ->references('id')
                ->on('telegram_accounts')
                ->onDelete('cascade');

            $table->index('telegram_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_sessions');
    }
};
