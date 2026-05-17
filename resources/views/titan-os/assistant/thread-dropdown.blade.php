{{--
  Titan Zero Thread Dropdown

  Provides a dropdown to select existing threads or create a new one.  The
  actual thread list will be populated dynamically in future passes.
--}}
<div class="titan-zero-thread-dropdown relative">
    <button class="text-xs px-2 py-1 bg-gray-200 rounded">Threads ▼</button>
    <div class="absolute right-0 mt-1 w-48 bg-white dark:bg-gray-900 border rounded shadow-lg z-10 hidden">
        <ul class="max-h-60 overflow-y-auto">
            @forelse (($threads ?? []) as $thread)
                <li><a href="#" class="block px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-800">{{ $thread['title'] }}</a></li>
            @empty
                <li><span class="block px-2 py-1 text-gray-500">No threads</span></li>
            @endforelse
        </ul>
        <div class="border-t p-1">
            <button class="text-xs">New Thread</button>
        </div>
    </div>
</div>