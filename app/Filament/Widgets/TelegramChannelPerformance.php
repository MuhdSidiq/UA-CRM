<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;


class TelegramChannelPerformance extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Lead::query()
                    ->select([
                        'telegram_account_id as id',
                        'telegram_account_id',
                        DB::raw('COUNT(*) as total_leads'),
                        DB::raw('COUNT(DISTINCT telegram_chat_id) as unique_chats'),
                        DB::raw('SUM(CASE WHEN status = "closed" THEN 1 ELSE 0 END) as closed_leads'),
                        DB::raw('COUNT(DISTINCT country) as unique_countries'),
                        DB::raw('MIN(first_message_date) as first_interaction'),
                        DB::raw('MAX(last_message_date) as last_interaction'),
                    ])
                    ->whereNotNull('telegram_account_id')
                    ->groupBy('telegram_account_id')
            )
            ->columns([
                Tables\Columns\TextColumn::make('telegramAccount.name')
                    ->label('Channel Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_leads')
                    ->label('Total Leads')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('unique_chats')
                    ->label('Unique Chats')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('closed_leads')
                    ->label('Closed Leads')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('avg_respond_time')
                    ->label('Avg Respond Time')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('first_interaction')
                    ->label('First Activity')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('last_interaction')
                    ->label('Last Activity')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->defaultSort('total_leads', 'desc')
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('first_message_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('last_message_date', '<=', $date),
                            );
                    })
            ]);
    }
}
