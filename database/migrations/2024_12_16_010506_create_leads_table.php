<?php

use App\Models\TelegramAccount;
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
            $table->bigIncrements('id');
            $table->integer('account_id')->nullable();
            $table->string('telegram_chat_id', 255)->nullable();
            $table->string('telegram_username', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->enum('status', ['new', 'f1', 'f2', '50D', 'close', 'cold lead'])->default('new');
            $table->string('username', 255)->nullable();
            $table->string('platform', 255)->nullable();
            $table->timestamp('first_message_date')->nullable();
            $table->timestamp('last_message_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('account_id', 'idx_account');
            $table->index('status', 'idx_status');
            $table->index(['first_message_date', 'last_message_date'], 'idx_dates');
            $table->foreign('account_id')
                ->references('id')
                ->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


    }


};
