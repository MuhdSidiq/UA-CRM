<?php

namespace App\Filament\Resources\TelegramAccountResource\Pages;

use App\Filament\Resources\TelegramAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTelegramAccounts extends ListRecords
{
    protected static string $resource = TelegramAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
