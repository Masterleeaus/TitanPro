{{--
  Empty State Widget

  Displayed when no content is available or when an unsupported widget
  type is requested.  It simply shows a message.
--}}
<div class="titan-os-widget-empty p-4 text-center text-gray-500 border rounded bg-white dark:bg-gray-900">
    <p>{{ $widget['message'] ?? 'Nothing to display.' }}</p>
</div>