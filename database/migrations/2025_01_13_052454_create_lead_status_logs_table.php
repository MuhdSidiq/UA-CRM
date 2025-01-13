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

        Schema::create('lead_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Lead::class, 'lead_id');
            $table->foreignIdFor(\App\Models\TelegramAccount::class, 'telegram_account_id');
            $table->string('old_status', 255)->nullable();
            $table->string('new_status', 255)->nullable();
            $table->timestamp('event_timestamp')->useCurrent();
            $table->timestamp('created_at')->nullable();


    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_status_logs');
    }
};
