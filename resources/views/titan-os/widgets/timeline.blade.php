{{--
  Timeline Widget
--}}
<div class="titan-os-widget-timeline p-4 border rounded bg-white dark:bg-gray-900 shadow">
    <div class="font-medium mb-2">{{ $widget['title'] ?? 'Timeline' }}</div>
    <ul class="space-y-2">
        @foreach (($widget['events'] ?? []) as $event)
            <li class="flex items-start space-x-2">
                <span class="text-xs text-gray-500">{{ $event['time'] ?? '' }}</span>
                <span class="flex-1">{{ $event['description'] ?? '' }}</span>
            </li>
        @endforeach
    </ul>
</div>