<?php

namespace App\Console\Commands;

use App\Models\DailyAccountReport;
use App\Models\Lead;
use App\Models\Message;
use App\Models\TelegramAccount;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class GenerateDailyAccountReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-daily-account-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday();
        $this->info("Generating reports for {$yesterday->toDateString()}");

        // Get all telegram accounts
        TelegramAccount::chunk(100, function ($accounts) use ($yesterday) {
            foreach ($accounts as $account) {
                $this->generateReportForAccount($account, $yesterday);
            }
        });

        $this->info('Daily reports generation completed');
    }

    private function generateReportForAccount(TelegramAccount $account, Carbon $date)
    {
        // Start and end of the day
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // Count new leads
        $newLeadsCount = Lead::where('telegram_account_id', $account->id)
            ->whereBetween('first_message_date', [$startOfDay, $endOfDay])
            ->count();

        // Count closed leads (from status logs)
        $closedLeadsCount = DB::table('lead_status_logs')
            ->where('telegram_account_id', $account->id)
            ->where('new_status', 'closed')
            ->whereBetween('event_timestamp', [$startOfDay, $endOfDay])
            ->count();

        // Count total messages
        $totalMessagesCount = Message::where('telegram_account_id', $account->id)
            ->whereBetween('message_timestamp', [$startOfDay, $endOfDay])
            ->count();

        // Calculate average response time
        $responseTimeStats = $this->calculateResponseTimeStats($account, $startOfDay, $endOfDay);

        // Create or update the daily report
        DailyAccountReport::updateOrCreate(
            [
                'telegram_account_id' => $account->id,
                'report_date' => $date->toDateString(),
            ],
            [
                'new_leads_count' => $newLeadsCount,
                'closed_leads_count' => $closedLeadsCount,
                'total_messages_count' => $totalMessagesCount,
                'total_response_time_seconds' => $responseTimeStats['total_seconds'],
                'response_count' => $responseTimeStats['count'],
                'average_response_time_seconds' => $responseTimeStats['average'],
            ]
        );

        $this->info("Generated report for account {$account->name} on {$date->toDateString()}");
    }

    private function calculateResponseTimeStats(TelegramAccount $account, Carbon $startOfDay, Carbon $endOfDay)
    {
        $messages = Message::where('telegram_account_id', $account->id)
            ->whereBetween('message_timestamp', [$startOfDay, $endOfDay])
            ->orderBy('chat_id')
            ->orderBy('message_timestamp')
            ->get();

        $totalResponseTime = 0;
        $responseCount = 0;
        $lastLeadMessageTime = [];

        foreach ($messages as $message) {
            if ($message->sender_type === 'LEAD') {
                $lastLeadMessageTime[$message->chat_id] = Carbon::parse($message->message_timestamp);
            } elseif ($message->sender_type === 'SALES' && isset($lastLeadMessageTime[$message->chat_id])) {
                // Calculate response time
                $responseTime = Carbon::parse($message->message_timestamp)
                    ->diffInSeconds($lastLeadMessageTime[$message->chat_id]);
                $totalResponseTime += $responseTime;
                $responseCount++;
                // Reset last lead message time for this chat
                unset($lastLeadMessageTime[$message->chat_id]);
            }
        }

        return [
            'total_seconds' => $totalResponseTime,
            'count' => $responseCount,
            'average' => $responseCount > 0 ? round($totalResponseTime / $responseCount) : 0
        ];
    }


}
