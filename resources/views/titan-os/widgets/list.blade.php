{{--
  List Widget
--}}
<div class="titan-os-widget-list p-4 border rounded bg-white dark:bg-gray-900 shadow">
    <div class="font-medium mb-2">{{ $widget['title'] ?? 'List' }}</div>
    <ul class="list-disc list-inside space-y-1">
        @foreach ($widget['items'] ?? [] as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
</div>