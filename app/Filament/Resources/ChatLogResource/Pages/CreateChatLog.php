<?php

namespace App\Filament\Resources\ChatLogResource\Pages;

use App\Filament\Resources\ChatLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChatLog extends CreateRecord
{
    protected static string $resource = ChatLogResource::class;
}
