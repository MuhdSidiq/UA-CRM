<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = [
        'telegram_account_id',
        'chat_id',
        'telegram_message_id',
        'message_text',
        'message_timestamp',
        'sender_type'
    ];

    // Each Message belongs to one TelegramAccount
    public function telegramAccount()
    {
        return $this->belongsTo(TelegramAccount::class, 'telegram_account_id');
    }

    // Each Message belongs to one Chat
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
