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
        Schema::create('daily_account_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('telegram_account_id');
            $table->date('report_date');
            $table->integer('new_leads_count')->default(0);
            $table->integer('closed_leads_count')->default(0);
            $table->integer('total_messages_count')->default(0);
            $table->integer('total_response_time_seconds')->default(0);
            $table->integer('response_count')->default(0);
            $table->integer('average_response_time_seconds')->default(0);
            $table->boolean('is_corrected')->default(false);
            $table->text('correction_note')->nullable();
            $table->timestamp('corrected_at')->nullable();
            $table->timestamps();

            // Add the foreign key constraint separately
            $table->foreign('telegram_account_id')
                ->references('id')
                ->on('telegram_accounts')
                ->onDelete('cascade');

            // Ensure unique report per day per account
            $table->unique(['telegram_account_id', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_account_reports');
    }
};
