<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramSession extends Model
{
    //

    protected $fillable = [
        'telegram_account_id',
        'session_string'
    ];

    // Each TelegramSession belongs to one TelegramAccount
    public function telegramAccount()
    {
        return $this->belongsTo(TelegramAccount::class, 'telegram_account_id');
    }
}
