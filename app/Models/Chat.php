<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{

    public function telegramAccount()
    {
        return $this->belongsTo(TelegramAccount::class, 'telegram_account_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

}
