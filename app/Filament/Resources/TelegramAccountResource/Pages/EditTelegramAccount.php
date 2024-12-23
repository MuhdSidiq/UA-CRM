<?php

namespace App\Filament\Resources\TelegramAccountResource\Pages;

use App\Filament\Resources\TelegramAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTelegramAccount extends EditRecord
{
    protected static string $resource = TelegramAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
