{{--
  Alert Widget
--}}
<div class="titan-os-widget-alert p-4 border-l-4 {{ match($widget['level'] ?? 'info') {
    'success' => 'border-green-500 bg-green-50',
    'warning' => 'border-yellow-500 bg-yellow-50',
    'danger' => 'border-red-500 bg-red-50',
    default => 'border-blue-500 bg-blue-50',
} }}">
    <div class="font-medium">{{ $widget['title'] ?? 'Alert' }}</div>
    <div class="text-sm">{{ $widget['message'] ?? '' }}</div>
</div>