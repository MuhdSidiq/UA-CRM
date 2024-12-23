<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
{
    use HasFactory;

    //

    // Relationship with TelegramAccount
    public function telegramAccount(): BelongsTo
    {
        return $this->belongsTo(TelegramAccount::class);
    }

    // Relationship with ChatLogs
    public function chatLogs(): HasMany
    {
        return $this->hasMany(ChatLog::class);
    }

    // Relationship with ChatStatusLogs
    public function statusLogs(): HasMany
    {
        return $this->hasMany(ChatStatusLog::class);
    }

    // Relationship with LeadMeta
    public function meta(): HasOne
    {
        return $this->hasOne(LeadMeta::class);
    }
}
