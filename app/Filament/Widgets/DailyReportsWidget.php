<?php

namespace App\Filament\Widgets;

use App\Models\DailyAccountReport;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\GenerateDailyAccountReports;

class DailyReportsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DailyAccountReport::query()
                    ->with('telegramAccount')
                    ->latest('report_date')
            )
            ->heading('Daily Account Reports')
            ->description('Overview of daily performance metrics for all Telegram accounts')
            ->headerActions([
                Action::make('generate')
                    ->label('Generate Today\'s Reports')
                    ->icon('heroicon-m-arrow-path')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Daily Reports')
                    ->modalDescription('This will generate new reports for all accounts. Continue?')
                    ->action(function () {
                        try {
                            Artisan::call(GenerateDailyAccountReports::class);

                            $this->dispatch('filament-notification', [
                                'status' => 'success',
                                'title' => 'Reports generated successfully',
                                'duration' => 3000,
                            ]);
                        } catch (\Exception $e) {
                            $this->dispatch('filament-notification', [
                                'status' => 'danger',
                                'title' => 'Error generating reports',
                                'body' => $e->getMessage(),
                                'duration' => 5000,
                            ]);
                        }
                    })
            ])
            ->columns([
                Tables\Columns\TextColumn::make('report_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('telegramAccount.name')
                    ->label('Account')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('new_leads_count')
                    ->label('New Leads')
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('closed_leads_count')
                    ->label('Closed Leads')
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('total_messages_count')
                    ->label('Messages')
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('average_response_time_seconds')
                    ->label('Avg Response Time')
                    ->formatStateUsing(fn (int $state): string => gmdate('H:i:s', $state))
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Generated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('report_date', 'desc')

            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        // Date filters
                        DateConstraint::make('report_date')
                            ->label('Report Date'),
                        DateConstraint::make('created_at')
                            ->label('Generated At'),

                        // Account relationship filter
                        RelationshipConstraint::make('telegramAccount')
                            ->label('Telegram Account')
                            ->multiple(),



                    ])
                    ->constraintPickerColumns([
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 4,
                    ])
            ])
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }
}
