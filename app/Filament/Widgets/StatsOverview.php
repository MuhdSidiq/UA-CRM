<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\TelegramAccount;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Get current and previous month's data for leads
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Calculate total leads for current and previous month
        $currentMonthLeads = Lead::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();

        $lastMonthLeads = Lead::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        // Calculate percentage change for leads
        $leadsPercentageChange = $lastMonthLeads > 0
            ? (($currentMonthLeads - $lastMonthLeads) / $lastMonthLeads) * 100
            : 0;

        // Get count of connected telegram accounts
        $telegramAccountsCount = TelegramAccount::count();

        // Calculate closed leads for current and previous month
        $currentMonthClosedLeads = Lead::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->where('status', 'closed')
            ->count();

        $lastMonthClosedLeads = Lead::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->where('status', 'closed')
            ->count();

        // Calculate percentage change for closed leads
        $closedLeadsPercentageChange = $lastMonthClosedLeads > 0
            ? (($currentMonthClosedLeads - $lastMonthClosedLeads) / $lastMonthClosedLeads) * 100
            : 0;

        return [
            Stat::make('Monthly Leads Performance', $currentMonthLeads)
                ->description(number_format(abs($leadsPercentageChange), 2) . '% ' . ($leadsPercentageChange >= 0 ? 'increase' : 'decrease'))
                ->descriptionIcon($leadsPercentageChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($leadsPercentageChange >= 0 ? 'success' : 'danger'),

            Stat::make('Closed Leads This Month', $currentMonthClosedLeads)
                ->description(abs($closedLeadsPercentageChange) . '% ' . ($closedLeadsPercentageChange >= 0 ? 'increase' : 'decrease'))
                ->descriptionIcon($closedLeadsPercentageChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($closedLeadsPercentageChange >= 0 ? 'success' : 'danger'),

            Stat::make('Connected Telegram Accounts', $telegramAccountsCount)
                ->description('Active Channels')
                ->descriptionIcon('heroicon-m-signal'),
        ];
    }
}
