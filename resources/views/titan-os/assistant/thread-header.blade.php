{{--
  Titan Zero Thread Header

  Displays the current thread title and actions such as rename or archive.
  This is a placeholder for the thread history feature that will be
  implemented in a later pass.
--}}
<div class="titan-zero-thread-header px-2 py-1 border-b flex items-center justify-between">
    <span class="font-medium">{{ $title ?? 'New Thread' }}</span>
    <div class="space-x-1">
        <button class="text-xs">Rename</button>
        <button class="text-xs">Archive</button>
    </div>
</div>