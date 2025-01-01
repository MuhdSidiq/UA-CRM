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
                        // Corrected average response time calculation
                        DB::raw('(
                    SELECT COALESCE(
                        AVG(response_time),
                        NULL
                    )
                    FROM (
                        SELECT
                            TIMESTAMPDIFF(
                                SECOND,
                                m1.message_timestamp,
                                MIN(m2.message_timestamp)
                            ) as response_time
                        FROM messages m1
                        LEFT JOIN messages m2 ON
                            m1.chat_id = m2.chat_id
                            AND m2.message_timestamp > m1.message_timestamp
                            AND m2.sender_type = "SALES"
                        WHERE
                            m1.sender_type = "LEAD"
                            AND m1.telegram_account_id = leads.telegram_account_id
                        GROUP BY m1.id
                    ) response_times
                ) as avg_respond_time')
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
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'N/A';

                        // Convert seconds to human readable format
                        $minutes = floor($state / 60);
                        $hours = floor($minutes / 60);
                        $days = floor($hours / 24);

                        if ($days > 0) {
                            return "{$days}d " . ($hours % 24) . "h";
                        }
                        if ($hours > 0) {
                            return "{$hours}h " . ($minutes % 60) . "m";
                        }
                        if ($minutes > 0) {
                            return "{$minutes}m";
                        }
                        return "< 1m";
                    })
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
