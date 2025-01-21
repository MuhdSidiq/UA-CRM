<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Console\Commands\GenerateDailyAccountReports;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class GenerateDailyReports extends Page
{

    protected static string $view = 'filament.pages.generate-daily-reports';
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Daily Reports';
    protected static ?string $title = 'Generate Daily Reports';
    protected static ?string $slug = 'daily-reports';
    protected static ?int $navigationSort = 3;

    public function generate(): void
    {
        try {
            // Run the command
            Artisan::call(GenerateDailyAccountReports::class);

            // Success notification
            Notification::make()
                ->title('Reports Generated Successfully')
                ->success()
                ->send();

        } catch (\Exception $e) {
            // Error notification
            Notification::make()
                ->title('Error Generating Reports')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate Reports')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action('generate')
                ->requiresConfirmation()
                ->modalHeading('Generate Daily Reports')
                ->modalDescription('Are you sure you want to generate the daily reports? This will create new reports for all accounts.')
                ->modalSubmitActionLabel('Yes, generate reports'),
        ];
    }

    protected function getViewData(): array
    {
        // Get the latest reports to show on the page
        return [
            'latestReports' => \App\Models\DailyAccountReport::with('telegramAccount')
                ->orderBy('report_date', 'desc')
                ->limit(5)
                ->get(),
        ];
    }
}
