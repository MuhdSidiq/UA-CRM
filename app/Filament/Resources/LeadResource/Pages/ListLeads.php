<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getLeadCounts(): array
    {
        return Cache::remember('lead_counts_current_month', now()->addMinutes(5), function () {
            return $this->getModel()::query()
                ->whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ])
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        });
    }

    public function getTabs(): array
    {
        $counts = $this->getLeadCounts();

        return [
            'New' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new'))
                ->icon('heroicon-o-sparkles')
                ->badge(fn () => $counts['new'] ?? 0),

            'Follow Up 1' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'f1'))
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->badge(fn () => $counts['f1'] ?? 0),

            'Follow Up 2' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'f2'))
                ->icon('heroicon-o-chat-bubble-left-right')
                ->badge(fn () => $counts['f2'] ?? 0),

            '50% Deal' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '50D'))
                ->icon('heroicon-o-bolt')
                ->badge(fn () => $counts['50D'] ?? 0),

            'Closed' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'close'))
                ->icon('heroicon-o-hand-thumb-up')
                ->badge(fn () => $counts['close'] ?? 0),

            'Cold Lead' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cold lead'))
                ->icon('heroicon-o-archive-box-arrow-down')
                ->badge(fn () => $counts['cold lead'] ?? 0),
        ];
    }
}
