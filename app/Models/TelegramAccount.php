<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegramAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'api_id',
        'api_hash',
        'session_data',
        'status',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'telegram_account_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'telegram_account_id');
    }
}
