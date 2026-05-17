{{--
  Metric Widget
--}}
<div class="titan-os-widget-metric p-4 border rounded bg-white dark:bg-gray-900 shadow">
    <div class="text-sm text-gray-500">{{ $widget['label'] ?? 'Metric' }}</div>
    <div class="text-3xl font-bold">{{ $widget['value'] ?? '0' }}</div>
</div>