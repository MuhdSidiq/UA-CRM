<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyAccountReport extends Model
{
    protected $fillable = [
        'telegram_account_id',
        'report_date',
        'new_leads_count',
        'closed_leads_count',
        'total_messages_count',
        'total_response_time_seconds',
        'response_count',
        'average_response_time_seconds',
        'is_corrected',
        'correction_note',
        'corrected_at'
    ];

    protected $casts = [
        'report_date' => 'date',
        'is_corrected' => 'boolean',
        'corrected_at' => 'datetime'
    ];

    public function telegramAccount(): BelongsTo
    {
        return $this->belongsTo(TelegramAccount::class, 'telegram_account_id');
    }
}
