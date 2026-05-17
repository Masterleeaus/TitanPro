{{--
  Table Widget
--}}
<div class="titan-os-widget-table p-4 border rounded bg-white dark:bg-gray-900 shadow overflow-x-auto">
    <div class="font-medium mb-2">{{ $widget['title'] ?? 'Table' }}</div>
    <table class="min-w-full text-sm">
        <thead>
            <tr>
                @foreach (($widget['columns'] ?? []) as $column)
                    <th class="px-2 py-1 border-b text-left font-semibold">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach (($widget['rows'] ?? []) as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td class="px-2 py-1 border-b">{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>