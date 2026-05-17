<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    @include('command-agent::analytics.charts.published-sub-items', ['chartData' => $publishedChartData, 'months' => $publishedMonths])
    @include('command-agent::analytics.charts.engagement-rate', ['chartData' => $engagementChartData, 'months' => $engagementMonths])
    @include('command-agent::analytics.charts.impressions', ['chartData' => $impressionsChartData, 'months' => $impressionsMonths])
    @include('command-agent::analytics.charts.audience-growth', ['chartData' => $audienceChartData, 'months' => $audienceMonths])
</div>
