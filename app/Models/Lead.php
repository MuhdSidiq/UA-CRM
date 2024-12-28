<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'telegram_account_id',
        'telegram_chat_id',
        'telegram_username',
        'name',
        'email',
        'phone_number',
        'country',
        'status',
        'username',
        'platform',
        'first_message_date',
        'last_message_date'
    ];   //


    public function telegramAccount()
    {
        return $this->belongsTo(TelegramAccount::class, 'telegram_account_id');
    }
}
