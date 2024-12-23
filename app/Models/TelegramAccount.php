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

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
