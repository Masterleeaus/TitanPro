{{--
  Card Widget
--}}
<div class="titan-os-widget-card p-4 border rounded bg-white dark:bg-gray-900 shadow">
    <div class="font-medium text-lg mb-1">{{ $widget['title'] ?? 'Card' }}</div>
    <div class="text-sm">{{ $widget['content'] ?? '' }}</div>
</div>