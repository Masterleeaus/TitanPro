{{--
  Titan Zero Thread List

  Renders a sidebar list of threads.  Placeholder only; real data will be
  integrated in later passes.
--}}
<ul class="titan-zero-thread-list space-y-1">
    @forelse (($threads ?? []) as $thread)
        <li class="p-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-800">
            {{ $thread['title'] }}
        </li>
    @empty
        @include('titan-os.assistant.thread-empty')
    @endforelse
</ul>