<?php

namespace App\Filament\Resources\TelegramAccountResource\Pages;

use App\Filament\Resources\TelegramAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTelegramAccount extends ViewRecord
{
    protected static string $resource = TelegramAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
