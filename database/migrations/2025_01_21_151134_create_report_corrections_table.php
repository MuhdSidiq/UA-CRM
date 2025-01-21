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
        Schema::create('report_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_account_report_id')->constrained('daily_account_reports')->onDelete('cascade');
            $table->string('field_name', 50);
            $table->text('old_value');
            $table->text('new_value');
            $table->text('correction_reason');
            $table->foreignId('corrected_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_corrections');
    }
};
