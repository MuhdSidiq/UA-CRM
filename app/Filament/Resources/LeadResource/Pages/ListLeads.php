<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'New' => Tab::make()->icon('heroicon-o-sparkles')->badge(5),
            'Follow Up 1' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('active', true))->icon('heroicon-o-chat-bubble-left-ellipsis')->badge(20),
            'Follow Up 2' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false))->icon('heroicon-o-chat-bubble-left-right')->badge(55),
            '50% Deal' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false))->icon('heroicon-o-bolt')->badge(102),
            'Closed' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false))->icon('heroicon-o-hand-thumb-up')->badge(120),
            'Cold Lead' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false))->icon('heroicon-o-archive-box-arrow-down')->badge(5),
        ];
    }
}
