<?php

namespace App\Filament\Resources\ChatLogResource\Pages;

use App\Filament\Resources\ChatLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChatLog extends ViewRecord
{
    protected static string $resource = ChatLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
