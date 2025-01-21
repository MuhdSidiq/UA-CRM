<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-6">
            <div class="grid gap-4">
                <h3 class="text-lg font-medium">Latest Generated Reports</h3>

                @if($latestReports->count() > 0)
                    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach($latestReports as $report)
                                <li class="px-4 py-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $report->telegramAccount->name }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Date: {{ $report->report_date->format('Y-m-d') }}
                                            </p>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <p>New Leads: {{ $report->new_leads_count }}</p>
                                            <p>Closed: {{ $report->closed_leads_count }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-gray-500">No reports generated yet.</p>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
